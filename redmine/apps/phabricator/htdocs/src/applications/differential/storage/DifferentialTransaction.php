<?php

final class DifferentialTransaction extends PhabricatorApplicationTransaction {

  private $isCommandeerSideEffect;


  public function setIsCommandeerSideEffect($is_side_effect) {
    $this->isCommandeerSideEffect = $is_side_effect;
    return $this;
  }

  public function getIsCommandeerSideEffect() {
    return $this->isCommandeerSideEffect;
  }

  const TYPE_INLINE = 'differential:inline';
  const TYPE_UPDATE = 'differential:update';
  const TYPE_ACTION = 'differential:action';
  const TYPE_STATUS = 'differential:status';

  public function getApplicationName() {
    return 'differential';
  }

  public function getApplicationTransactionType() {
    return DifferentialRevisionPHIDType::TYPECONST;
  }

  public function getApplicationTransactionCommentObject() {
    return new DifferentialTransactionComment();
  }

  public function shouldHide() {
    $old = $this->getOldValue();
    $new = $this->getNewValue();

    switch ($this->getTransactionType()) {
      case self::TYPE_UPDATE:
        // Older versions of this transaction have an ID for the new value,
        // and/or do not record the old value. Only hide the transaction if
        // the new value is a PHID, indicating that this is a newer style
        // transaction.
        if ($old === null) {
          if (phid_get_type($new) == DifferentialDiffPHIDType::TYPECONST) {
            return true;
          }
        }
        break;

      case PhabricatorTransactions::TYPE_EDGE:
        $add = array_diff_key($new, $old);
        $rem = array_diff_key($old, $new);

        // Hide metadata-only edge transactions. These correspond to users
        // accepting or rejecting revisions, but the change is always explicit
        // because of the TYPE_ACTION transaction. Rendering these transactions
        // just creates clutter.

        if (!$add && !$rem) {
          return true;
        }
        break;
    }

    return parent::shouldHide();
  }

  public function shouldHideForMail(array $xactions) {
    switch ($this->getTransactionType()) {
      case self::TYPE_INLINE:
        // Hide inlines when rendering mail transactions if any other
        // transaction type exists.
        foreach ($xactions as $xaction) {
          if ($xaction->getTransactionType() != self::TYPE_INLINE) {
            return true;
          }
        }

        // If only inline transactions exist, we just render the first one.
        return ($this !== head($xactions));
    }

    return parent::shouldHideForMail($xactions);
  }

  public function getBodyForMail() {
    switch ($this->getTransactionType()) {
      case self::TYPE_INLINE:
        // Don't render inlines into the mail body; they render into a special
        // section immediately after the body instead.
        return null;
    }

    return parent::getBodyForMail();
  }

  public function getRequiredHandlePHIDs() {
    $phids = parent::getRequiredHandlePHIDs();

    $old = $this->getOldValue();
    $new = $this->getNewValue();

    switch ($this->getTransactionType()) {
      case self::TYPE_ACTION:
        if ($new == DifferentialAction::ACTION_CLOSE &&
            $this->getMetadataValue('isCommitClose')) {
          $phids[] = $this->getMetadataValue('commitPHID');
          if ($this->getMetadataValue('committerPHID')) {
            $phids[] = $this->getMetadataValue('committerPHID');
          }
          if ($this->getMetadataValue('authorPHID')) {
            $phids[] = $this->getMetadataValue('authorPHID');
          }
        }
        break;
      case self::TYPE_UPDATE:
        if ($new) {
          $phids[] = $new;
        }
        break;
    }

    return $phids;
  }

  public function getActionStrength() {

    switch ($this->getTransactionType()) {
      case self::TYPE_ACTION:
        return 3;
      case self::TYPE_UPDATE:
        return 2;
      case self::TYPE_INLINE:
        return 0.25;
    }

    return parent::getActionStrength();
  }


  public function getActionName() {
    switch ($this->getTransactionType()) {
      case self::TYPE_INLINE:
        return pht('Commented On');
      case self::TYPE_UPDATE:
        $old = $this->getOldValue();
        if ($old === null) {
          return pht('Request');
        } else {
          return pht('Updated');
        }
      case self::TYPE_ACTION:
        $map = array(
          DifferentialAction::ACTION_ACCEPT => pht('Accepted'),
          DifferentialAction::ACTION_REJECT => pht('Requested Changes To'),
          DifferentialAction::ACTION_RETHINK => pht('Planned Changes To'),
          DifferentialAction::ACTION_ABANDON => pht('Abandoned'),
          DifferentialAction::ACTION_CLOSE => pht('Closed'),
          DifferentialAction::ACTION_REQUEST => pht('Requested A Review Of'),
          DifferentialAction::ACTION_RESIGN => pht('Resigned From'),
          DifferentialAction::ACTION_ADDREVIEWERS => pht('Added Reviewers'),
          DifferentialAction::ACTION_CLAIM => pht('Commandeered'),
          DifferentialAction::ACTION_REOPEN => pht('Reopened'),
        );
        $name = idx($map, $this->getNewValue());
        if ($name !== null) {
          return $name;
        }
        break;
    }

    return parent::getActionName();
  }

  public function getMailTags() {
    $tags = array();

    switch ($this->getTransactionType()) {
      case PhabricatorTransactions::TYPE_SUBSCRIBERS;
        $tags[] = MetaMTANotificationType::TYPE_DIFFERENTIAL_CC;
        break;
      case self::TYPE_ACTION:
        switch ($this->getNewValue()) {
          case DifferentialAction::ACTION_CLOSE:
            $tags[] = MetaMTANotificationType::TYPE_DIFFERENTIAL_CLOSED;
            break;
        }
        break;
      case self::TYPE_UPDATE:
        $old = $this->getOldValue();
        if ($old === null) {
          $tags[] = MetaMTANotificationType::TYPE_DIFFERENTIAL_REVIEW_REQUEST;
        } else {
          $tags[] = MetaMTANotificationType::TYPE_DIFFERENTIAL_UPDATED;
        }
        break;
      case PhabricatorTransactions::TYPE_EDGE:
        switch ($this->getMetadataValue('edge:type')) {
          case PhabricatorEdgeConfig::TYPE_DREV_HAS_REVIEWER:
            $tags[] = MetaMTANotificationType::TYPE_DIFFERENTIAL_REVIEWERS;
            break;
        }
        break;
      case PhabricatorTransactions::TYPE_COMMENT:
      case self::TYPE_INLINE:
        $tags[] = MetaMTANotificationType::TYPE_DIFFERENTIAL_COMMENT;
        break;
    }

    if (!$tags) {
      $tags[] = MetaMTANotificationType::TYPE_DIFFERENTIAL_OTHER;
    }

    return $tags;
  }

  public function getTitle() {
    $author_phid = $this->getAuthorPHID();
    $author_handle = $this->renderHandleLink($author_phid);

    $old = $this->getOldValue();
    $new = $this->getNewValue();

    switch ($this->getTransactionType()) {
      case self::TYPE_INLINE:
        return pht(
          '%s added inline comments.',
          $author_handle);
      case self::TYPE_UPDATE:
        if ($this->getMetadataValue('isCommitUpdate')) {
          return pht(
            'This revision was automatically updated to reflect the '.
            'committed changes.');
        } else if ($new) {
          // TODO: Migrate to PHIDs and use handles here?
          if (phid_get_type($new) == DifferentialDiffPHIDType::TYPECONST) {
            return pht(
              '%s updated this revision to %s.',
              $author_handle,
              $this->renderHandleLink($new));
          } else {
            return pht(
              '%s updated this revision.',
              $author_handle);
          }
        } else {
          return pht(
            '%s updated this revision.',
            $author_handle);
        }
      case self::TYPE_ACTION:
        switch ($new) {
          case DifferentialAction::ACTION_CLOSE:
            if (!$this->getMetadataValue('isCommitClose')) {
              return DifferentialAction::getBasicStoryText(
                $new,
                $author_handle);
            }
            $commit_name = $this->renderHandleLink(
              $this->getMetadataValue('commitPHID'));
            $committer_phid = $this->getMetadataValue('committerPHID');
            $author_phid = $this->getMetadataValue('authorPHID');
            if ($this->getHandleIfExists($committer_phid)) {
              $committer_name = $this->renderHandleLink($committer_phid);
            } else {
              $committer_name = $this->getMetadataValue('committerName');
            }
            if ($this->getHandleIfExists($author_phid)) {
              $author_name = $this->renderHandleLink($author_phid);
            } else {
              $author_name = $this->getMetadataValue('authorName');
            }

            if ($committer_name && ($committer_name != $author_name)) {
              return pht(
                'Closed by commit %s (authored by %s, committed by %s).',
                $commit_name,
                $author_name,
                $committer_name);
            } else {
              return pht(
                'Closed by commit %s (authored by %s).',
                $commit_name,
                $author_name);
            }
            break;
          default:
            return DifferentialAction::getBasicStoryText($new, $author_handle);
        }
        break;
      case self::TYPE_STATUS:
        switch ($this->getNewValue()) {
          case ArcanistDifferentialRevisionStatus::ACCEPTED:
            return pht(
              'This revision is now accepted and ready to land.');
          case ArcanistDifferentialRevisionStatus::NEEDS_REVISION:
            return pht(
              'This revision now requires changes to proceed.');
          case ArcanistDifferentialRevisionStatus::NEEDS_REVIEW:
            return pht(
              'This revision now requires review to proceed.');
        }
    }

    return parent::getTitle();
  }

  public function renderExtraInformationLink() {
    if ($this->getMetadataValue('revisionMatchData')) {
      $details_href =
        '/differential/revision/closedetails/'.$this->getPHID().'/';
      $details_link = javelin_tag(
        'a',
        array(
          'href' => $details_href,
          'sigil' => 'workflow',
        ),
        pht('Explain Why'));
      return $details_link;
    }
    return parent::renderExtraInformationLink();
  }

  public function getTitleForFeed(PhabricatorFeedStory $story) {
    $author_phid = $this->getAuthorPHID();
    $object_phid = $this->getObjectPHID();

    $old = $this->getOldValue();
    $new = $this->getNewValue();

    $author_link = $this->renderHandleLink($author_phid);
    $object_link = $this->renderHandleLink($object_phid);

    switch ($this->getTransactionType()) {
      case self::TYPE_INLINE:
        return pht(
          '%s added inline comments to %s.',
          $author_link,
          $object_link);
      case self::TYPE_UPDATE:
        return pht(
          '%s updated the diff for %s.',
          $author_link,
          $object_link);
      case self::TYPE_ACTION:
        switch ($new) {
          case DifferentialAction::ACTION_ACCEPT:
            return pht(
              '%s accepted %s.',
              $author_link,
              $object_link);
          case DifferentialAction::ACTION_REJECT:
            return pht(
              '%s requested changes to %s.',
              $author_link,
              $object_link);
          case DifferentialAction::ACTION_RETHINK:
            return pht(
              '%s planned changes to %s.',
              $author_link,
              $object_link);
          case DifferentialAction::ACTION_ABANDON:
            return pht(
              '%s abandoned %s.',
              $author_link,
              $object_link);
          case DifferentialAction::ACTION_CLOSE:
            if (!$this->getMetadataValue('isCommitClose')) {
              return pht(
                '%s closed %s.',
                $author_link,
                $object_link);
            } else {
              $commit_name = $this->renderHandleLink(
                $this->getMetadataValue('commitPHID'));
              $committer_phid = $this->getMetadataValue('committerPHID');
              $author_phid = $this->getMetadataValue('authorPHID');
              if ($this->getHandleIfExists($committer_phid)) {
                $committer_name = $this->renderHandleLink($committer_phid);
              } else {
                $committer_name = $this->getMetadataValue('committerName');
              }
              if ($this->getHandleIfExists($author_phid)) {
                $author_name = $this->renderHandleLink($author_phid);
              } else {
                $author_name = $this->getMetadataValue('authorName');
              }

              if ($committer_name && ($committer_name != $author_name)) {
                return pht(
                  '%s closed %s by commit %s (authored by %s).',
                  $author_link,
                  $object_link,
                  $commit_name,
                  $author_name);
              } else {
                return pht(
                  '%s closed %s by commit %s.',
                  $author_link,
                  $object_link,
                  $commit_name);
              }
            }
            break;

          case DifferentialAction::ACTION_REQUEST:
            return pht(
              '%s requested review of %s.',
              $author_link,
              $object_link);
          case DifferentialAction::ACTION_RECLAIM:
            return pht(
              '%s reclaimed %s.',
              $author_link,
              $object_link);
          case DifferentialAction::ACTION_RESIGN:
            return pht(
              '%s resigned from %s.',
              $author_link,
              $object_link);
          case DifferentialAction::ACTION_CLAIM:
            return pht(
              '%s commandeered %s.',
              $author_link,
              $object_link);
          case DifferentialAction::ACTION_REOPEN:
            return pht(
              '%s reopened %s.',
              $author_link,
              $object_link);
        }
        break;
      case self::TYPE_STATUS:
        switch ($this->getNewValue()) {
          case ArcanistDifferentialRevisionStatus::ACCEPTED:
            return pht(
              '%s is now accepted and ready to land.',
              $object_link);
          case ArcanistDifferentialRevisionStatus::NEEDS_REVISION:
            return pht(
              '%s now requires changes to proceed.',
              $object_link);
          case ArcanistDifferentialRevisionStatus::NEEDS_REVIEW:
            return pht(
              '%s now requires review to proceed.',
              $object_link);
        }
    }

    return parent::getTitleForFeed($story);
  }

  public function getIcon() {
    switch ($this->getTransactionType()) {
      case self::TYPE_INLINE:
        return 'fa-comment';
      case self::TYPE_UPDATE:
        return 'fa-refresh';
      case self::TYPE_STATUS:
        switch ($this->getNewValue()) {
          case ArcanistDifferentialRevisionStatus::ACCEPTED:
            return 'fa-check';
          case ArcanistDifferentialRevisionStatus::NEEDS_REVISION:
            return 'fa-times';
          case ArcanistDifferentialRevisionStatus::NEEDS_REVIEW:
            return 'fa-undo';
        }
        break;
      case self::TYPE_ACTION:
        switch ($this->getNewValue()) {
          case DifferentialAction::ACTION_CLOSE:
            return 'fa-check';
          case DifferentialAction::ACTION_ACCEPT:
            return 'fa-check-circle-o';
          case DifferentialAction::ACTION_REJECT:
            return 'fa-times-circle-o';
          case DifferentialAction::ACTION_ABANDON:
            return 'fa-plane';
          case DifferentialAction::ACTION_RETHINK:
            return 'fa-headphones';
          case DifferentialAction::ACTION_REQUEST:
            return 'fa-refresh';
          case DifferentialAction::ACTION_RECLAIM:
          case DifferentialAction::ACTION_REOPEN:
            return 'fa-bullhorn';
          case DifferentialAction::ACTION_RESIGN:
            return 'fa-flag';
          case DifferentialAction::ACTION_CLAIM:
            return 'fa-flag';
        }
      case PhabricatorTransactions::TYPE_EDGE:
        switch ($this->getMetadataValue('edge:type')) {
          case PhabricatorEdgeConfig::TYPE_DREV_HAS_REVIEWER:
            return 'fa-user';
        }
    }

    return parent::getIcon();
  }

  public function shouldDisplayGroupWith(array $group) {

    // Never group status changes with other types of actions, they're indirect
    // and don't make sense when combined with direct actions.

    $type_status = self::TYPE_STATUS;

    if ($this->getTransactionType() == $type_status) {
      return false;
    }

    foreach ($group as $xaction) {
      if ($xaction->getTransactionType() == $type_status) {
        return false;
      }
    }

    return parent::shouldDisplayGroupWith($group);
  }


  public function getColor() {
    switch ($this->getTransactionType()) {
      case self::TYPE_UPDATE:
        return PhabricatorTransactions::COLOR_SKY;
      case self::TYPE_STATUS:
        switch ($this->getNewValue()) {
          case ArcanistDifferentialRevisionStatus::ACCEPTED:
            return PhabricatorTransactions::COLOR_GREEN;
          case ArcanistDifferentialRevisionStatus::NEEDS_REVISION:
            return PhabricatorTransactions::COLOR_RED;
          case ArcanistDifferentialRevisionStatus::NEEDS_REVIEW:
            return PhabricatorTransactions::COLOR_ORANGE;
        }
        break;
      case self::TYPE_ACTION:
        switch ($this->getNewValue()) {
          case DifferentialAction::ACTION_CLOSE:
            return PhabricatorTransactions::COLOR_BLUE;
          case DifferentialAction::ACTION_ACCEPT:
            return PhabricatorTransactions::COLOR_GREEN;
          case DifferentialAction::ACTION_REJECT:
            return PhabricatorTransactions::COLOR_RED;
          case DifferentialAction::ACTION_ABANDON:
            return PhabricatorTransactions::COLOR_BLACK;
          case DifferentialAction::ACTION_RETHINK:
            return PhabricatorTransactions::COLOR_RED;
          case DifferentialAction::ACTION_REQUEST:
            return PhabricatorTransactions::COLOR_SKY;
          case DifferentialAction::ACTION_RECLAIM:
            return PhabricatorTransactions::COLOR_SKY;
          case DifferentialAction::ACTION_REOPEN:
            return PhabricatorTransactions::COLOR_SKY;
          case DifferentialAction::ACTION_RESIGN:
            return PhabricatorTransactions::COLOR_ORANGE;
          case DifferentialAction::ACTION_CLAIM:
            return PhabricatorTransactions::COLOR_YELLOW;
        }
    }


    return parent::getColor();
  }

  public function getNoEffectDescription() {
    switch ($this->getTransactionType()) {
      case PhabricatorTransactions::TYPE_EDGE:
        switch ($this->getMetadataValue('edge:type')) {
          case PhabricatorEdgeConfig::TYPE_DREV_HAS_REVIEWER:
            return pht(
              'The reviewers you are trying to add are already reviewing '.
              'this revision.');
        }
        break;
      case DifferentialTransaction::TYPE_ACTION:
        switch ($this->getNewValue()) {
          case DifferentialAction::ACTION_CLOSE:
            return pht('This revision is already closed.');
          case DifferentialAction::ACTION_ABANDON:
            return pht('This revision has already been abandoned.');
          case DifferentialAction::ACTION_RECLAIM:
            return pht(
              'You can not reclaim this revision because his revision is '.
              'not abandoned.');
          case DifferentialAction::ACTION_REOPEN:
            return pht(
              'You can not reopen this revision because this revision is '.
              'not closed.');
          case DifferentialAction::ACTION_RETHINK:
            return pht('This revision already requires changes.');
          case DifferentialAction::ACTION_REQUEST:
            return pht('Review is already requested for this revision.');
          case DifferentialAction::ACTION_RESIGN:
            return pht(
              'You can not resign from this revision because you are not '.
              'a reviewer.');
          case DifferentialAction::ACTION_CLAIM:
            return pht(
              'You can not commandeer this revision because you already own '.
              'it.');
          case DifferentialAction::ACTION_ACCEPT:
            return pht(
              'You have already accepted this revision.');
          case DifferentialAction::ACTION_REJECT:
            return pht(
              'You have already requested changes to this revision.');
        }
        break;
    }

    return parent::getNoEffectDescription();
  }

  public function renderAsTextForDoorkeeper(
    DoorkeeperFeedStoryPublisher $publisher,
    PhabricatorFeedStory $story,
    array $xactions) {

    $body = parent::renderAsTextForDoorkeeper($publisher, $story, $xactions);

    $inlines = array();
    foreach ($xactions as $xaction) {
      if ($xaction->getTransactionType() == self::TYPE_INLINE) {
        $inlines[] = $xaction;
      }
    }

    // TODO: This is a bit gross, but far less bad than it used to be. It
    // could be further cleaned up at some point.

    if ($inlines) {
      $engine = PhabricatorMarkupEngine::newMarkupEngine(array())
        ->setConfig('viewer', new PhabricatorUser())
        ->setMode(PhutilRemarkupEngine::MODE_TEXT);

      $body .= "\n\n";
      $body .= pht('Inline Comments');
      $body .= "\n";

      $changeset_ids = array();
      foreach ($inlines as $inline) {
        $changeset_ids[] = $inline->getComment()->getChangesetID();
      }

      $changesets = id(new DifferentialChangeset())->loadAllWhere(
        'id IN (%Ld)',
        $changeset_ids);

      foreach ($inlines as $inline) {
        $comment = $inline->getComment();
        $changeset = idx($changesets, $comment->getChangesetID());
        if (!$changeset) {
          continue;
        }

        $filename = $changeset->getDisplayFilename();
        $linenumber = $comment->getLineNumber();
        $inline_text = $engine->markupText($comment->getContent());
        $inline_text = rtrim($inline_text);

        $body .= "{$filename}:{$linenumber} {$inline_text}\n";
      }
    }

    return $body;
  }


}
