<?php

final class LegalpadDocumentManageController extends LegalpadController {

  private $id;

  public function willProcessRequest(array $data) {
    $this->id = $data['id'];
  }

  public function processRequest() {
    $request = $this->getRequest();
    $user = $request->getUser();

    // NOTE: We require CAN_EDIT to view this page.

    $document = id(new LegalpadDocumentQuery())
      ->setViewer($user)
      ->withIDs(array($this->id))
      ->needDocumentBodies(true)
      ->needContributors(true)
      ->requireCapabilities(
        array(
          PhabricatorPolicyCapability::CAN_VIEW,
          PhabricatorPolicyCapability::CAN_EDIT,
        ))
      ->executeOne();
    if (!$document) {
      return new Aphront404Response();
    }

    $xactions = id(new LegalpadTransactionQuery())
      ->setViewer($user)
      ->withObjectPHIDs(array($document->getPHID()))
      ->execute();

    $subscribers = PhabricatorSubscribersQuery::loadSubscribersForPHID(
      $document->getPHID());

    $document_body = $document->getDocumentBody();
    $phids = array();
    $phids[] = $document_body->getCreatorPHID();
    foreach ($subscribers as $subscriber) {
      $phids[] = $subscriber;
    }
    foreach ($document->getContributors() as $contributor) {
      $phids[] = $contributor;
    }
    $this->loadHandles($phids);

    $engine = id(new PhabricatorMarkupEngine())
      ->setViewer($user);
    $engine->addObject(
      $document_body,
      LegalpadDocumentBody::MARKUP_FIELD_TEXT);
    foreach ($xactions as $xaction) {
      if ($xaction->getComment()) {
        $engine->addObject(
          $xaction->getComment(),
          PhabricatorApplicationTransactionComment::MARKUP_FIELD_COMMENT);
      }
    }
    $engine->process();

    $title = $document_body->getTitle();

    $header = id(new PHUIHeaderView())
      ->setHeader($title)
      ->setUser($user)
      ->setPolicyObject($document);

    $actions = $this->buildActionView($document);
    $properties = $this->buildPropertyView($document, $engine, $actions);

    $comment_form_id = celerity_generate_unique_node_id();

    $xaction_view = id(new LegalpadTransactionView())
      ->setUser($this->getRequest()->getUser())
      ->setObjectPHID($document->getPHID())
      ->setTransactions($xactions)
      ->setMarkupEngine($engine);

    $add_comment = $this->buildAddCommentView($document, $comment_form_id);

    $crumbs = $this->buildApplicationCrumbs($this->buildSideNav());
    $crumbs->setActionList($actions);
    $crumbs->addTextCrumb(
      $document->getMonogram(),
      '/'.$document->getMonogram());
    $crumbs->addTextCrumb(pht('Manage'));

    $object_box = id(new PHUIObjectBoxView())
      ->setHeader($header)
      ->addPropertyList($properties)
      ->addPropertyList($this->buildDocument($engine, $document_body));

    $content = array(
      $crumbs,
      $object_box,
      $xaction_view,
      $add_comment,
    );

    return $this->buildApplicationPage(
      $content,
      array(
        'title' => $title,
        'pageObjects' => array($document->getPHID()),
      ));
  }

  private function buildDocument(
    PhabricatorMarkupEngine
    $engine, LegalpadDocumentBody $body) {

    $view = new PHUIPropertyListView();
    $view->addClass('legalpad');
    $view->addSectionHeader(pht('Document'));
    $view->addTextContent(
      $engine->getOutput($body, LegalpadDocumentBody::MARKUP_FIELD_TEXT));

    return $view;

  }

  private function buildActionView(LegalpadDocument $document) {
    $user = $this->getRequest()->getUser();

    $actions = id(new PhabricatorActionListView())
      ->setUser($user)
      ->setObjectURI($this->getRequest()->getRequestURI())
      ->setObject($document);

    $can_edit = PhabricatorPolicyFilter::hasCapability(
      $user,
      $document,
      PhabricatorPolicyCapability::CAN_EDIT);

    $doc_id = $document->getID();

    $actions->addAction(
      id(new PhabricatorActionView())
      ->setIcon('fa-pencil-square')
      ->setName(pht('View/Sign Document'))
      ->setHref('/'.$document->getMonogram()));

    $actions->addAction(
      id(new PhabricatorActionView())
        ->setIcon('fa-pencil')
        ->setName(pht('Edit Document'))
        ->setHref($this->getApplicationURI('/edit/'.$doc_id.'/'))
        ->setDisabled(!$can_edit)
        ->setWorkflow(!$can_edit));

    $actions->addAction(
      id(new PhabricatorActionView())
      ->setIcon('fa-terminal')
      ->setName(pht('View Signatures'))
      ->setHref($this->getApplicationURI('/signatures/'.$doc_id.'/')));

    return $actions;
  }

  private function buildPropertyView(
    LegalpadDocument $document,
    PhabricatorMarkupEngine $engine,
    PhabricatorActionListView $actions) {

    $user = $this->getRequest()->getUser();

    $properties = id(new PHUIPropertyListView())
      ->setUser($user)
      ->setObject($document)
      ->setActionList($actions);

    $properties->addProperty(
      pht('Signature Type'),
      $document->getSignatureTypeName());

    $properties->addProperty(
      pht('Last Updated'),
      phabricator_datetime($document->getDateModified(), $user));

    $properties->addProperty(
      pht('Updated By'),
      $this->getHandle(
        $document->getDocumentBody()->getCreatorPHID())->renderLink());

    $properties->addProperty(
      pht('Versions'),
      $document->getVersions());

    $contributor_view = array();
    foreach ($document->getContributors() as $contributor) {
      $contributor_view[] = $this->getHandle($contributor)->renderLink();
    }
    $contributor_view = phutil_implode_html(', ', $contributor_view);
    $properties->addProperty(
      pht('Contributors'),
      $contributor_view);

    $properties->invokeWillRenderEvent();

    return $properties;
  }

  private function buildAddCommentView(
    LegalpadDocument $document,
    $comment_form_id) {
    $user = $this->getRequest()->getUser();

    $draft = PhabricatorDraft::newFromUserAndKey($user, $document->getPHID());

    $is_serious = PhabricatorEnv::getEnvConfig('phabricator.serious-business');

    $title = $is_serious
      ? pht('Add Comment')
      : pht('Debate Legislation');

    $form = id(new PhabricatorApplicationTransactionCommentView())
      ->setUser($user)
      ->setObjectPHID($document->getPHID())
      ->setFormID($comment_form_id)
      ->setHeaderText($title)
      ->setDraft($draft)
      ->setSubmitButtonName(pht('Add Comment'))
      ->setAction($this->getApplicationURI('/comment/'.$document->getID().'/'))
      ->setRequestURI($this->getRequest()->getRequestURI());

    return $form;

  }

}
