<?php

final class PhabricatorApplicationDatasource
  extends PhabricatorTypeaheadDatasource {

  public function getPlaceholderText() {
    return pht('Type an application name...');
  }

  public function getDatasourceApplicationClass() {
    return 'PhabricatorApplicationsApplication';
  }

  public function loadResults() {
    $viewer = $this->getViewer();
    $raw_query = $this->getRawQuery();

    $results = array();

    $applications = PhabricatorApplication::getAllInstalledApplications();
    foreach ($applications as $application) {
      $uri = $application->getTypeaheadURI();
      if (!$uri) {
        continue;
      }
      $name = $application->getName().' '.$application->getShortDescription();
      $img = 'apps-'.$application->getIconName().'-dark-large';
      $results[] = id(new PhabricatorTypeaheadResult())
        ->setName($name)
        ->setURI($uri)
        ->setPHID($application->getPHID())
        ->setPriorityString($application->getName())
        ->setDisplayName($application->getName())
        ->setDisplayType($application->getShortDescription())
        ->setImageuRI($application->getIconURI())
        ->setPriorityType('apps')
        ->setImageSprite('phabricator-search-icon sprite-apps-large '.$img);
    }

    return $results;
  }

}
