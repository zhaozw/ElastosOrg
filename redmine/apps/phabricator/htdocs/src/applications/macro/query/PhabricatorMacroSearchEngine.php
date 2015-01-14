<?php

final class PhabricatorMacroSearchEngine
  extends PhabricatorApplicationSearchEngine {

  public function getResultTypeDescription() {
    return pht('Macros');
  }

  public function getApplicationClassName() {
    return 'PhabricatorMacroApplication';
  }

  public function buildSavedQueryFromRequest(AphrontRequest $request) {
    $saved = new PhabricatorSavedQuery();
    $saved->setParameter(
      'authorPHIDs',
      $this->readUsersFromRequest($request, 'authors'));

    $saved->setParameter('status', $request->getStr('status'));
    $saved->setParameter('names', $request->getStrList('names'));
    $saved->setParameter('nameLike', $request->getStr('nameLike'));
    $saved->setParameter('createdStart', $request->getStr('createdStart'));
    $saved->setParameter('createdEnd', $request->getStr('createdEnd'));
    $saved->setParameter('flagColor', $request->getStr('flagColor', '-1'));

    return $saved;
  }

  public function buildQueryFromSavedQuery(PhabricatorSavedQuery $saved) {
    $query = id(new PhabricatorMacroQuery())
      ->needFiles(true)
      ->withIDs($saved->getParameter('ids', array()))
      ->withPHIDs($saved->getParameter('phids', array()))
      ->withAuthorPHIDs($saved->getParameter('authorPHIDs', array()));

    $status = $saved->getParameter('status');
    $options = PhabricatorMacroQuery::getStatusOptions();
    if (empty($options[$status])) {
      $status = head_key($options);
    }
    $query->withStatus($status);

    $names = $saved->getParameter('names', array());
    if ($names) {
      $query->withNames($names);
    }

    $like = $saved->getParameter('nameLike');
    if (strlen($like)) {
      $query->withNameLike($like);
    }

    $start = $this->parseDateTime($saved->getParameter('createdStart'));
    $end = $this->parseDateTime($saved->getParameter('createdEnd'));

    if ($start) {
      $query->withDateCreatedAfter($start);
    }

    if ($end) {
      $query->withDateCreatedBefore($end);
    }

    $color = $saved->getParameter('flagColor');
    if (strlen($color)) {
      $query->withFlagColor($color);
    }

    return $query;
  }

  public function buildSearchForm(
    AphrontFormView $form,
    PhabricatorSavedQuery $saved_query) {

    $phids = $saved_query->getParameter('authorPHIDs', array());
    $author_handles = id(new PhabricatorHandleQuery())
      ->setViewer($this->requireViewer())
      ->withPHIDs($phids)
      ->execute();

    $status = $saved_query->getParameter('status');
    $names = implode(', ', $saved_query->getParameter('names', array()));
    $like = $saved_query->getParameter('nameLike');
    $color = $saved_query->getParameter('flagColor', '-1');

    $form
      ->appendChild(
        id(new AphrontFormSelectControl())
          ->setName('status')
          ->setLabel(pht('Status'))
          ->setOptions(PhabricatorMacroQuery::getStatusOptions())
          ->setValue($status))
      ->appendChild(
        id(new AphrontFormTokenizerControl())
          ->setDatasource(new PhabricatorPeopleDatasource())
          ->setName('authors')
          ->setLabel(pht('Authors'))
          ->setValue($author_handles))
      ->appendChild(
        id(new AphrontFormTextControl())
          ->setName('nameLike')
          ->setLabel(pht('Name Contains'))
          ->setValue($like))
      ->appendChild(
        id(new AphrontFormTextControl())
          ->setName('names')
          ->setLabel(pht('Exact Names'))
          ->setValue($names))
      ->appendChild(
        id(new AphrontFormSelectControl())
          ->setName('flagColor')
          ->setLabel(pht('Marked with Flag'))
          ->setOptions(PhabricatorMacroQuery::getFlagColorsOptions())
          ->setValue($color));

    $this->buildDateRange(
      $form,
      $saved_query,
      'createdStart',
      pht('Created After'),
      'createdEnd',
      pht('Created Before'));
  }

  protected function getURI($path) {
    return '/macro/'.$path;
  }

  public function getBuiltinQueryNames() {
    $names = array(
      'active'  => pht('Active'),
      'all'     => pht('All'),
    );

    if ($this->requireViewer()->isLoggedIn()) {
      $names['authored'] = pht('Authored');
    }

    return $names;
  }

  public function buildSavedQueryFromBuiltin($query_key) {
    $query = $this->newSavedQuery();
    $query->setQueryKey($query_key);

    switch ($query_key) {
      case 'active':
        return $query;
      case 'all':
        return $query->setParameter(
          'status',
          PhabricatorMacroQuery::STATUS_ANY);
      case 'authored':
        return $query->setParameter(
          'authorPHIDs',
          array($this->requireViewer()->getPHID()));
    }

    return parent::buildSavedQueryFromBuiltin($query_key);
  }

  protected function getRequiredHandlePHIDsForResultList(
    array $macros,
    PhabricatorSavedQuery $query) {
    return mpull($macros, 'getAuthorPHID');
  }

  protected function renderResultList(
    array $macros,
    PhabricatorSavedQuery $query,
    array $handles) {

    assert_instances_of($macros, 'PhabricatorFileImageMacro');
    $viewer = $this->requireViewer();

    $pinboard = new PHUIPinboardView();
    foreach ($macros as $macro) {
      $file = $macro->getFile();

      $item = new PHUIPinboardItemView();
      if ($file) {
        $item->setImageURI($file->getThumb280x210URI());
        $item->setImageSize(280, 210);
      }

      if ($macro->getDateCreated()) {
        $datetime = phabricator_date($macro->getDateCreated(), $viewer);
        $item->appendChild(
          phutil_tag(
            'div',
            array(),
            pht('Created on %s', $datetime)));
      } else {
        // Very old macros don't have a creation date. Rendering something
        // keeps all the pins at the same height and avoids flow issues.
        $item->appendChild(
          phutil_tag(
            'div',
            array(),
            pht('Created in ages long past')));
      }

      if ($macro->getAuthorPHID()) {
        $author_handle = $handles[$macro->getAuthorPHID()];
        $item->appendChild(
          pht('Created by %s', $author_handle->renderLink()));
      }

      $item->setURI($this->getApplicationURI('/view/'.$macro->getID().'/'));
      $item->setDisabled($macro->getisDisabled());
      $item->setHeader($macro->getName());

      $pinboard->addItem($item);
    }

    return $pinboard;
  }

}
