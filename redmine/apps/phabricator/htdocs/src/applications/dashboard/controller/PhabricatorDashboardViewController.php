<?php

final class PhabricatorDashboardViewController
  extends PhabricatorDashboardController {

  private $id;

  public function shouldAllowPublic() {
    return true;
  }

  public function willProcessRequest(array $data) {
    $this->id = $data['id'];
  }

  public function processRequest() {
    $request = $this->getRequest();
    $viewer = $request->getUser();

    $dashboard = id(new PhabricatorDashboardQuery())
      ->setViewer($viewer)
      ->withIDs(array($this->id))
      ->needPanels(true)
      ->executeOne();
    if (!$dashboard) {
      return new Aphront404Response();
    }

    $title = $dashboard->getName();
    $crumbs = $this->buildApplicationCrumbs();
    $crumbs->addTextCrumb(pht('Dashboard %d', $dashboard->getID()));

    if ($dashboard->getPanelPHIDs()) {
      $rendered_dashboard = id(new PhabricatorDashboardRenderingEngine())
        ->setViewer($viewer)
        ->setDashboard($dashboard)
        ->renderDashboard();
    } else {
      $rendered_dashboard = $this->buildEmptyView();
    }

    return $this->buildApplicationPage(
      array(
        $crumbs,
        $rendered_dashboard,
      ),
      array(
        'title' => $title,
      ));
  }

  public function buildApplicationCrumbs() {
    $crumbs = parent::buildApplicationCrumbs();
    $id = $this->id;

    $crumbs->addAction(
      id(new PHUIListItemView())
        ->setIcon('fa-th')
        ->setName(pht('Manage Dashboard'))
        ->setHref($this->getApplicationURI("manage/{$id}/")));

    return $crumbs;
  }

  public function buildEmptyView() {
    $id = $this->id;
    $manage_uri = $this->getApplicationURI("manage/{$id}/");

    return id(new AphrontErrorView())
      ->setSeverity(AphrontErrorView::SEVERITY_NODATA)
      ->appendChild(
        pht('This dashboard has no panels '.
          'yet. Use %s to add panels.',
          phutil_tag(
            'a',
            array('href' => $manage_uri),
            pht('Manage Dashboard'))));
  }

}
