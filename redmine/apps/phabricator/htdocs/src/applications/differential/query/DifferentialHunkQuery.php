<?php

final class DifferentialHunkQuery
  extends PhabricatorCursorPagedPolicyAwareQuery {

  private $changesets;
  private $shouldAttachToChangesets;

  public function withChangesets(array $changesets) {
    assert_instances_of($changesets, 'DifferentialChangeset');
    $this->changesets = $changesets;
    return $this;
  }

  public function needAttachToChangesets($attach) {
    $this->shouldAttachToChangesets = $attach;
    return $this;
  }

  public function willExecute() {
    // If we fail to load any hunks at all (for example, because all of
    // the requested changesets are directories or empty files and have no
    // hunks) we'll never call didFilterPage(), and thus never have an
    // opportunity to attach hunks. Attach empty hunk lists now so that we
    // end up with the right result.
    if ($this->shouldAttachToChangesets) {
      foreach ($this->changesets as $changeset) {
        $changeset->attachHunks(array());
      }
    }
  }

  public function loadPage() {
    $all_results = array();

    // Load modern hunks.
    $table = new DifferentialHunkModern();
    $conn_r = $table->establishConnection('r');

    $modern_data = queryfx_all(
      $conn_r,
      'SELECT * FROM %T %Q %Q %Q',
      $table->getTableName(),
      $this->buildWhereClause($conn_r),
      $this->buildOrderClause($conn_r),
      $this->buildLimitClause($conn_r));
    $modern_results = $table->loadAllFromArray($modern_data);


    // Now, load legacy hunks.
    $table = new DifferentialHunkLegacy();
    $conn_r = $table->establishConnection('r');

    $legacy_data = queryfx_all(
      $conn_r,
      'SELECT * FROM %T %Q %Q %Q',
      $table->getTableName(),
      $this->buildWhereClause($conn_r),
      $this->buildOrderClause($conn_r),
      $this->buildLimitClause($conn_r));
    $legacy_results = $table->loadAllFromArray($legacy_data);

    // Strip all the IDs off since they're not unique and nothing should be
    // using them.
    return array_values(array_merge($legacy_results, $modern_results));
  }

  public function willFilterPage(array $hunks) {
    $changesets = mpull($this->changesets, null, 'getID');
    foreach ($hunks as $key => $hunk) {
      $changeset = idx($changesets, $hunk->getChangesetID());
      if (!$changeset) {
        unset($hunks[$key]);
      }
      $hunk->attachChangeset($changeset);
    }

    return $hunks;
  }

  public function didFilterPage(array $hunks) {
    if ($this->shouldAttachToChangesets) {
      $hunk_groups = mgroup($hunks, 'getChangesetID');
      foreach ($this->changesets as $changeset) {
        $hunks = idx($hunk_groups, $changeset->getID(), array());
        $changeset->attachHunks($hunks);
      }
    }

    return $hunks;
  }

  private function buildWhereClause(AphrontDatabaseConnection $conn_r) {
    $where = array();

    if (!$this->changesets) {
      throw new Exception(
        pht('You must load hunks via changesets, with withChangesets()!'));
    }

    $where[] = qsprintf(
      $conn_r,
      'changesetID IN (%Ld)',
      mpull($this->changesets, 'getID'));

    $where[] = $this->buildPagingClause($conn_r);

    return $this->formatWhereClause($where);
  }

  public function getQueryApplicationClass() {
    return 'PhabricatorDifferentialApplication';
  }

  protected function getReversePaging() {
    return true;
  }

}
