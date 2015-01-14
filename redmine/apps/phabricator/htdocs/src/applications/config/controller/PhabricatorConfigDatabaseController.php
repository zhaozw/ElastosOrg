<?php

abstract class PhabricatorConfigDatabaseController
  extends PhabricatorConfigController {

  protected function buildSchemaQuery() {
    $conf = PhabricatorEnv::newObjectFromConfig(
      'mysql.configuration-provider',
      array($dao = null, 'w'));

    $api = id(new PhabricatorStorageManagementAPI())
      ->setUser($conf->getUser())
      ->setHost($conf->getHost())
      ->setPort($conf->getPort())
      ->setNamespace(PhabricatorLiskDAO::getDefaultStorageNamespace())
      ->setPassword($conf->getPassword());

    $query = id(new PhabricatorConfigSchemaQuery())
      ->setAPI($api);

    return $query;
  }

  protected function renderIcon($status) {
    switch ($status) {
      case PhabricatorConfigStorageSchema::STATUS_OKAY:
        $icon = 'fa-check-circle green';
        break;
      case PhabricatorConfigStorageSchema::STATUS_WARN:
        $icon = 'fa-exclamation-circle yellow';
        break;
      case PhabricatorConfigStorageSchema::STATUS_FAIL:
      default:
        $icon = 'fa-times-circle red';
        break;
    }

    return id(new PHUIIconView())
      ->setIconFont($icon);
  }

  protected function renderAttr($attr, $issue) {
    if ($issue) {
      return phutil_tag(
        'span',
        array(
          'style' => 'color: #aa0000;',
        ),
        $attr);
    } else {
      return $attr;
    }
  }

  protected function renderBoolean($value) {
    if ($value === null) {
      return '';
    } else if ($value === true) {
      return pht('Yes');
    } else {
      return pht('No');
    }
  }

  protected function buildHeaderWithDocumentationLink($title) {

    $doc_link = PhabricatorEnv::getDoclink('Managing Storage Adjustments');

    return id(new PHUIHeaderView())
      ->setHeader($title)
      ->addActionLink(
        id(new PHUIButtonView())
          ->setTag('a')
          ->setIcon(
            id(new PHUIIconView())
              ->setIconFont('fa-book'))
          ->setHref($doc_link)
          ->setText(pht('Learn More')));
  }

}
