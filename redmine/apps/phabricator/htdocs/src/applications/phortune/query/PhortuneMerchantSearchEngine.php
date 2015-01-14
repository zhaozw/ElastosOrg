<?php

final class PhortuneMerchantSearchEngine
  extends PhabricatorApplicationSearchEngine {

  public function getResultTypeDescription() {
    return pht('Phortune Merchants');
  }

  public function buildSavedQueryFromRequest(AphrontRequest $request) {
    $saved = new PhabricatorSavedQuery();

    return $saved;
  }

  public function buildQueryFromSavedQuery(PhabricatorSavedQuery $saved) {
    $query = id(new PhortuneMerchantQuery());

    return $query;
  }

  public function buildSearchForm(
    AphrontFormView $form,
    PhabricatorSavedQuery $saved_query) {}

  protected function getURI($path) {
    return '/phortune/merchant/'.$path;
  }

  public function getBuiltinQueryNames() {
    $names = array(
      'all' => pht('All Merchants'),
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
    array $merchants,
    PhabricatorSavedQuery $query) {
    return array();
  }

  protected function renderResultList(
    array $merchants,
    PhabricatorSavedQuery $query,
    array $handles) {
    assert_instances_of($merchants, 'PhortuneMerchant');

    $viewer = $this->requireViewer();

    $list = new PHUIObjectItemListView();
    $list->setUser($viewer);
    foreach ($merchants as $merchant) {
      $item = id(new PHUIObjectItemView())
        ->setObjectName(pht('Merchant %d', $merchant->getID()))
        ->setHeader($merchant->getName())
        ->setHref('/phortune/merchant/'.$merchant->getID().'/')
        ->setObject($merchant);

      $list->addItem($item);
    }

    return $list;
  }
}
