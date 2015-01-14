<?php

final class HarbormasterUIEventListener
  extends PhabricatorEventListener {

  public function register() {
    $this->listen(PhabricatorEventType::TYPE_UI_WILLRENDERPROPERTIES);
  }

  public function handleEvent(PhutilEvent $event) {
    switch ($event->getType()) {
      case PhabricatorEventType::TYPE_UI_WILLRENDERPROPERTIES:
        $this->handlePropertyEvent($event);
        break;
    }
  }

  private function handlePropertyEvent($ui_event) {
    $user = $ui_event->getUser();
    $object = $ui_event->getValue('object');

    if (!$object || !$object->getPHID()) {
      // No object, or the object has no PHID yet..
      return;
    }

    if ($object instanceof HarbormasterBuildable) {
      // Although HarbormasterBuildable implements the correct interface, it
      // does not make sense to show a build's build status. In the best case
      // it is meaningless, and in the worst case it's confusing.
      return;
    }

    if (!($object instanceof HarbormasterBuildableInterface)) {
      return;
    }

    $buildable_phid = $object->getHarbormasterBuildablePHID();
    if (!$buildable_phid) {
      return;
    }

    if (!$this->canUseApplication($ui_event->getUser())) {
      return;
    }

    $buildables = id(new HarbormasterBuildableQuery())
      ->setViewer($user)
      ->withManualBuildables(false)
      ->withBuildablePHIDs(array($buildable_phid))
      ->execute();
    if (!$buildables) {
      return;
    }

    $builds = id(new HarbormasterBuildQuery())
      ->setViewer($user)
      ->withBuildablePHIDs(mpull($buildables, 'getPHID'))
      ->execute();
    if (!$builds) {
      return;
    }

    $build_handles = id(new PhabricatorHandleQuery())
      ->setViewer($user)
      ->withPHIDs(mpull($builds, 'getPHID'))
      ->execute();

    $status_view = new PHUIStatusListView();

    foreach ($builds as $build) {
      $item = new PHUIStatusItemView();
      $item->setTarget($build_handles[$build->getPHID()]->renderLink());

      $status = $build->getBuildStatus();
      $status_name = HarbormasterBuild::getBuildStatusName($status);
      $icon = HarbormasterBuild::getBuildStatusIcon($status);
      $color = HarbormasterBuild::getBuildStatusColor($status);

      $item->setIcon($icon, $color, $status_name);


      $status_view->addItem($item);
    }

    $view = $ui_event->getValue('view');
    $view->addProperty(pht('Build Status'), $status_view);
  }

}
