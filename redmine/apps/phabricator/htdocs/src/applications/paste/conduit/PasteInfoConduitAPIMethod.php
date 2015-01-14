<?php

final class PasteInfoConduitAPIMethod extends PasteConduitAPIMethod {

  public function getAPIMethodName() {
    return 'paste.info';
  }

  public function getMethodStatus() {
    return self::METHOD_STATUS_DEPRECATED;
  }

  public function getMethodStatusDescription() {
    return "Replaced by 'paste.query'.";
  }

  public function getMethodDescription() {
    return 'Retrieve an array of information about a paste.';
  }

  public function defineParamTypes() {
    return array(
      'paste_id' => 'required id',
    );
  }

  public function defineReturnType() {
    return 'nonempty dict';
  }

  public function defineErrorTypes() {
    return array(
      'ERR_BAD_PASTE' => 'No such paste exists',
    );
  }

  protected function execute(ConduitAPIRequest $request) {
    $paste_id = $request->getValue('paste_id');
    $paste = id(new PhabricatorPasteQuery())
      ->setViewer($request->getUser())
      ->withIDs(array($paste_id))
      ->needRawContent(true)
      ->executeOne();
    if (!$paste) {
      throw new ConduitException('ERR_BAD_PASTE');
    }
    return $this->buildPasteInfoDictionary($paste);
  }

}
