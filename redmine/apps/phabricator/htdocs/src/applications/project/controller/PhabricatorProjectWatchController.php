<?php

final class PhabricatorProjectWatchController
  extends PhabricatorProjectController {

  private $id;
  private $action;

  public function willProcessRequest(array $data) {
    $this->id = $data['id'];
    $this->action = $data['action'];
  }

  public function processRequest() {
    $request = $this->getRequest();
    $viewer = $request->getUser();

    $project = id(new PhabricatorProjectQuery())
      ->setViewer($viewer)
      ->withIDs(array($this->id))
      ->needMembers(true)
      ->needWatchers(true)
      ->executeOne();
    if (!$project) {
      return new Aphront404Response();
    }

    $project_uri = '/project/view/'.$project->getID().'/';

    // You must be a member of a project to
    if (!$project->isUserMember($viewer->getPHID())) {
      return new Aphront400Response();
    }

    if ($request->isDialogFormPost()) {
      $edge_action = null;
      switch ($this->action) {
        case 'watch':
          $edge_action = '+';
          $force_subscribe = true;
          break;
        case 'unwatch':
          $edge_action = '-';
          $force_subscribe = false;
          break;
      }

      $type_member = PhabricatorEdgeConfig::TYPE_OBJECT_HAS_WATCHER;
      $member_spec = array(
        $edge_action => array($viewer->getPHID() => $viewer->getPHID()),
      );

      $xactions = array();
      $xactions[] = id(new PhabricatorProjectTransaction())
        ->setTransactionType(PhabricatorTransactions::TYPE_EDGE)
        ->setMetadataValue('edge:type', $type_member)
        ->setNewValue($member_spec);

      $editor = id(new PhabricatorProjectTransactionEditor($project))
        ->setActor($viewer)
        ->setContentSourceFromRequest($request)
        ->setContinueOnNoEffect(true)
        ->setContinueOnMissingFields(true)
        ->applyTransactions($project, $xactions);

      return id(new AphrontRedirectResponse())->setURI($project_uri);
    }

    $dialog = null;
    switch ($this->action) {
      case 'watch':
        $title = pht('Watch Project?');
        $body = pht(
          'Watching a project will let you monitor it closely. You will '.
          'receive email and notifications about changes to every object '.
          'associated with projects you watch.');
        $submit = pht('Watch Project');
        break;
      case 'unwatch':
        $title = pht('Unwatch Project?');
        $body = pht(
          'You will no longer receive email or notifications about every '.
          'object associated with this project.');
        $submit = pht('Unwatch Project');
        break;
      default:
        return new Aphront404Response();
    }

    return $this->newDialog()
      ->setTitle($title)
      ->appendParagraph($body)
      ->addCancelButton($project_uri)
      ->addSubmitButton($submit);
  }

}
