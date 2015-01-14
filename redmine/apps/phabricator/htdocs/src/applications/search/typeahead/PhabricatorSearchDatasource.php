<?php

final class PhabricatorSearchDatasource
  extends PhabricatorTypeaheadCompositeDatasource {

  public function getPlaceholderText() {
    return pht('Type an object name...');
  }

  public function getDatasourceApplicationClass() {
    return 'PhabricatorSearchApplication';
  }

  public function getComponentDatasources() {
    return array(
      id(new PhabricatorPeopleDatasource())->setEnrichResults(true),
      new PhabricatorProjectDatasource(),
      new PhabricatorApplicationDatasource(),
      new PhabricatorTypeaheadMonogramDatasource(),
      new DiffusionSymbolDatasource(),
    );
  }

}
