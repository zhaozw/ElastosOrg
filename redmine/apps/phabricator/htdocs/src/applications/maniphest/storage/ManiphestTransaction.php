<?php

final class ManiphestTransaction
  extends PhabricatorApplicationTransaction {

  const TYPE_TITLE = 'title';
  const TYPE_STATUS = 'status';
  const TYPE_DESCRIPTION = 'description';
  const TYPE_OWNER  = 'reassign';
  const TYPE_CCS = 'ccs';
  const TYPE_PROJECTS = 'projects';
  const TYPE_PRIORITY = 'priority';
  const TYPE_EDGE = 'edge';
  const TYPE_SUBPRIORITY = 'subpriority';
  const TYPE_PROJECT_COLUMN = 'projectcolumn';
  const TYPE_MERGED_INTO = 'mergedinto';
  const TYPE_MERGED_FROM = 'mergedfrom';

  const TYPE_UNBLOCK = 'unblock';

  // NOTE: this type is deprecated. Keep it around for legacy installs
  // so any transactions render correctly.
  const TYPE_ATTACH = 'attach';


  const MAILTAG_STATUS = 'maniphest-status';
  const MAILTAG_OWNER = 'maniphest-owner';
  const MAILTAG_PRIORITY = 'maniphest-priority';
  const MAILTAG_CC = 'maniphest-cc';
  const MAILTAG_PROJECTS = 'maniphest-projects';
  const MAILTAG_COMMENT = 'maniphest-comment';
  const MAILTAG_COLUMN = 'maniphest-column';
  const MAILTAG_UNBLOCK = 'maniphest-unblock';
  const MAILTAG_OTHER = 'maniphest-other';


  public function getApplicationName() {
    return 'maniphest';
  }

  public function getApplicationTransactionType() {
    return ManiphestTaskPHIDType::TYPECONST;
  }

  public function getApplicationTransactionCommentObject() {
    return new ManiphestTransactionComment();
  }

  public function shouldGenerateOldValue() {
    switch ($this->getTransactionType()) {
      case self::TYPE_PROJECT_COLUMN:
      case self::TYPE_EDGE:
      case self::TYPE_UNBLOCK:
        return false;
    }

    return parent::shouldGenerateOldValue();
  }

  public function getRemarkupBlocks() {
    $blocks = parent::getRemarkupBlocks();

    switch ($this->getTransactionType()) {
      case self::TYPE_DESCRIPTION:
        $blocks[] = $this->getNewValue();
        break;
    }

    return $blocks;
  }

  public function getRequiredHandlePHIDs() {
    $phids = parent::getRequiredHandlePHIDs();

    $new = $this->getNewValue();
    $old = $this->getOldValue();

    switch ($this->getTransactionType()) {
      case self::TYPE_OWNER:
        if ($new) {
          $phids[] = $new;
        }

        if ($old) {
          $phids[] = $old;
        }
        break;
      case self::TYPE_CCS:
      case self::TYPE_PROJECTS:
        $phids = array_mergev(
          array(
            $phids,
            nonempty($old, array()),
            nonempty($new, array()),
          ));
        break;
      case self::TYPE_PROJECT_COLUMN:
        $phids[] = $new['projectPHID'];
        $phids[] = head($new['columnPHIDs']);
        break;
      case self::TYPE_MERGED_INTO:
        $phids[] = $new;
        break;
      case self::TYPE_MERGED_FROM:
        $phids = array_merge($phids, $new);
        break;
      case self::TYPE_EDGE:
        $phids = array_mergev(
          array(
            $phids,
            array_keys(nonempty($old, array())),
            array_keys(nonempty($new, array())),
          ));
        break;
      case self::TYPE_ATTACH:
        $old = nonempty($old, array());
        $new = nonempty($new, array());
        $phids = array_mergev(
          array(
            $phids,
            array_keys(idx($new, 'FILE', array())),
            array_keys(idx($old, 'FILE', array())),
          ));
        break;
      case self::TYPE_UNBLOCK:
        foreach (array_keys($new) as $phid) {
          $phids[] = $phid;
        }
        break;
    }

    return $phids;
  }

  public function shouldHide() {
    switch ($this->getTransactionType()) {
      case self::TYPE_DESCRIPTION:
      case self::TYPE_PRIORITY:
      case self::TYPE_STATUS:
        if ($this->getOldValue() === null) {
          return true;
        } else {
          return false;
        }
        break;
      case self::TYPE_SUBPRIORITY:
        return true;
      case self::TYPE_PROJECT_COLUMN:
        $old_cols = idx($this->getOldValue(), 'columnPHIDs');
        $new_cols = idx($this->getNewValue(), 'columnPHIDs');

        $old_cols = array_values($old_cols);
        $new_cols = array_values($new_cols);
        sort($old_cols);
        sort($new_cols);

        return ($old_cols === $new_cols);
    }

    return parent::shouldHide();
  }

  public function getActionStrength() {
    switch ($this->getTransactionType()) {
      case self::TYPE_TITLE:
        return 1.4;
      case self::TYPE_STATUS:
        return 1.3;
      case self::TYPE_OWNER:
        return 1.2;
      case self::TYPE_PRIORITY:
        return 1.1;
    }

    return parent::getActionStrength();
  }


  public function getColor() {
    $old = $this->getOldValue();
    $new = $this->getNewValue();

    switch ($this->getTransactionType()) {
      case self::TYPE_OWNER:
        if ($this->getAuthorPHID() == $new) {
          return 'green';
        } else if (!$new) {
          return 'black';
        } else if (!$old) {
          return 'green';
        } else {
          return 'green';
        }

      case self::TYPE_STATUS:
        $color = ManiphestTaskStatus::getStatusColor($new);
        if ($color !== null) {
          return $color;
        }

        if (ManiphestTaskStatus::isOpenStatus($new)) {
          return 'green';
        } else {
          return 'black';
        }

      case self::TYPE_PRIORITY:
        if ($old == ManiphestTaskPriority::getDefaultPriority()) {
          return 'green';
        } else if ($old > $new) {
          return 'grey';
        } else {
          return 'yellow';
        }

      case self::TYPE_MERGED_FROM:
        return 'orange';

      case self::TYPE_MERGED_INTO:
        return 'black';
    }

    return parent::getColor();
  }

  public function getActionName() {
    $old = $this->getOldValue();
    $new = $this->getNewValue();

    switch ($this->getTransactionType()) {
      case self::TYPE_TITLE:
        if ($old === null) {
          return pht('Created');
        }

        return pht('Retitled');

      case self::TYPE_STATUS:
        $action = ManiphestTaskStatus::getStatusActionName($new);
        if ($action) {
          return $action;
        }

        $old_closed = ManiphestTaskStatus::isClosedStatus($old);
        $new_closed = ManiphestTaskStatus::isClosedStatus($new);

        if ($new_closed && !$old_closed) {
          return pht('Closed');
        } else if (!$new_closed && $old_closed) {
          return pht('Reopened');
        } else {
          return pht('Changed Status');
        }

      case self::TYPE_DESCRIPTION:
        return pht('Edited');

      case self::TYPE_OWNER:
        if ($this->getAuthorPHID() == $new) {
          return pht('Claimed');
        } else if (!$new) {
          return pht('Up For Grabs');
        } else if (!$old) {
          return pht('Assigned');
        } else {
          return pht('Reassigned');
        }

      case self::TYPE_CCS:
        return pht('Changed CC');

      case self::TYPE_PROJECTS:
        return pht('Changed Projects');

      case self::TYPE_PROJECT_COLUMN:
        return pht('Changed Project Column');

      case self::TYPE_PRIORITY:
        if ($old == ManiphestTaskPriority::getDefaultPriority()) {
          return pht('Triaged');
        } else if ($old > $new) {
          return pht('Lowered Priority');
        } else {
          return pht('Raised Priority');
        }

      case self::TYPE_EDGE:
      case self::TYPE_ATTACH:
        return pht('Attached');

      case self::TYPE_UNBLOCK:
        $old_status = head($old);
        $new_status = head($new);

        $old_closed = ManiphestTaskStatus::isClosedStatus($old_status);
        $new_closed = ManiphestTaskStatus::isClosedStatus($new_status);

        if ($old_closed && !$new_closed) {
          return pht('Block');
        } else if (!$old_closed && $new_closed) {
          return pht('Unblock');
        } else {
          return pht('Blocker');
        }

      case self::TYPE_MERGED_INTO:
      case self::TYPE_MERGED_FROM:
        return pht('Merged');

    }

    return parent::getActionName();
  }

  public function getIcon() {
    $old = $this->getOldValue();
    $new = $this->getNewValue();

    switch ($this->getTransactionType()) {
      case self::TYPE_OWNER:
        return 'fa-user';

      case self::TYPE_CCS:
        return 'fa-envelope';

      case self::TYPE_TITLE:
        if ($old === null) {
          return 'fa-pencil';
        }

        return 'fa-pencil';

      case self::TYPE_STATUS:
        $action = ManiphestTaskStatus::getStatusIcon($new);
        if ($action !== null) {
          return $action;
        }

        if (ManiphestTaskStatus::isClosedStatus($new)) {
          return 'fa-check';
        } else {
          return 'fa-pencil';
        }

      case self::TYPE_DESCRIPTION:
        return 'fa-pencil';

      case self::TYPE_PROJECTS:
        return 'fa-briefcase';

      case self::TYPE_PROJECT_COLUMN:
        return 'fa-columns';

      case self::TYPE_MERGED_INTO:
        return 'fa-check';
      case self::TYPE_MERGED_FROM:
        return 'fa-compress';

      case self::TYPE_PRIORITY:
        if ($old == ManiphestTaskPriority::getDefaultPriority()) {
          return 'fa-arrow-right';
        } else if ($old > $new) {
          return 'fa-arrow-down';
        } else {
          return 'fa-arrow-up';
        }

      case self::TYPE_EDGE:
      case self::TYPE_ATTACH:
        return 'fa-thumb-tack';

      case self::TYPE_UNBLOCK:
        return 'fa-shield';

    }

    return parent::getIcon();
  }



  public function getTitle() {
    $author_phid = $this->getAuthorPHID();

    $old = $this->getOldValue();
    $new = $this->getNewValue();

    switch ($this->getTransactionType()) {
      case self::TYPE_TITLE:
        if ($old === null) {
          return pht(
            '%s created this task.',
            $this->renderHandleLink($author_phid));
        }
        return pht(
          '%s changed the title from "%s" to "%s".',
          $this->renderHandleLink($author_phid),
          $old,
          $new);

      case self::TYPE_DESCRIPTION:
        return pht(
          '%s edited the task description.',
          $this->renderHandleLink($author_phid));

      case self::TYPE_STATUS:
        $old_closed = ManiphestTaskStatus::isClosedStatus($old);
        $new_closed = ManiphestTaskStatus::isClosedStatus($new);

        $old_name = ManiphestTaskStatus::getTaskStatusName($old);
        $new_name = ManiphestTaskStatus::getTaskStatusName($new);

        if ($new_closed && !$old_closed) {
          if ($new == ManiphestTaskStatus::getDuplicateStatus()) {
            return pht(
              '%s closed this task as a duplicate.',
              $this->renderHandleLink($author_phid));
          } else {
            return pht(
              '%s closed this task as "%s".',
              $this->renderHandleLink($author_phid),
              $new_name);
          }
        } else if (!$new_closed && $old_closed) {
          return pht(
            '%s reopened this task as "%s".',
            $this->renderHandleLink($author_phid),
            $new_name);
        } else {
          return pht(
            '%s changed the task status from "%s" to "%s".',
            $this->renderHandleLink($author_phid),
            $old_name,
            $new_name);
        }

      case self::TYPE_UNBLOCK:
        $blocker_phid = key($new);
        $old_status = head($old);
        $new_status = head($new);

        $old_closed = ManiphestTaskStatus::isClosedStatus($old_status);
        $new_closed = ManiphestTaskStatus::isClosedStatus($new_status);

        $old_name = ManiphestTaskStatus::getTaskStatusName($old_status);
        $new_name = ManiphestTaskStatus::getTaskStatusName($new_status);

        if ($old_closed && !$new_closed) {
          return pht(
            '%s reopened blocking task %s as "%s".',
            $this->renderHandleLink($author_phid),
            $this->renderHandleLink($blocker_phid),
            $new_name);
        } else if (!$old_closed && $new_closed) {
          return pht(
            '%s closed blocking task %s as "%s".',
            $this->renderHandleLink($author_phid),
            $this->renderHandleLink($blocker_phid),
            $new_name);
        } else {
          return pht(
            '%s changed the status of blocking task %s from "%s" to "%s".',
            $this->renderHandleLink($author_phid),
            $this->renderHandleLink($blocker_phid),
            $old_name,
            $new_name);
        }

      case self::TYPE_OWNER:
        if ($author_phid == $new) {
          return pht(
            '%s claimed this task.',
            $this->renderHandleLink($author_phid));
        } else if (!$new) {
          return pht(
            '%s placed this task up for grabs.',
            $this->renderHandleLink($author_phid));
        } else if (!$old) {
          return pht(
            '%s assigned this task to %s.',
            $this->renderHandleLink($author_phid),
            $this->renderHandleLink($new));
        } else {
          return pht(
            '%s reassigned this task from %s to %s.',
            $this->renderHandleLink($author_phid),
            $this->renderHandleLink($old),
            $this->renderHandleLink($new));
        }

      case self::TYPE_PROJECTS:
        $added = array_diff($new, $old);
        $removed = array_diff($old, $new);
        if ($added && !$removed) {
          return pht(
            '%s added %d project(s): %s',
            $this->renderHandleLink($author_phid),
            count($added),
            $this->renderHandleList($added));
        } else if ($removed && !$added) {
          return pht(
            '%s removed %d project(s): %s',
            $this->renderHandleLink($author_phid),
            count($removed),
            $this->renderHandleList($removed));
        } else if ($removed && $added) {
          return pht(
            '%s changed project(s), added %d: %s; removed %d: %s',
            $this->renderHandleLink($author_phid),
            count($added),
            $this->renderHandleList($added),
            count($removed),
            $this->renderHandleList($removed));
        } else {
          // This is hit when rendering previews.
          return pht(
            '%s changed projects...',
            $this->renderHandleLink($author_phid));
        }

      case self::TYPE_PRIORITY:
        $old_name = ManiphestTaskPriority::getTaskPriorityName($old);
        $new_name = ManiphestTaskPriority::getTaskPriorityName($new);

        if ($old == ManiphestTaskPriority::getDefaultPriority()) {
          return pht(
            '%s triaged this task as "%s" priority.',
            $this->renderHandleLink($author_phid),
            $new_name);
        } else if ($old > $new) {
          return pht(
            '%s lowered the priority of this task from "%s" to "%s".',
            $this->renderHandleLink($author_phid),
            $old_name,
            $new_name);
        } else {
          return pht(
            '%s raised the priority of this task from "%s" to "%s".',
            $this->renderHandleLink($author_phid),
            $old_name,
            $new_name);
        }

      case self::TYPE_CCS:
        // TODO: Remove this when we switch to subscribers. Just reuse the
        // code in the parent.
        $clone = clone $this;
        $clone->setTransactionType(PhabricatorTransactions::TYPE_SUBSCRIBERS);
        return $clone->getTitle();

      case self::TYPE_EDGE:
        // TODO: Remove this when we switch to real edges. Just reuse the
        // code in the parent;
        $clone = clone $this;
        $clone->setTransactionType(PhabricatorTransactions::TYPE_EDGE);
        return $clone->getTitle();

      case self::TYPE_ATTACH:
        $old = nonempty($old, array());
        $new = nonempty($new, array());
        $new = array_keys(idx($new, 'FILE', array()));
        $old = array_keys(idx($old, 'FILE', array()));

        $added = array_diff($new, $old);
        $removed = array_diff($old, $new);
        if ($added && !$removed) {
          return pht(
            '%s attached %d file(s): %s',
            $this->renderHandleLink($author_phid),
            count($added),
            $this->renderHandleList($added));
        } else if ($removed && !$added) {
          return pht(
            '%s detached %d file(s): %s',
            $this->renderHandleLink($author_phid),
            count($removed),
            $this->renderHandleList($removed));
        } else {
          return pht(
            '%s changed file(s), attached %d: %s; detached %d: %s',
            $this->renderHandleLink($author_phid),
            count($added),
            $this->renderHandleList($added),
            count($removed),
            $this->renderHandleList($removed));
        }

      case self::TYPE_PROJECT_COLUMN:
        $project_phid = $new['projectPHID'];
        $column_phid = head($new['columnPHIDs']);
        return pht(
          '%s moved this task to %s on the %s workboard.',
          $this->renderHandleLink($author_phid),
          $this->renderHandleLink($column_phid),
          $this->renderHandleLink($project_phid));
        break;

      case self::TYPE_MERGED_INTO:
        return pht(
          '%s closed this task as a duplicate of %s.',
          $this->renderHandleLink($author_phid),
          $this->renderHandleLink($new));
        break;

      case self::TYPE_MERGED_FROM:
        return pht(
          '%s merged %d task(s): %s.',
          $this->renderHandleLink($author_phid),
          count($new),
          $this->renderHandleList($new));
        break;


    }

    return parent::getTitle();
  }

  public function getTitleForFeed(PhabricatorFeedStory $story) {
    $author_phid = $this->getAuthorPHID();
    $object_phid = $this->getObjectPHID();

    $old = $this->getOldValue();
    $new = $this->getNewValue();

    switch ($this->getTransactionType()) {
      case self::TYPE_TITLE:
        if ($old === null) {
          return pht(
            '%s created %s.',
            $this->renderHandleLink($author_phid),
            $this->renderHandleLink($object_phid));
        }

        return pht(
          '%s renamed %s from "%s" to "%s".',
          $this->renderHandleLink($author_phid),
          $this->renderHandleLink($object_phid),
          $old,
          $new);

      case self::TYPE_DESCRIPTION:
        return pht(
          '%s edited the description of %s.',
          $this->renderHandleLink($author_phid),
          $this->renderHandleLink($object_phid));

      case self::TYPE_STATUS:
        $old_closed = ManiphestTaskStatus::isClosedStatus($old);
        $new_closed = ManiphestTaskStatus::isClosedStatus($new);

        $old_name = ManiphestTaskStatus::getTaskStatusName($old);
        $new_name = ManiphestTaskStatus::getTaskStatusName($new);

        if ($new_closed && !$old_closed) {
          if ($new == ManiphestTaskStatus::getDuplicateStatus()) {
            return pht(
              '%s closed %s as a duplicate.',
              $this->renderHandleLink($author_phid),
              $this->renderHandleLink($object_phid));
          } else {
            return pht(
              '%s closed %s as "%s".',
              $this->renderHandleLink($author_phid),
              $this->renderHandleLink($object_phid),
              $new_name);
          }
        } else if (!$new_closed && $old_closed) {
          return pht(
            '%s reopened %s as "%s".',
            $this->renderHandleLink($author_phid),
            $this->renderHandleLink($object_phid),
            $new_name);
        } else {
          return pht(
            '%s changed the status of %s from "%s" to "%s".',
            $this->renderHandleLink($author_phid),
            $this->renderHandleLink($object_phid),
            $old_name,
            $new_name);
        }

      case self::TYPE_UNBLOCK:
        $blocker_phid = key($new);
        $old_status = head($old);
        $new_status = head($new);

        $old_closed = ManiphestTaskStatus::isClosedStatus($old_status);
        $new_closed = ManiphestTaskStatus::isClosedStatus($new_status);

        $old_name = ManiphestTaskStatus::getTaskStatusName($old_status);
        $new_name = ManiphestTaskStatus::getTaskStatusName($new_status);

        if ($old_closed && !$new_closed) {
          return pht(
            '%s reopened %s, a task blocking %s, as "%s".',
            $this->renderHandleLink($author_phid),
            $this->renderHandleLink($blocker_phid),
            $this->renderHandleLink($object_phid),
            $new_name);
        } else if (!$old_closed && $new_closed) {
          return pht(
            '%s closed %s, a task blocking %s, as "%s".',
            $this->renderHandleLink($author_phid),
            $this->renderHandleLink($blocker_phid),
            $this->renderHandleLink($object_phid),
            $new_name);
        } else {
          return pht(
            '%s changed the status of %s, a task blocking %s, '.
            'from "%s" to "%s".',
            $this->renderHandleLink($author_phid),
            $this->renderHandleLink($blocker_phid),
            $this->renderHandleLink($object_phid),
            $old_name,
            $new_name);
        }

      case self::TYPE_OWNER:
        if ($author_phid == $new) {
          return pht(
            '%s claimed %s.',
            $this->renderHandleLink($author_phid),
            $this->renderHandleLink($object_phid));
        } else if (!$new) {
          return pht(
            '%s placed %s up for grabs.',
            $this->renderHandleLink($author_phid),
            $this->renderHandleLink($object_phid));
        } else if (!$old) {
          return pht(
            '%s assigned %s to %s.',
            $this->renderHandleLink($author_phid),
            $this->renderHandleLink($object_phid),
            $this->renderHandleLink($new));
        } else {
          return pht(
            '%s reassigned %s from %s to %s.',
            $this->renderHandleLink($author_phid),
            $this->renderHandleLink($object_phid),
            $this->renderHandleLink($old),
            $this->renderHandleLink($new));
        }

      case self::TYPE_PROJECTS:
        $added = array_diff($new, $old);
        $removed = array_diff($old, $new);
        if ($added && !$removed) {
          return pht(
            '%s added %d project(s) to %s: %s',
            $this->renderHandleLink($author_phid),
            count($added),
            $this->renderHandleLink($object_phid),
            $this->renderHandleList($added));
        } else if ($removed && !$added) {
          return pht(
            '%s removed %d project(s) from %s: %s',
            $this->renderHandleLink($author_phid),
            count($removed),
            $this->renderHandleLink($object_phid),
            $this->renderHandleList($removed));
        } else if ($removed && $added) {
          return pht(
            '%s changed project(s) of %s, added %d: %s; removed %d: %s',
            $this->renderHandleLink($author_phid),
            $this->renderHandleLink($object_phid),
            count($added),
            $this->renderHandleList($added),
            count($removed),
            $this->renderHandleList($removed));
        }

      case self::TYPE_PRIORITY:
        $old_name = ManiphestTaskPriority::getTaskPriorityName($old);
        $new_name = ManiphestTaskPriority::getTaskPriorityName($new);

        if ($old == ManiphestTaskPriority::getDefaultPriority()) {
          return pht(
            '%s triaged %s as "%s" priority.',
            $this->renderHandleLink($author_phid),
            $this->renderHandleLink($object_phid),
            $new_name);
        } else if ($old > $new) {
          return pht(
            '%s lowered the priority of %s from "%s" to "%s".',
            $this->renderHandleLink($author_phid),
            $this->renderHandleLink($object_phid),
            $old_name,
            $new_name);
        } else {
          return pht(
            '%s raised the priority of %s from "%s" to "%s".',
            $this->renderHandleLink($author_phid),
            $this->renderHandleLink($object_phid),
            $old_name,
            $new_name);
        }

      case self::TYPE_CCS:
        // TODO: Remove this when we switch to subscribers. Just reuse the
        // code in the parent.
        $clone = clone $this;
        $clone->setTransactionType(PhabricatorTransactions::TYPE_SUBSCRIBERS);
        return $clone->getTitleForFeed($story);

      case self::TYPE_EDGE:
        // TODO: Remove this when we switch to real edges. Just reuse the
        // code in the parent;
        $clone = clone $this;
        $clone->setTransactionType(PhabricatorTransactions::TYPE_EDGE);
        return $clone->getTitleForFeed($story);

      case self::TYPE_ATTACH:
        $old = nonempty($old, array());
        $new = nonempty($new, array());
        $new = array_keys(idx($new, 'FILE', array()));
        $old = array_keys(idx($old, 'FILE', array()));

        $added = array_diff($new, $old);
        $removed = array_diff($old, $new);
        if ($added && !$removed) {
          return pht(
            '%s attached %d file(s) of %s: %s',
            $this->renderHandleLink($author_phid),
            $this->renderHandleLink($object_phid),
            count($added),
            $this->renderHandleList($added));
        } else if ($removed && !$added) {
          return pht(
            '%s detached %d file(s) of %s: %s',
            $this->renderHandleLink($author_phid),
            $this->renderHandleLink($object_phid),
            count($removed),
            $this->renderHandleList($removed));
        } else {
          return pht(
            '%s changed file(s) for %s, attached %d: %s; detached %d: %s',
            $this->renderHandleLink($author_phid),
            $this->renderHandleLink($object_phid),
            count($added),
            $this->renderHandleList($added),
            count($removed),
            $this->renderHandleList($removed));
        }

      case self::TYPE_PROJECT_COLUMN:
        $project_phid = $new['projectPHID'];
        $column_phid = head($new['columnPHIDs']);
        return pht(
          '%s moved %s to %s on the %s workboard.',
          $this->renderHandleLink($author_phid),
          $this->renderHandleLink($object_phid),
          $this->renderHandleLink($column_phid),
          $this->renderHandleLink($project_phid));

      case self::TYPE_MERGED_INTO:
        return pht(
          '%s merged task %s into %s.',
          $this->renderHandleLink($author_phid),
          $this->renderHandleLink($object_phid),
          $this->renderHandleLink($new));

      case self::TYPE_MERGED_FROM:
        return pht(
          '%s merged %d task(s) %s into %s.',
          $this->renderHandleLink($author_phid),
          count($new),
          $this->renderHandleList($new),
          $this->renderHandleLink($object_phid));

    }

    return parent::getTitleForFeed($story);
  }

  public function hasChangeDetails() {
    switch ($this->getTransactionType()) {
      case self::TYPE_DESCRIPTION:
        return true;
    }
    return parent::hasChangeDetails();
  }

  public function renderChangeDetails(PhabricatorUser $viewer) {
    return $this->renderTextCorpusChangeDetails(
      $viewer,
      $this->getOldValue(),
      $this->getNewValue());
  }

  public function getMailTags() {
    $tags = array();
    switch ($this->getTransactionType()) {
      case self::TYPE_STATUS:
        $tags[] = self::MAILTAG_STATUS;
        break;
      case self::TYPE_OWNER:
        $tags[] = self::MAILTAG_OWNER;
        break;
      case self::TYPE_CCS:
        $tags[] = self::MAILTAG_CC;
        break;
      case PhabricatorTransactions::TYPE_EDGE:
        switch ($this->getMetadataValue('edge:type')) {
          case PhabricatorProjectObjectHasProjectEdgeType::EDGECONST:
            $tags[] = self::MAILTAG_PROJECTS;
            break;
          default:
            $tags[] = self::MAILTAG_OTHER;
            break;
        }
        break;
      case self::TYPE_PRIORITY:
        $tags[] = self::MAILTAG_PRIORITY;
        break;
      case self::TYPE_UNBLOCK:
        $tags[] = self::MAILTAG_UNBLOCK;
        break;
      case self::TYPE_PROJECT_COLUMN:
        $tags[] = self::MAILTAG_COLUMN;
        break;
      case PhabricatorTransactions::TYPE_COMMENT:
        $tags[] = self::MAILTAG_COMMENT;
        break;
      default:
        $tags[] = self::MAILTAG_OTHER;
        break;
    }
    return $tags;
  }

  public function getNoEffectDescription() {

    switch ($this->getTransactionType()) {
      case self::TYPE_STATUS:
        return pht('The task already has the selected status.');
      case self::TYPE_OWNER:
        return pht('The task already has the selected owner.');
      case self::TYPE_PROJECTS:
        return pht('The task is already associated with those projects.');
      case self::TYPE_PRIORITY:
        return pht('The task already has the selected priority.');
    }

    return parent::getNoEffectDescription();
  }

}
