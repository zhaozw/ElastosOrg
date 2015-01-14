<?php

final class PhabricatorDashboardPanelSearchApplicationCustomField
  extends PhabricatorStandardCustomField {

  public function getFieldType() {
    return 'search.application';
  }

  public function shouldAppearInApplicationSearch() {
    return false;
  }

  public function renderEditControl(array $handles) {

    $engines = id(new PhutilSymbolLoader())
      ->setAncestorClass('PhabricatorApplicationSearchEngine')
      ->loadObjects();

    $options = array();

    $value = $this->getFieldValue();
    if (strlen($value) && empty($engines[$value])) {
      $options[$value] = $value;
    }

    $engines = msort($engines, 'getResultTypeDescription');
    foreach ($engines as $class_name => $engine) {
      $options[$class_name] = $engine->getResultTypeDescription();
    }

    return id(new AphrontFormSelectControl())
      ->setID($this->getFieldControlID())
      ->setLabel($this->getFieldName())
      ->setCaption($this->getCaption())
      ->setName($this->getFieldKey())
      ->setValue($this->getFieldValue())
      ->setOptions($options);
  }

}
