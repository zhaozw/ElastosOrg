<?php

final class UserFindConduitAPIMethod extends UserConduitAPIMethod {

  public function getAPIMethodName() {
    return 'user.find';
  }

  public function getMethodStatus() {
    return self::METHOD_STATUS_DEPRECATED;
  }

  public function getMethodStatusDescription() {
    return pht('Obsoleted by "user.query".');
  }

  public function getMethodDescription() {
    return pht('Lookup PHIDs by username. Obsoleted by "user.query".');
  }

  public function defineParamTypes() {
    return array(
      'aliases' => 'required list<string>',
    );
  }

  public function defineReturnType() {
    return 'nonempty dict<string, phid>';
  }

  public function defineErrorTypes() {
    return array(
    );
  }

  protected function execute(ConduitAPIRequest $request) {
    $users = id(new PhabricatorPeopleQuery())
      ->setViewer($request->getUser())
      ->withUsernames($request->getValue('aliases', array()))
      ->execute();

    return mpull($users, 'getPHID', 'getUsername');
  }

}
