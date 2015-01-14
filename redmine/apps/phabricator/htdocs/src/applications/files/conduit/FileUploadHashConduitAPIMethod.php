<?php

final class FileUploadHashConduitAPIMethod extends FileConduitAPIMethod {

  public function getAPIMethodName() {
    return 'file.uploadhash';
  }

  public function getMethodDescription() {
    return 'Upload a file to the server using content hash.';
  }

  public function defineParamTypes() {
    return array(
      'hash' => 'required nonempty string',
      'name' => 'required nonempty string',
    );
  }

  public function defineReturnType() {
    return 'phid or null';
  }

  public function defineErrorTypes() {
    return array(
    );
  }

  protected function execute(ConduitAPIRequest $request) {
    $hash = $request->getValue('hash');
    $name = $request->getValue('name');
    $user = $request->getUser();

    $file = PhabricatorFile::newFileFromContentHash(
      $hash,
      array(
        'name' => $name,
        'authorPHID' => $user->getPHID(),
      ));

    if ($file) {
      return $file->getPHID();
    }
    return $file;
  }

}
