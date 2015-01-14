<?php

final class PhabricatorXHPASTViewParseTree extends PhabricatorXHPASTViewDAO {

  protected $authorPHID;

  protected $input;
  protected $stdout;

  public function getConfiguration() {
    return array(
      self::CONFIG_COLUMN_SCHEMA => array(
        'authorPHID' => 'phid?',
        'input' => 'text',
        'stdout' => 'text',
      ),
    ) + parent::getConfiguration();
  }
}
