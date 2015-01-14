<?php

final class PhabricatorProjectQuery
  extends PhabricatorCursorPagedPolicyAwareQuery {

  private $ids;
  private $phids;
  private $memberPHIDs;
  private $slugs;
  private $phrictionSlugs;
  private $names;
  private $datasourceQuery;
  private $icons;
  private $colors;

  private $status       = 'status-any';
  const STATUS_ANY      = 'status-any';
  const STATUS_OPEN     = 'status-open';
  const STATUS_CLOSED   = 'status-closed';
  const STATUS_ACTIVE   = 'status-active';
  const STATUS_ARCHIVED = 'status-archived';

  private $needSlugs;
  private $needMembers;
  private $needWatchers;
  private $needImages;

  public function withIDs(array $ids) {
    $this->ids = $ids;
    return $this;
  }

  public function withPHIDs(array $phids) {
    $this->phids = $phids;
    return $this;
  }

  public function withStatus($status) {
    $this->status = $status;
    return $this;
  }

  public function withMemberPHIDs(array $member_phids) {
    $this->memberPHIDs = $member_phids;
    return $this;
  }

  public function withSlugs(array $slugs) {
    $this->slugs = $slugs;
    return $this;
  }

  public function withPhrictionSlugs(array $slugs) {
    $this->phrictionSlugs = $slugs;
    return $this;
  }

  public function withNames(array $names) {
    $this->names = $names;
    return $this;
  }

  public function withDatasourceQuery($string) {
    $this->datasourceQuery = $string;
    return $this;
  }

  public function withIcons(array $icons) {
    $this->icons = $icons;
    return $this;
  }

  public function withColors(array $colors) {
    $this->colors = $colors;
    return $this;
  }

  public function needMembers($need_members) {
    $this->needMembers = $need_members;
    return $this;
  }

  public function needWatchers($need_watchers) {
    $this->needWatchers = $need_watchers;
    return $this;
  }

  public function needImages($need_images) {
    $this->needImages = $need_images;
    return $this;
  }

  public function needSlugs($need_slugs) {
    $this->needSlugs = $need_slugs;
    return $this;
  }

  protected function getPagingColumn() {
    return 'name';
  }

  protected function getPagingValue($result) {
    return $result->getName();
  }

  protected function getReversePaging() {
    return true;
  }

  protected function loadPage() {
    $table = new PhabricatorProject();
    $conn_r = $table->establishConnection('r');

    // NOTE: Because visibility checks for projects depend on whether or not
    // the user is a project member, we always load their membership. If we're
    // loading all members anyway we can piggyback on that; otherwise we
    // do an explicit join.

    $select_clause = '';
    if (!$this->needMembers) {
      $select_clause = ', vm.dst viewerIsMember';
    }

    $data = queryfx_all(
      $conn_r,
      'SELECT p.* %Q FROM %T p %Q %Q %Q %Q %Q',
      $select_clause,
      $table->getTableName(),
      $this->buildJoinClause($conn_r),
      $this->buildWhereClause($conn_r),
      $this->buildGroupClause($conn_r),
      $this->buildOrderClause($conn_r),
      $this->buildLimitClause($conn_r));

    $projects = $table->loadAllFromArray($data);

    if ($projects) {
      $viewer_phid = $this->getViewer()->getPHID();
      $project_phids = mpull($projects, 'getPHID');

      $member_type = PhabricatorEdgeConfig::TYPE_PROJ_MEMBER;
      $watcher_type = PhabricatorEdgeConfig::TYPE_OBJECT_HAS_WATCHER;

      $need_edge_types = array();
      if ($this->needMembers) {
        $need_edge_types[] = $member_type;
      } else {
        foreach ($data as $row) {
          $projects[$row['id']]->setIsUserMember(
            $viewer_phid,
            ($row['viewerIsMember'] !== null));
        }
      }

      if ($this->needWatchers) {
        $need_edge_types[] = $watcher_type;
      }

      if ($need_edge_types) {
        $edges = id(new PhabricatorEdgeQuery())
          ->withSourcePHIDs($project_phids)
          ->withEdgeTypes($need_edge_types)
          ->execute();

        if ($this->needMembers) {
          foreach ($projects as $project) {
            $phid = $project->getPHID();
            $project->attachMemberPHIDs(
              array_keys($edges[$phid][$member_type]));
            $project->setIsUserMember(
              $viewer_phid,
              isset($edges[$phid][$member_type][$viewer_phid]));
          }
        }

        if ($this->needWatchers) {
          foreach ($projects as $project) {
            $phid = $project->getPHID();
            $project->attachWatcherPHIDs(
              array_keys($edges[$phid][$watcher_type]));
            $project->setIsUserWatcher(
              $viewer_phid,
              isset($edges[$phid][$watcher_type][$viewer_phid]));
          }
        }
      }
    }

    return $projects;
  }

  protected function didFilterPage(array $projects) {
    if ($this->needImages) {
      $default = null;

      $file_phids = mpull($projects, 'getProfileImagePHID');
      $files = id(new PhabricatorFileQuery())
        ->setParentQuery($this)
        ->setViewer($this->getViewer())
        ->withPHIDs($file_phids)
        ->execute();
      $files = mpull($files, null, 'getPHID');
      foreach ($projects as $project) {
        $file = idx($files, $project->getProfileImagePHID());
        if (!$file) {
          if (!$default) {
            $default = PhabricatorFile::loadBuiltin(
              $this->getViewer(),
              'project.png');
          }
          $file = $default;
        }
        $project->attachProfileImageFile($file);
      }
    }

    if ($this->needSlugs) {
      $slugs = id(new PhabricatorProjectSlug())
        ->loadAllWhere(
          'projectPHID IN (%Ls)',
          mpull($projects, 'getPHID'));
      $slugs = mgroup($slugs, 'getProjectPHID');
      foreach ($projects as $project) {
        $project_slugs = idx($slugs, $project->getPHID(), array());
        $project->attachSlugs($project_slugs);
      }
    }

    return $projects;
  }

  private function buildWhereClause($conn_r) {
    $where = array();

    if ($this->status != self::STATUS_ANY) {
      switch ($this->status) {
        case self::STATUS_OPEN:
        case self::STATUS_ACTIVE:
          $filter = array(
            PhabricatorProjectStatus::STATUS_ACTIVE,
          );
          break;
        case self::STATUS_CLOSED:
        case self::STATUS_ARCHIVED:
          $filter = array(
            PhabricatorProjectStatus::STATUS_ARCHIVED,
          );
          break;
        default:
          throw new Exception(
            "Unknown project status '{$this->status}'!");
      }
      $where[] = qsprintf(
        $conn_r,
        'status IN (%Ld)',
        $filter);
    }

    if ($this->ids !== null) {
      $where[] = qsprintf(
        $conn_r,
        'id IN (%Ld)',
        $this->ids);
    }

    if ($this->phids !== null) {
      $where[] = qsprintf(
        $conn_r,
        'phid IN (%Ls)',
        $this->phids);
    }

    if ($this->memberPHIDs !== null) {
      $where[] = qsprintf(
        $conn_r,
        'e.dst IN (%Ls)',
        $this->memberPHIDs);
    }

    if ($this->slugs !== null) {
      $slugs = array();
      foreach ($this->slugs as $slug) {
        $slugs[] = rtrim(PhabricatorSlug::normalize($slug), '/');
      }

      $where[] = qsprintf(
        $conn_r,
        'slug.slug IN (%Ls)',
        $slugs);
    }

    if ($this->phrictionSlugs !== null) {
      $where[] = qsprintf(
        $conn_r,
        'phrictionSlug IN (%Ls)',
        $this->phrictionSlugs);
    }

    if ($this->names !== null) {
      $where[] = qsprintf(
        $conn_r,
        'name IN (%Ls)',
        $this->names);
    }

    if ($this->icons !== null) {
      $where[] = qsprintf(
        $conn_r,
        'icon IN (%Ls)',
        $this->icons);
    }

    if ($this->colors !== null) {
      $where[] = qsprintf(
        $conn_r,
        'color IN (%Ls)',
        $this->colors);
    }

    $where[] = $this->buildPagingClause($conn_r);

    return $this->formatWhereClause($where);
  }

  private function buildGroupClause($conn_r) {
    if ($this->memberPHIDs || $this->datasourceQuery) {
      return 'GROUP BY p.id';
    } else {
      return $this->buildApplicationSearchGroupClause($conn_r);
    }
  }

  private function buildJoinClause($conn_r) {
    $joins = array();

    if (!$this->needMembers !== null) {
      $joins[] = qsprintf(
        $conn_r,
        'LEFT JOIN %T vm ON vm.src = p.phid AND vm.type = %d AND vm.dst = %s',
        PhabricatorEdgeConfig::TABLE_NAME_EDGE,
        PhabricatorEdgeConfig::TYPE_PROJ_MEMBER,
        $this->getViewer()->getPHID());
    }

    if ($this->memberPHIDs !== null) {
      $joins[] = qsprintf(
        $conn_r,
        'JOIN %T e ON e.src = p.phid AND e.type = %d',
        PhabricatorEdgeConfig::TABLE_NAME_EDGE,
        PhabricatorEdgeConfig::TYPE_PROJ_MEMBER);
    }

    if ($this->slugs !== null) {
      $joins[] = qsprintf(
        $conn_r,
        'JOIN %T slug on slug.projectPHID = p.phid',
        id(new PhabricatorProjectSlug())->getTableName());
    }

    if ($this->datasourceQuery !== null) {
      $tokens = PhabricatorTypeaheadDatasource::tokenizeString(
        $this->datasourceQuery);
      if (!$tokens) {
        throw new PhabricatorEmptyQueryException();
      }

      $likes = array();
      foreach ($tokens as $token) {
        $likes[] = qsprintf($conn_r, 'token.token LIKE %>', $token);
      }

      $joins[] = qsprintf(
        $conn_r,
        'JOIN %T token ON token.projectID = p.id AND (%Q)',
        PhabricatorProject::TABLE_DATASOURCE_TOKEN,
        '('.implode(') OR (', $likes).')');
    }

    $joins[] = $this->buildApplicationSearchJoinClause($conn_r);

    return implode(' ', $joins);
  }

  public function getQueryApplicationClass() {
    return 'PhabricatorProjectApplication';
  }

  protected function getApplicationSearchObjectPHIDColumn() {
    return 'p.phid';
  }

}
