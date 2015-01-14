<?php

final class PhabricatorDashboardPanelTypeQuery
  extends PhabricatorDashboardPanelType {

  public function getPanelTypeKey() {
    return 'query';
  }

  public function getPanelTypeName() {
    return pht('Query Panel');
  }

  public function getPanelTypeDescription() {
    return pht(
      'Show results of a search query, like the most recently filed tasks or '.
      'revisions you need to review.');
  }

  public function getFieldSpecifications() {
    return array(
      'class' => array(
        'name' => pht('Search For'),
        'type' => 'search.application',
      ),
      'key' => array(
        'name' => pht('Query'),
        'type' => 'search.query',
        'control.application' => 'class',
      ),
      'limit' => array(
        'name' => pht('Limit'),
        'caption' => pht('Leave this blank for the default number of items.'),
        'type' => 'text',
      ),
    );
  }

  public function initializeFieldsFromRequest(
    PhabricatorDashboardPanel $panel,
    PhabricatorCustomFieldList $field_list,
    AphrontRequest $request) {

    $map = array();
    if (strlen($request->getStr('engine'))) {
      $map['class'] = $request->getStr('engine');
    }

    if (strlen($request->getStr('query'))) {
      $map['key'] = $request->getStr('query');
    }

    $full_map = array();
    foreach ($map as $key => $value) {
      $full_map["std:dashboard:core:{$key}"] = $value;
    }

    foreach ($field_list->getFields() as $field) {
      $field_key = $field->getFieldKey();
      if (isset($full_map[$field_key])) {
        $field->setValueFromStorage($full_map[$field_key]);
      }
    }
  }


  public function renderPanelContent(
    PhabricatorUser $viewer,
    PhabricatorDashboardPanel $panel,
    PhabricatorDashboardPanelRenderingEngine $engine) {

    $class = $panel->getProperty('class');

    $engine = PhabricatorApplicationSearchEngine::getEngineByClassName($class);
    if (!$engine) {
      throw new Exception(
        pht(
          'The application search engine "%s" is not known to Phabricator!',
          $class));
    }

    $engine->setViewer($viewer);
    $engine->setContext(PhabricatorApplicationSearchEngine::CONTEXT_PANEL);

    $key = $panel->getProperty('key');
    if ($engine->isBuiltinQuery($key)) {
      $saved = $engine->buildSavedQueryFromBuiltin($key);
    } else {
      $saved = id(new PhabricatorSavedQueryQuery())
        ->setViewer($viewer)
        ->withEngineClassNames(array($class))
        ->withQueryKeys(array($key))
        ->executeOne();
    }

    if (!$saved) {
      throw new Exception(
        pht(
          'Query "%s" is unknown to application search engine "%s"!',
          $key,
          $class));
    }

    $query = $engine->buildQueryFromSavedQuery($saved);
    $pager = $engine->newPagerForSavedQuery($saved);

    if ($panel->getProperty('limit')) {
      $limit = (int)$panel->getProperty('limit');
      if ($pager->getPageSize() !== 0xFFFF) {
        $pager->setPageSize($limit);
      }
    }

    $results = $engine->executeQuery($query, $pager);

    return $engine->renderResults($results, $saved);
  }

}
