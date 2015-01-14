<?php

/**
 * Parameters
 * ==========
 *
 * When creating a new file using a method like @{method:newFromFileData}, these
 * parameters are supported:
 *
 *   | name | Human readable filename.
 *   | authorPHID | User PHID of uploader.
 *   | ttl | Temporary file lifetime, in seconds.
 *   | viewPolicy | File visibility policy.
 *   | isExplicitUpload | Used to show users files they explicitly uploaded.
 *   | canCDN | Allows the file to be cached and delivered over a CDN.
 *   | mime-type | Optional, explicit file MIME type.
 *
 */
final class PhabricatorFile extends PhabricatorFileDAO
  implements
    PhabricatorTokenReceiverInterface,
    PhabricatorSubscribableInterface,
    PhabricatorFlaggableInterface,
    PhabricatorPolicyInterface,
    PhabricatorDestructibleInterface {

  const ONETIME_TEMPORARY_TOKEN_TYPE = 'file:onetime';
  const STORAGE_FORMAT_RAW  = 'raw';

  const METADATA_IMAGE_WIDTH  = 'width';
  const METADATA_IMAGE_HEIGHT = 'height';
  const METADATA_CAN_CDN = 'canCDN';

  protected $name;
  protected $mimeType;
  protected $byteSize;
  protected $authorPHID;
  protected $secretKey;
  protected $contentHash;
  protected $metadata = array();
  protected $mailKey;

  protected $storageEngine;
  protected $storageFormat;
  protected $storageHandle;

  protected $ttl;
  protected $isExplicitUpload = 1;
  protected $viewPolicy = PhabricatorPolicies::POLICY_USER;

  private $objects = self::ATTACHABLE;
  private $objectPHIDs = self::ATTACHABLE;
  private $originalFile = self::ATTACHABLE;

  public static function initializeNewFile() {
    $app = id(new PhabricatorApplicationQuery())
      ->setViewer(PhabricatorUser::getOmnipotentUser())
      ->withClasses(array('PhabricatorFilesApplication'))
      ->executeOne();

    $view_policy = $app->getPolicy(
      FilesDefaultViewCapability::CAPABILITY);

    return id(new PhabricatorFile())
      ->setViewPolicy($view_policy)
      ->attachOriginalFile(null)
      ->attachObjects(array())
      ->attachObjectPHIDs(array());
  }

  public function getConfiguration() {
    return array(
      self::CONFIG_AUX_PHID => true,
      self::CONFIG_SERIALIZATION => array(
        'metadata' => self::SERIALIZATION_JSON,
      ),
      self::CONFIG_COLUMN_SCHEMA => array(
        'name' => 'text255?',
        'mimeType' => 'text255?',
        'byteSize' => 'uint64',
        'storageEngine' => 'text32',
        'storageFormat' => 'text32',
        'storageHandle' => 'text255',
        'authorPHID' => 'phid?',
        'secretKey' => 'bytes20?',
        'contentHash' => 'bytes40?',
        'ttl' => 'epoch?',
        'isExplicitUpload' => 'bool?',
        'mailKey' => 'bytes20',
      ),
      self::CONFIG_KEY_SCHEMA => array(
        'key_phid' => null,
        'phid' => array(
          'columns' => array('phid'),
          'unique' => true,
        ),
        'authorPHID' => array(
          'columns' => array('authorPHID'),
        ),
        'contentHash' => array(
          'columns' => array('contentHash'),
        ),
        'key_ttl' => array(
          'columns' => array('ttl'),
        ),
        'key_dateCreated' => array(
          'columns' => array('dateCreated'),
        ),
      ),
    ) + parent::getConfiguration();
  }

  public function generatePHID() {
    return PhabricatorPHID::generateNewPHID(
      PhabricatorFileFilePHIDType::TYPECONST);
  }

  public function save() {
    if (!$this->getSecretKey()) {
      $this->setSecretKey($this->generateSecretKey());
    }
    if (!$this->getMailKey()) {
      $this->setMailKey(Filesystem::readRandomCharacters(20));
    }
    return parent::save();
  }

  public function getMonogram() {
    return 'F'.$this->getID();
  }

  public static function readUploadedFileData($spec) {
    if (!$spec) {
      throw new Exception('No file was uploaded!');
    }

    $err = idx($spec, 'error');
    if ($err) {
      throw new PhabricatorFileUploadException($err);
    }

    $tmp_name = idx($spec, 'tmp_name');
    $is_valid = @is_uploaded_file($tmp_name);
    if (!$is_valid) {
      throw new Exception('File is not an uploaded file.');
    }

    $file_data = Filesystem::readFile($tmp_name);
    $file_size = idx($spec, 'size');

    if (strlen($file_data) != $file_size) {
      throw new Exception('File size disagrees with uploaded size.');
    }

    self::validateFileSize(strlen($file_data));

    return $file_data;
  }

  public static function newFromPHPUpload($spec, array $params = array()) {
    $file_data = self::readUploadedFileData($spec);

    $file_name = nonempty(
      idx($params, 'name'),
      idx($spec,   'name'));
    $params = array(
      'name' => $file_name,
    ) + $params;

    return self::newFromFileData($file_data, $params);
  }

  public static function newFromXHRUpload($data, array $params = array()) {
    self::validateFileSize(strlen($data));
    return self::newFromFileData($data, $params);
  }

  private static function validateFileSize($size) {
    $limit = PhabricatorEnv::getEnvConfig('storage.upload-size-limit');
    if (!$limit) {
      return;
    }

    $limit = phutil_parse_bytes($limit);
    if ($size > $limit) {
      throw new PhabricatorFileUploadException(-1000);
    }
  }


  /**
   * Given a block of data, try to load an existing file with the same content
   * if one exists. If it does not, build a new file.
   *
   * This method is generally used when we have some piece of semi-trusted data
   * like a diff or a file from a repository that we want to show to the user.
   * We can't just dump it out because it may be dangerous for any number of
   * reasons; instead, we need to serve it through the File abstraction so it
   * ends up on the CDN domain if one is configured and so on. However, if we
   * simply wrote a new file every time we'd potentially end up with a lot
   * of redundant data in file storage.
   *
   * To solve these problems, we use file storage as a cache and reuse the
   * same file again if we've previously written it.
   *
   * NOTE: This method unguards writes.
   *
   * @param string  Raw file data.
   * @param dict    Dictionary of file information.
   */
  public static function buildFromFileDataOrHash(
    $data,
    array $params = array()) {

    $file = id(new PhabricatorFile())->loadOneWhere(
      'name = %s AND contentHash = %s LIMIT 1',
      self::normalizeFileName(idx($params, 'name')),
      self::hashFileContent($data));

    if (!$file) {
      $unguarded = AphrontWriteGuard::beginScopedUnguardedWrites();
      $file = PhabricatorFile::newFromFileData($data, $params);
      unset($unguarded);
    }

    return $file;
  }

  public static function newFileFromContentHash($hash, array $params) {
    // Check to see if a file with same contentHash exist
    $file = id(new PhabricatorFile())->loadOneWhere(
      'contentHash = %s LIMIT 1',
      $hash);

    if ($file) {
      // copy storageEngine, storageHandle, storageFormat
      $copy_of_storage_engine = $file->getStorageEngine();
      $copy_of_storage_handle = $file->getStorageHandle();
      $copy_of_storage_format = $file->getStorageFormat();
      $copy_of_byteSize = $file->getByteSize();
      $copy_of_mimeType = $file->getMimeType();

      $new_file = PhabricatorFile::initializeNewFile();

      $new_file->setByteSize($copy_of_byteSize);

      $new_file->setContentHash($hash);
      $new_file->setStorageEngine($copy_of_storage_engine);
      $new_file->setStorageHandle($copy_of_storage_handle);
      $new_file->setStorageFormat($copy_of_storage_format);
      $new_file->setMimeType($copy_of_mimeType);
      $new_file->copyDimensions($file);

      $new_file->readPropertiesFromParameters($params);

      $new_file->save();

      return $new_file;
    }

    return $file;
  }

  private static function buildFromFileData($data, array $params = array()) {

    if (isset($params['storageEngines'])) {
      $engines = $params['storageEngines'];
    } else {
      $selector = PhabricatorEnv::newObjectFromConfig(
        'storage.engine-selector');
      $engines = $selector->selectStorageEngines($data, $params);
    }

    assert_instances_of($engines, 'PhabricatorFileStorageEngine');
    if (!$engines) {
      throw new Exception('No valid storage engines are available!');
    }

    $file = PhabricatorFile::initializeNewFile();

    $data_handle = null;
    $engine_identifier = null;
    $exceptions = array();
    foreach ($engines as $engine) {
      $engine_class = get_class($engine);
      try {
        list($engine_identifier, $data_handle) = $file->writeToEngine(
          $engine,
          $data,
          $params);

        // We stored the file somewhere so stop trying to write it to other
        // places.
        break;
      } catch (PhabricatorFileStorageConfigurationException $ex) {
        // If an engine is outright misconfigured (or misimplemented), raise
        // that immediately since it probably needs attention.
        throw $ex;
      } catch (Exception $ex) {
        phlog($ex);

        // If an engine doesn't work, keep trying all the other valid engines
        // in case something else works.
        $exceptions[$engine_class] = $ex;
      }
    }

    if (!$data_handle) {
      throw new PhutilAggregateException(
        'All storage engines failed to write file:',
        $exceptions);
    }

    $file->setByteSize(strlen($data));
    $file->setContentHash(self::hashFileContent($data));

    $file->setStorageEngine($engine_identifier);
    $file->setStorageHandle($data_handle);

    // TODO: This is probably YAGNI, but allows for us to do encryption or
    // compression later if we want.
    $file->setStorageFormat(self::STORAGE_FORMAT_RAW);

    $file->readPropertiesFromParameters($params);

    if (!$file->getMimeType()) {
      $tmp = new TempFile();
      Filesystem::writeFile($tmp, $data);
      $file->setMimeType(Filesystem::getMimeType($tmp));
    }

    try {
      $file->updateDimensions(false);
    } catch (Exception $ex) {
      // Do nothing
    }

    $file->save();

    return $file;
  }

  public static function newFromFileData($data, array $params = array()) {
    $hash = self::hashFileContent($data);
    $file = self::newFileFromContentHash($hash, $params);

    if ($file) {
      return $file;
    }

    return self::buildFromFileData($data, $params);
  }

  public function migrateToEngine(PhabricatorFileStorageEngine $engine) {
    if (!$this->getID() || !$this->getStorageHandle()) {
      throw new Exception(
        "You can not migrate a file which hasn't yet been saved.");
    }

    $data = $this->loadFileData();
    $params = array(
      'name' => $this->getName(),
    );

    list($new_identifier, $new_handle) = $this->writeToEngine(
      $engine,
      $data,
      $params);

    $old_engine = $this->instantiateStorageEngine();
    $old_identifier = $this->getStorageEngine();
    $old_handle = $this->getStorageHandle();

    $this->setStorageEngine($new_identifier);
    $this->setStorageHandle($new_handle);
    $this->save();

    $this->deleteFileDataIfUnused(
      $old_engine,
      $old_identifier,
      $old_handle);

    return $this;
  }

  private function writeToEngine(
    PhabricatorFileStorageEngine $engine,
    $data,
    array $params) {

    $engine_class = get_class($engine);

    $data_handle = $engine->writeFile($data, $params);

    if (!$data_handle || strlen($data_handle) > 255) {
      // This indicates an improperly implemented storage engine.
      throw new PhabricatorFileStorageConfigurationException(
        "Storage engine '{$engine_class}' executed writeFile() but did ".
        "not return a valid handle ('{$data_handle}') to the data: it ".
        "must be nonempty and no longer than 255 characters.");
    }

    $engine_identifier = $engine->getEngineIdentifier();
    if (!$engine_identifier || strlen($engine_identifier) > 32) {
      throw new PhabricatorFileStorageConfigurationException(
        "Storage engine '{$engine_class}' returned an improper engine ".
        "identifier '{$engine_identifier}': it must be nonempty ".
        "and no longer than 32 characters.");
    }

    return array($engine_identifier, $data_handle);
  }


  public static function newFromFileDownload($uri, array $params = array()) {
    // Make sure we're allowed to make a request first
    if (!PhabricatorEnv::getEnvConfig('security.allow-outbound-http')) {
      throw new Exception('Outbound HTTP requests are disabled!');
    }

    $uri = new PhutilURI($uri);

    $protocol = $uri->getProtocol();
    switch ($protocol) {
      case 'http':
      case 'https':
        break;
      default:
        // Make sure we are not accessing any file:// URIs or similar.
        return null;
    }

    $timeout = 5;

    list($file_data) = id(new HTTPSFuture($uri))
        ->setTimeout($timeout)
        ->resolvex();

    $params = $params + array(
      'name' => basename($uri),
    );

    return self::newFromFileData($file_data, $params);
  }

  public static function normalizeFileName($file_name) {
    $pattern = "@[\\x00-\\x19#%&+!~'\$\"\/=\\\\?<> ]+@";
    $file_name = preg_replace($pattern, '_', $file_name);
    $file_name = preg_replace('@_+@', '_', $file_name);
    $file_name = trim($file_name, '_');

    $disallowed_filenames = array(
      '.'  => 'dot',
      '..' => 'dotdot',
      ''   => 'file',
    );
    $file_name = idx($disallowed_filenames, $file_name, $file_name);

    return $file_name;
  }

  public function delete() {
    // We want to delete all the rows which mark this file as the transformation
    // of some other file (since we're getting rid of it). We also delete all
    // the transformations of this file, so that a user who deletes an image
    // doesn't need to separately hunt down and delete a bunch of thumbnails and
    // resizes of it.

    $outbound_xforms = id(new PhabricatorFileQuery())
      ->setViewer(PhabricatorUser::getOmnipotentUser())
      ->withTransforms(
        array(
          array(
            'originalPHID' => $this->getPHID(),
            'transform'    => true,
          ),
        ))
      ->execute();

    foreach ($outbound_xforms as $outbound_xform) {
      $outbound_xform->delete();
    }

    $inbound_xforms = id(new PhabricatorTransformedFile())->loadAllWhere(
      'transformedPHID = %s',
      $this->getPHID());

    $this->openTransaction();
      foreach ($inbound_xforms as $inbound_xform) {
        $inbound_xform->delete();
      }
      $ret = parent::delete();
    $this->saveTransaction();

    $this->deleteFileDataIfUnused(
      $this->instantiateStorageEngine(),
      $this->getStorageEngine(),
      $this->getStorageHandle());

    return $ret;
  }


  /**
   * Destroy stored file data if there are no remaining files which reference
   * it.
   */
  public function deleteFileDataIfUnused(
    PhabricatorFileStorageEngine $engine,
    $engine_identifier,
    $handle) {

    // Check to see if any files are using storage.
    $usage = id(new PhabricatorFile())->loadAllWhere(
      'storageEngine = %s AND storageHandle = %s LIMIT 1',
      $engine_identifier,
      $handle);

    // If there are no files using the storage, destroy the actual storage.
    if (!$usage) {
      try {
        $engine->deleteFile($handle);
      } catch (Exception $ex) {
        // In the worst case, we're leaving some data stranded in a storage
        // engine, which is not a big deal.
        phlog($ex);
      }
    }
  }


  public static function hashFileContent($data) {
    return sha1($data);
  }

  public function loadFileData() {

    $engine = $this->instantiateStorageEngine();
    $data = $engine->readFile($this->getStorageHandle());

    switch ($this->getStorageFormat()) {
      case self::STORAGE_FORMAT_RAW:
        $data = $data;
        break;
      default:
        throw new Exception('Unknown storage format.');
    }

    return $data;
  }

  public function getViewURI() {
    if (!$this->getPHID()) {
      throw new Exception(
        'You must save a file before you can generate a view URI.');
    }

    $name = phutil_escape_uri($this->getName());

    $path = '/file/data/'.$this->getSecretKey().'/'.$this->getPHID().'/'.$name;
    return PhabricatorEnv::getCDNURI($path);
  }

  public function getInfoURI() {
    return '/'.$this->getMonogram();
  }

  public function getBestURI() {
    if ($this->isViewableInBrowser()) {
      return $this->getViewURI();
    } else {
      return $this->getInfoURI();
    }
  }

  public function getDownloadURI() {
    $uri = id(new PhutilURI($this->getViewURI()))
      ->setQueryParam('download', true);
    return (string) $uri;
  }

  public function getProfileThumbURI() {
    $path = '/file/xform/thumb-profile/'.$this->getPHID().'/'
      .$this->getSecretKey().'/';
    return PhabricatorEnv::getCDNURI($path);
  }

  public function getThumb60x45URI() {
    $path = '/file/xform/thumb-60x45/'.$this->getPHID().'/'
      .$this->getSecretKey().'/';
    return PhabricatorEnv::getCDNURI($path);
  }

  public function getThumb160x120URI() {
    $path = '/file/xform/thumb-160x120/'.$this->getPHID().'/'
      .$this->getSecretKey().'/';
    return PhabricatorEnv::getCDNURI($path);
  }

  public function getPreview100URI() {
    $path = '/file/xform/preview-100/'.$this->getPHID().'/'
      .$this->getSecretKey().'/';
    return PhabricatorEnv::getCDNURI($path);
  }

  public function getPreview220URI() {
    $path = '/file/xform/preview-220/'.$this->getPHID().'/'
      .$this->getSecretKey().'/';
    return PhabricatorEnv::getCDNURI($path);
  }

  public function getThumb220x165URI() {
    $path = '/file/xform/thumb-220x165/'.$this->getPHID().'/'
      .$this->getSecretKey().'/';
    return PhabricatorEnv::getCDNURI($path);
  }

  public function getThumb280x210URI() {
    $path = '/file/xform/thumb-280x210/'.$this->getPHID().'/'
      .$this->getSecretKey().'/';
    return PhabricatorEnv::getCDNURI($path);
  }

  public function isViewableInBrowser() {
    return ($this->getViewableMimeType() !== null);
  }

  public function isViewableImage() {
    if (!$this->isViewableInBrowser()) {
      return false;
    }

    $mime_map = PhabricatorEnv::getEnvConfig('files.image-mime-types');
    $mime_type = $this->getMimeType();
    return idx($mime_map, $mime_type);
  }

  public function isAudio() {
    if (!$this->isViewableInBrowser()) {
      return false;
    }

    $mime_map = PhabricatorEnv::getEnvConfig('files.audio-mime-types');
    $mime_type = $this->getMimeType();
    return idx($mime_map, $mime_type);
  }

  public function isTransformableImage() {
    // NOTE: The way the 'gd' extension works in PHP is that you can install it
    // with support for only some file types, so it might be able to handle
    // PNG but not JPEG. Try to generate thumbnails for whatever we can. Setup
    // warns you if you don't have complete support.

    $matches = null;
    $ok = preg_match(
      '@^image/(gif|png|jpe?g)@',
      $this->getViewableMimeType(),
      $matches);
    if (!$ok) {
      return false;
    }

    switch ($matches[1]) {
      case 'jpg';
      case 'jpeg':
        return function_exists('imagejpeg');
        break;
      case 'png':
        return function_exists('imagepng');
        break;
      case 'gif':
        return function_exists('imagegif');
        break;
      default:
        throw new Exception('Unknown type matched as image MIME type.');
    }
  }

  public static function getTransformableImageFormats() {
    $supported = array();

    if (function_exists('imagejpeg')) {
      $supported[] = 'jpg';
    }

    if (function_exists('imagepng')) {
      $supported[] = 'png';
    }

    if (function_exists('imagegif')) {
      $supported[] = 'gif';
    }

    return $supported;
  }

  public function instantiateStorageEngine() {
    return self::buildEngine($this->getStorageEngine());
  }

  public static function buildEngine($engine_identifier) {
    $engines = self::buildAllEngines();
    foreach ($engines as $engine) {
      if ($engine->getEngineIdentifier() == $engine_identifier) {
        return $engine;
      }
    }

    throw new Exception(
      "Storage engine '{$engine_identifier}' could not be located!");
  }

  public static function buildAllEngines() {
    $engines = id(new PhutilSymbolLoader())
      ->setType('class')
      ->setConcreteOnly(true)
      ->setAncestorClass('PhabricatorFileStorageEngine')
      ->selectAndLoadSymbols();

    $results = array();
    foreach ($engines as $engine_class) {
      $results[] = newv($engine_class['name'], array());
    }

    return $results;
  }

  public function getViewableMimeType() {
    $mime_map = PhabricatorEnv::getEnvConfig('files.viewable-mime-types');

    $mime_type = $this->getMimeType();
    $mime_parts = explode(';', $mime_type);
    $mime_type = trim(reset($mime_parts));

    return idx($mime_map, $mime_type);
  }

  public function getDisplayIconForMimeType() {
    $mime_map = PhabricatorEnv::getEnvConfig('files.icon-mime-types');
    $mime_type = $this->getMimeType();
    return idx($mime_map, $mime_type, 'docs_file');
  }

  public function validateSecretKey($key) {
    return ($key == $this->getSecretKey());
  }

  public function generateSecretKey() {
    return Filesystem::readRandomCharacters(20);
  }

  public function updateDimensions($save = true) {
    if (!$this->isViewableImage()) {
      throw new Exception(
        'This file is not a viewable image.');
    }

    if (!function_exists('imagecreatefromstring')) {
      throw new Exception(
        'Cannot retrieve image information.');
    }

    $data = $this->loadFileData();

    $img = imagecreatefromstring($data);
    if ($img === false) {
      throw new Exception(
        'Error when decoding image.');
    }

    $this->metadata[self::METADATA_IMAGE_WIDTH] = imagesx($img);
    $this->metadata[self::METADATA_IMAGE_HEIGHT] = imagesy($img);

    if ($save) {
      $this->save();
    }

    return $this;
  }

  public function copyDimensions(PhabricatorFile $file) {
    $metadata = $file->getMetadata();
    $width = idx($metadata, self::METADATA_IMAGE_WIDTH);
    if ($width) {
      $this->metadata[self::METADATA_IMAGE_WIDTH] = $width;
    }
    $height = idx($metadata, self::METADATA_IMAGE_HEIGHT);
    if ($height) {
      $this->metadata[self::METADATA_IMAGE_HEIGHT] = $height;
    }

    return $this;
  }


  /**
   * Load (or build) the {@class:PhabricatorFile} objects for builtin file
   * resources. The builtin mechanism allows files shipped with Phabricator
   * to be treated like normal files so that APIs do not need to special case
   * things like default images or deleted files.
   *
   * Builtins are located in `resources/builtin/` and identified by their
   * name.
   *
   * @param  PhabricatorUser                Viewing user.
   * @param  list<string>                   List of builtin file names.
   * @return dict<string, PhabricatorFile>  Dictionary of named builtins.
   */
  public static function loadBuiltins(PhabricatorUser $user, array $names) {
    $specs = array();
    foreach ($names as $name) {
      $specs[] = array(
        'originalPHID' => PhabricatorPHIDConstants::PHID_VOID,
        'transform'    => 'builtin:'.$name,
      );
    }

    // NOTE: Anyone is allowed to access builtin files.

    $files = id(new PhabricatorFileQuery())
      ->setViewer(PhabricatorUser::getOmnipotentUser())
      ->withTransforms($specs)
      ->execute();

    $files = mpull($files, null, 'getName');

    $root = dirname(phutil_get_library_root('phabricator'));
    $root = $root.'/resources/builtin/';

    $build = array();
    foreach ($names as $name) {
      if (isset($files[$name])) {
        continue;
      }

      // This is just a sanity check to prevent loading arbitrary files.
      if (basename($name) != $name) {
        throw new Exception("Invalid builtin name '{$name}'!");
      }

      $path = $root.$name;

      if (!Filesystem::pathExists($path)) {
        throw new Exception("Builtin '{$path}' does not exist!");
      }

      $data = Filesystem::readFile($path);
      $params = array(
        'name' => $name,
        'ttl'  => time() + (60 * 60 * 24 * 7),
      );

      $unguarded = AphrontWriteGuard::beginScopedUnguardedWrites();
        $file = PhabricatorFile::newFromFileData($data, $params);
        $xform = id(new PhabricatorTransformedFile())
          ->setOriginalPHID(PhabricatorPHIDConstants::PHID_VOID)
          ->setTransform('builtin:'.$name)
          ->setTransformedPHID($file->getPHID())
          ->save();
      unset($unguarded);

      $file->attachObjectPHIDs(array());
      $file->attachObjects(array());

      $files[$name] = $file;
    }

    return $files;
  }


  /**
   * Convenience wrapper for @{method:loadBuiltins}.
   *
   * @param PhabricatorUser   Viewing user.
   * @param string            Single builtin name to load.
   * @return PhabricatorFile  Corresponding builtin file.
   */
  public static function loadBuiltin(PhabricatorUser $user, $name) {
    return idx(self::loadBuiltins($user, array($name)), $name);
  }

  public function getObjects() {
    return $this->assertAttached($this->objects);
  }

  public function attachObjects(array $objects) {
    $this->objects = $objects;
    return $this;
  }

  public function getObjectPHIDs() {
    return $this->assertAttached($this->objectPHIDs);
  }

  public function attachObjectPHIDs(array $object_phids) {
    $this->objectPHIDs = $object_phids;
    return $this;
  }

  public function getOriginalFile() {
    return $this->assertAttached($this->originalFile);
  }

  public function attachOriginalFile(PhabricatorFile $file = null) {
    $this->originalFile = $file;
    return $this;
  }

  public function getImageHeight() {
    if (!$this->isViewableImage()) {
      return null;
    }
    return idx($this->metadata, self::METADATA_IMAGE_HEIGHT);
  }

  public function getImageWidth() {
    if (!$this->isViewableImage()) {
      return null;
    }
    return idx($this->metadata, self::METADATA_IMAGE_WIDTH);
  }

  public function getCanCDN() {
    if (!$this->isViewableImage()) {
      return false;
    }

    return idx($this->metadata, self::METADATA_CAN_CDN);
  }

  public function setCanCDN($can_cdn) {
    $this->metadata[self::METADATA_CAN_CDN] = $can_cdn ? 1 : 0;
    return $this;
  }

  protected function generateOneTimeToken() {
    $key = Filesystem::readRandomCharacters(16);

    // Save the new secret.
    $unguarded = AphrontWriteGuard::beginScopedUnguardedWrites();
      $token = id(new PhabricatorAuthTemporaryToken())
        ->setObjectPHID($this->getPHID())
        ->setTokenType(self::ONETIME_TEMPORARY_TOKEN_TYPE)
        ->setTokenExpires(time() + phutil_units('1 hour in seconds'))
        ->setTokenCode(PhabricatorHash::digest($key))
        ->save();
    unset($unguarded);

    return $key;
  }

  public function validateOneTimeToken($token_code) {
    $token = id(new PhabricatorAuthTemporaryTokenQuery())
      ->setViewer(PhabricatorUser::getOmnipotentUser())
      ->withObjectPHIDs(array($this->getPHID()))
      ->withTokenTypes(array(self::ONETIME_TEMPORARY_TOKEN_TYPE))
      ->withExpired(false)
      ->withTokenCodes(array(PhabricatorHash::digest($token_code)))
      ->executeOne();

    return $token;
  }

  /** Get the CDN uri for this file
   * This will generate a one-time-use token if
   * security.alternate_file_domain is set in the config.
   */
  public function getCDNURIWithToken() {
    if (!$this->getPHID()) {
      throw new Exception(
        'You must save a file before you can generate a CDN URI.');
    }
    $name = phutil_escape_uri($this->getName());

    $path = '/file/data'
          .'/'.$this->getSecretKey()
          .'/'.$this->getPHID()
          .'/'.$this->generateOneTimeToken()
          .'/'.$name;
    return PhabricatorEnv::getCDNURI($path);
  }



  /**
   * Write the policy edge between this file and some object.
   *
   * @param phid Object PHID to attach to.
   * @return this
   */
  public function attachToObject($phid) {
    $edge_type = PhabricatorEdgeConfig::TYPE_OBJECT_HAS_FILE;

    id(new PhabricatorEdgeEditor())
      ->addEdge($phid, $edge_type, $this->getPHID())
      ->save();

    return $this;
  }


  /**
   * Remove the policy edge between this file and some object.
   *
   * @param phid Object PHID to detach from.
   * @return this
   */
  public function detachFromObject($phid) {
    $edge_type = PhabricatorEdgeConfig::TYPE_OBJECT_HAS_FILE;

    id(new PhabricatorEdgeEditor())
      ->removeEdge($phid, $edge_type, $this->getPHID())
      ->save();

    return $this;
  }


  /**
   * Configure a newly created file object according to specified parameters.
   *
   * This method is called both when creating a file from fresh data, and
   * when creating a new file which reuses existing storage.
   *
   * @param map<string, wild>   Bag of parameters, see @{class:PhabricatorFile}
   *  for documentation.
   * @return this
   */
  private function readPropertiesFromParameters(array $params) {
    $file_name = idx($params, 'name');
    $file_name = self::normalizeFileName($file_name);
    $this->setName($file_name);

    $author_phid = idx($params, 'authorPHID');
    $this->setAuthorPHID($author_phid);

    $file_ttl = idx($params, 'ttl');
    $this->setTtl($file_ttl);

    $view_policy = idx($params, 'viewPolicy');
    if ($view_policy) {
      $this->setViewPolicy($params['viewPolicy']);
    }

    $is_explicit = (idx($params, 'isExplicitUpload') ? 1 : 0);
    $this->setIsExplicitUpload($is_explicit);

    $can_cdn = idx($params, 'canCDN');
    if ($can_cdn) {
      $this->setCanCDN(true);
    }

    $mime_type = idx($params, 'mime-type');
    if ($mime_type) {
      $this->setMimeType($mime_type);
    }

    return $this;
  }

  public function getRedirectResponse() {
    $uri = $this->getBestURI();

    // TODO: This is a bit iffy. Sometimes, getBestURI() returns a CDN URI
    // (if the file is a viewable image) and sometimes a local URI (if not).
    // For now, just detect which one we got and configure the response
    // appropriately. In the long run, if this endpoint is served from a CDN
    // domain, we can't issue a local redirect to an info URI (which is not
    // present on the CDN domain). We probably never actually issue local
    // redirects here anyway, since we only ever transform viewable images
    // right now.

    $is_external = strlen(id(new PhutilURI($uri))->getDomain());

    return id(new AphrontRedirectResponse())
      ->setIsExternal($is_external)
      ->setURI($uri);
  }


/* -(  PhabricatorPolicyInterface Implementation  )-------------------------- */


  public function getCapabilities() {
    return array(
      PhabricatorPolicyCapability::CAN_VIEW,
      PhabricatorPolicyCapability::CAN_EDIT,
    );
  }

  public function getPolicy($capability) {
    switch ($capability) {
      case PhabricatorPolicyCapability::CAN_VIEW:
        return $this->getViewPolicy();
      case PhabricatorPolicyCapability::CAN_EDIT:
        return PhabricatorPolicies::POLICY_NOONE;
    }
  }

  public function hasAutomaticCapability($capability, PhabricatorUser $viewer) {
    $viewer_phid = $viewer->getPHID();
    if ($viewer_phid) {
      if ($this->getAuthorPHID() == $viewer_phid) {
        return true;
      }
    }

    switch ($capability) {
      case PhabricatorPolicyCapability::CAN_VIEW:
        // If you can see the file this file is a transform of, you can see
        // this file.
        if ($this->getOriginalFile()) {
          return true;
        }

        // If you can see any object this file is attached to, you can see
        // the file.
        return (count($this->getObjects()) > 0);
    }

    return false;
  }

  public function describeAutomaticCapability($capability) {
    $out = array();
    $out[] = pht('The user who uploaded a file can always view and edit it.');
    switch ($capability) {
      case PhabricatorPolicyCapability::CAN_VIEW:
        $out[] = pht(
          'Files attached to objects are visible to users who can view '.
          'those objects.');
        $out[] = pht(
          'Thumbnails are visible only to users who can view the original '.
          'file.');
        break;
    }

    return $out;
  }


/* -(  PhabricatorSubscribableInterface Implementation  )-------------------- */


  public function isAutomaticallySubscribed($phid) {
    return ($this->authorPHID == $phid);
  }

  public function shouldShowSubscribersProperty() {
    return true;
  }

  public function shouldAllowSubscription($phid) {
    return true;
  }


/* -(  PhabricatorTokenReceiverInterface  )---------------------------------- */


  public function getUsersToNotifyOfTokenGiven() {
    return array(
      $this->getAuthorPHID(),
    );
  }


/* -(  PhabricatorDestructibleInterface  )----------------------------------- */


  public function destroyObjectPermanently(
    PhabricatorDestructionEngine $engine) {

    $this->openTransaction();
      $this->delete();
    $this->saveTransaction();
  }

}
