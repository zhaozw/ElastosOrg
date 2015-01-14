<?php

final class AlmanacNetworkSearchEngine
  extends PhabricatorApplicationSearchEngine {

  public function getResultTypeDescription() {
    return pht('Almanac Networks');
  }

  protected function getApplicationClassName() {
    return 'PhabricatorAlmanacApplication';
  }

  public function buildSavedQueryFromRequest(AphrontRequest $request) {
    $saved = new PhabricatorSavedQuery();

    return $saved;
  }

  public function buildQueryFromSavedQuery(PhabricatorSavedQuery $saved) {
    $query = id(new AlmanacNetworkQuery());

    return $query;
  }

  public function buildSearchForm(
    AphrontFormView $form,
    PhabricatorSavedQuery $saved_query) {}

  protected function getURI($path) {
    return '/almanac/network/'.$path;
  }

  public function getBuiltinQueryNames() {
    $names = array(
      'all' => pht('All Networks'),
    );

    return $names;
  }

  public function buildSavedQueryFromBuiltin($query_key) {

    $query = $this->newSavedQuery();
    $query->setQueryKey($query_key);

    switch ($query_key) {
      case 'all':
        return $query;
    }

    return parent::buildSavedQueryFromBuiltin($query_key);
  }

  protected function getRequiredHandlePHIDsForResultList(
    array $networks,
    PhabricatorSavedQuery $query) {
    return array();
  }

  protected function renderResultList(
    array $networks,
    PhabricatorSavedQuery $query,
    array $handles) {
    assert_instances_of($networks, 'AlmanacNetwork');

    $viewer = $this->requireViewer();

    $list = new PHUIObjectItemListView();
    $list->setUser($viewer);
    foreach ($networks as $network) {
      $id = $network->getID();

      $item = id(new PHUIObjectItemView())
        ->setObjectName(pht('Network %d', $id))
        ->setHeader($network->getName())
        ->setHref($this->getApplicationURI("network/{$id}/"))
        ->setObject($network);

      $list->addItem($item);
    }

    return $list;
  }
}
