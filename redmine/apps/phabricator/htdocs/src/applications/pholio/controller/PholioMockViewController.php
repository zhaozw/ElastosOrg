<?php

final class PholioMockViewController extends PholioController {

  private $id;
  private $imageID;
  private $maniphestTaskPHIDs = array();

  private function setManiphestTaskPHIDs($maniphest_task_phids) {
    $this->maniphestTaskPHIDs = $maniphest_task_phids;
    return $this;
  }
  private function getManiphestTaskPHIDs() {
    return $this->maniphestTaskPHIDs;
  }

  public function shouldAllowPublic() {
    return true;
  }

  public function willProcessRequest(array $data) {
    $this->id = $data['id'];
    $this->imageID = idx($data, 'imageID');
  }

  public function processRequest() {
    $request = $this->getRequest();
    $user = $request->getUser();

    $mock = id(new PholioMockQuery())
      ->setViewer($user)
      ->withIDs(array($this->id))
      ->needImages(true)
      ->needInlineComments(true)
      ->executeOne();

    if (!$mock) {
      return new Aphront404Response();
    }

    $xactions = id(new PholioTransactionQuery())
      ->setViewer($user)
      ->withObjectPHIDs(array($mock->getPHID()))
      ->execute();

    $phids = PhabricatorEdgeQuery::loadDestinationPHIDs(
      $mock->getPHID(),
      PhabricatorEdgeConfig::TYPE_MOCK_HAS_TASK);
    $this->setManiphestTaskPHIDs($phids);
    $phids[] = $mock->getAuthorPHID();
    $this->loadHandles($phids);

    $engine = id(new PhabricatorMarkupEngine())
      ->setViewer($user);
    $engine->addObject($mock, PholioMock::MARKUP_FIELD_DESCRIPTION);
    foreach ($xactions as $xaction) {
      if ($xaction->getComment()) {
        $engine->addObject(
          $xaction->getComment(),
          PhabricatorApplicationTransactionComment::MARKUP_FIELD_COMMENT);
      }
    }
    $engine->process();

    $title = $mock->getName();

    if ($mock->isClosed()) {
      $header_icon = 'fa-ban';
      $header_name = pht('Closed');
      $header_color = 'dark';
    } else {
      $header_icon = 'fa-square-o';
      $header_name = pht('Open');
      $header_color = 'bluegrey';
    }

    $header = id(new PHUIHeaderView())
      ->setHeader($title)
      ->setUser($user)
      ->setStatus($header_icon, $header_color, $header_name)
      ->setPolicyObject($mock);

    $actions = $this->buildActionView($mock);
    $properties = $this->buildPropertyView($mock, $engine, $actions);

    require_celerity_resource('pholio-css');
    require_celerity_resource('pholio-inline-comments-css');

    $comment_form_id = celerity_generate_unique_node_id();
    $output = id(new PholioMockImagesView())
      ->setRequestURI($request->getRequestURI())
      ->setCommentFormID($comment_form_id)
      ->setUser($user)
      ->setMock($mock)
      ->setImageID($this->imageID);

    $output = id(new PHUIObjectBoxView())
      ->setHeaderText(pht('Image'))
      ->appendChild($output);

    $xaction_view = id(new PholioTransactionView())
      ->setUser($this->getRequest()->getUser())
      ->setMock($mock)
      ->setObjectPHID($mock->getPHID())
      ->setTransactions($xactions)
      ->setMarkupEngine($engine);

    $add_comment = $this->buildAddCommentView($mock, $comment_form_id);

    $crumbs = $this->buildApplicationCrumbs();
    $crumbs->setActionList($actions);
    $crumbs->addTextCrumb('M'.$mock->getID(), '/M'.$mock->getID());

    $object_box = id(new PHUIObjectBoxView())
      ->setHeader($header)
      ->addPropertyList($properties);

    $thumb_grid = id(new PholioMockThumbGridView())
      ->setUser($user)
      ->setMock($mock);

    $content = array(
      $crumbs,
      $object_box,
      $output,
      $thumb_grid,
      $xaction_view,
      $add_comment,
    );

    return $this->buildApplicationPage(
      $content,
      array(
        'title' => 'M'.$mock->getID().' '.$title,
        'pageObjects' => array($mock->getPHID()),
      ));
  }

  private function buildActionView(PholioMock $mock) {
    $user = $this->getRequest()->getUser();

    $actions = id(new PhabricatorActionListView())
      ->setUser($user)
      ->setObjectURI($this->getRequest()->getRequestURI())
      ->setObject($mock);

    $can_edit = PhabricatorPolicyFilter::hasCapability(
      $user,
      $mock,
      PhabricatorPolicyCapability::CAN_EDIT);

    $actions->addAction(
      id(new PhabricatorActionView())
      ->setIcon('fa-pencil')
      ->setName(pht('Edit Mock'))
      ->setHref($this->getApplicationURI('/edit/'.$mock->getID().'/'))
      ->setDisabled(!$can_edit)
      ->setWorkflow(!$can_edit));

    $actions->addAction(
      id(new PhabricatorActionView())
      ->setIcon('fa-anchor')
      ->setName(pht('Edit Maniphest Tasks'))
      ->setHref("/search/attach/{$mock->getPHID()}/TASK/edge/")
      ->setDisabled(!$user->isLoggedIn())
      ->setWorkflow(true));

    return $actions;
  }

  private function buildPropertyView(
    PholioMock $mock,
    PhabricatorMarkupEngine $engine,
    PhabricatorActionListView $actions) {

    $user = $this->getRequest()->getUser();

    $properties = id(new PHUIPropertyListView())
      ->setUser($user)
      ->setObject($mock)
      ->setActionList($actions);

    $properties->addProperty(
      pht('Author'),
      $this->getHandle($mock->getAuthorPHID())->renderLink());

    $properties->addProperty(
      pht('Created'),
      phabricator_datetime($mock->getDateCreated(), $user));

    if ($this->getManiphestTaskPHIDs()) {
      $properties->addProperty(
        pht('Maniphest Tasks'),
        $this->renderHandlesForPHIDs($this->getManiphestTaskPHIDs()));
    }

    $properties->invokeWillRenderEvent();

    $properties->addSectionHeader(
        pht('Description'),
        PHUIPropertyListView::ICON_SUMMARY);

    $properties->addImageContent(
        $engine->getOutput($mock, PholioMock::MARKUP_FIELD_DESCRIPTION));

    return $properties;
  }

  private function buildAddCommentView(PholioMock $mock, $comment_form_id) {
    $user = $this->getRequest()->getUser();

    $draft = PhabricatorDraft::newFromUserAndKey($user, $mock->getPHID());

    $is_serious = PhabricatorEnv::getEnvConfig('phabricator.serious-business');
    $title = $is_serious
      ? pht('Add Comment')
      : pht('History Beckons');

    $form = id(new PhabricatorApplicationTransactionCommentView())
      ->setUser($user)
      ->setObjectPHID($mock->getPHID())
      ->setFormID($comment_form_id)
      ->setDraft($draft)
      ->setHeaderText($title)
      ->setSubmitButtonName(pht('Add Comment'))
      ->setAction($this->getApplicationURI('/comment/'.$mock->getID().'/'))
      ->setRequestURI($this->getRequest()->getRequestURI());

    return $form;
  }

}
