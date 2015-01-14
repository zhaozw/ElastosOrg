<?php

final class ConduitQueryConduitAPIMethod extends ConduitAPIMethod {

  public function getAPIMethodName() {
    return 'conduit.query';
  }

  public function getMethodDescription() {
    return 'Returns the parameters of the Conduit methods.';
  }

  public function defineParamTypes() {
    return array();
  }

  public function defineReturnType() {
    return 'dict<dict>';
  }

  public function defineErrorTypes() {
    return array();
  }

  protected function execute(ConduitAPIRequest $request) {
    $classes = id(new PhutilSymbolLoader())
      ->setAncestorClass('ConduitAPIMethod')
      ->setType('class')
      ->loadObjects();

    $names_to_params = array();
    foreach ($classes as $class) {
      $names_to_params[$class->getAPIMethodName()] = array(
        'params' => $class->defineParamTypes(),
      );
    }

    return $names_to_params;
  }

}
