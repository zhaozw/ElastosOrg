<?php

final class PhabricatorDashboardTransactionEditor
  extends PhabricatorApplicationTransactionEditor {

  public function getEditorApplicationClass() {
    return 'PhabricatorDashboardApplication';
  }

  public function getEditorObjectsDescription() {
    return pht('Dashboards');
  }

  public static function addPanelToDashboard(
    PhabricatorUser $actor,
    PhabricatorContentSource $content_source,
    PhabricatorDashboardPanel $panel,
    PhabricatorDashboard $dashboard,
    $column) {

    $xactions = array();
    $xactions[] = id(new PhabricatorDashboardTransaction())
      ->setTransactionType(PhabricatorTransactions::TYPE_EDGE)
      ->setMetadataValue(
        'edge:type',
        PhabricatorEdgeConfig::TYPE_DASHBOARD_HAS_PANEL)
      ->setNewValue(
        array(
          '+' => array(
            $panel->getPHID() => $panel->getPHID(),
          ),
        ));

    $layout_config = $dashboard->getLayoutConfigObject();
    $layout_config->setPanelLocation($column, $panel->getPHID());
    $dashboard->setLayoutConfigFromObject($layout_config);

    $editor = id(new PhabricatorDashboardTransactionEditor())
      ->setActor($actor)
      ->setContentSource($content_source)
      ->setContinueOnMissingFields(true)
      ->setContinueOnNoEffect(true)
      ->applyTransactions($dashboard, $xactions);
  }

  public function getTransactionTypes() {
    $types = parent::getTransactionTypes();

    $types[] = PhabricatorTransactions::TYPE_VIEW_POLICY;
    $types[] = PhabricatorTransactions::TYPE_EDIT_POLICY;
    $types[] = PhabricatorTransactions::TYPE_EDGE;

    $types[] = PhabricatorDashboardTransaction::TYPE_NAME;
    $types[] = PhabricatorDashboardTransaction::TYPE_LAYOUT_MODE;

    return $types;
  }

  protected function getCustomTransactionOldValue(
    PhabricatorLiskDAO $object,
    PhabricatorApplicationTransaction $xaction) {
    switch ($xaction->getTransactionType()) {
      case PhabricatorDashboardTransaction::TYPE_NAME:
        if ($this->getIsNewObject()) {
          return null;
        }
        return $object->getName();
      case PhabricatorDashboardTransaction::TYPE_LAYOUT_MODE:
        if ($this->getIsNewObject()) {
          return null;
        }
        $layout_config = $object->getLayoutConfigObject();
        return $layout_config->getLayoutMode();
    }

    return parent::getCustomTransactionOldValue($object, $xaction);
  }

  protected function getCustomTransactionNewValue(
    PhabricatorLiskDAO $object,
    PhabricatorApplicationTransaction $xaction) {
    switch ($xaction->getTransactionType()) {
      case PhabricatorDashboardTransaction::TYPE_NAME:
      case PhabricatorDashboardTransaction::TYPE_LAYOUT_MODE:
        return $xaction->getNewValue();
    }
    return parent::getCustomTransactionNewValue($object, $xaction);
  }

  protected function applyCustomInternalTransaction(
    PhabricatorLiskDAO $object,
    PhabricatorApplicationTransaction $xaction) {
    switch ($xaction->getTransactionType()) {
      case PhabricatorDashboardTransaction::TYPE_NAME:
        $object->setName($xaction->getNewValue());
        return;
      case PhabricatorDashboardTransaction::TYPE_LAYOUT_MODE:
        $old_layout = $object->getLayoutConfigObject();
        $new_layout = clone $old_layout;
        $new_layout->setLayoutMode($xaction->getNewValue());
        if ($old_layout->isMultiColumnLayout() !=
            $new_layout->isMultiColumnLayout()) {
          $panel_phids = $object->getPanelPHIDs();
          $new_locations = $new_layout->getDefaultPanelLocations();
          foreach ($panel_phids as $panel_phid) {
            $new_locations[0][] = $panel_phid;
          }
          $new_layout->setPanelLocations($new_locations);
        }
        $object->setLayoutConfigFromObject($new_layout);
        return;
      case PhabricatorTransactions::TYPE_VIEW_POLICY:
        $object->setViewPolicy($xaction->getNewValue());
        return;
      case PhabricatorTransactions::TYPE_EDIT_POLICY:
        $object->setEditPolicy($xaction->getNewValue());
        return;
      case PhabricatorTransactions::TYPE_EDGE:
        return;
    }

    return parent::applyCustomInternalTransaction($object, $xaction);
  }

  protected function applyCustomExternalTransaction(
    PhabricatorLiskDAO $object,
    PhabricatorApplicationTransaction $xaction) {

    switch ($xaction->getTransactionType()) {
      case PhabricatorDashboardTransaction::TYPE_NAME:
      case PhabricatorDashboardTransaction::TYPE_LAYOUT_MODE:
      case PhabricatorTransactions::TYPE_VIEW_POLICY:
      case PhabricatorTransactions::TYPE_EDIT_POLICY:
        return;
      case PhabricatorTransactions::TYPE_EDGE:
        return;
    }

    return parent::applyCustomExternalTransaction($object, $xaction);
  }

  protected function validateTransaction(
    PhabricatorLiskDAO $object,
    $type,
    array $xactions) {

    $errors = parent::validateTransaction($object, $type, $xactions);

    switch ($type) {
      case PhabricatorDashboardTransaction::TYPE_NAME:
        $missing = $this->validateIsEmptyTextField(
          $object->getName(),
          $xactions);

        if ($missing) {
          $error = new PhabricatorApplicationTransactionValidationError(
            $type,
            pht('Required'),
            pht('Dashboard name is required.'),
            nonempty(last($xactions), null));

          $error->setIsMissingFieldError(true);
          $errors[] = $error;
        }
        break;
    }

    return $errors;
  }


}
