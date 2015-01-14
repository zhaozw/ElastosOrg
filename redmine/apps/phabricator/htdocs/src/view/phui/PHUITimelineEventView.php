<?php

final class PHUITimelineEventView extends AphrontView {

  const DELIMITER = " \xC2\xB7 ";

  private $userHandle;
  private $title;
  private $icon;
  private $color;
  private $classes = array();
  private $contentSource;
  private $dateCreated;
  private $anchor;
  private $isEditable;
  private $isEdited;
  private $isRemovable;
  private $transactionPHID;
  private $isPreview;
  private $eventGroup = array();
  private $hideByDefault;
  private $token;
  private $tokenRemoved;
  private $quoteTargetID;
  private $quoteRef;
  private $reallyMajorEvent;

  public function setQuoteRef($quote_ref) {
    $this->quoteRef = $quote_ref;
    return $this;
  }

  public function getQuoteRef() {
    return $this->quoteRef;
  }

  public function setQuoteTargetID($quote_target_id) {
    $this->quoteTargetID = $quote_target_id;
    return $this;
  }

  public function getQuoteTargetID() {
    return $this->quoteTargetID;
  }

  public function setHideByDefault($hide_by_default) {
    $this->hideByDefault = $hide_by_default;
    return $this;
  }

  public function getHideByDefault() {
    return $this->hideByDefault;
  }

  public function setTransactionPHID($transaction_phid) {
    $this->transactionPHID = $transaction_phid;
    return $this;
  }

  public function getTransactionPHID() {
    return $this->transactionPHID;
  }

  public function setIsEdited($is_edited) {
    $this->isEdited = $is_edited;
    return $this;
  }

  public function getIsEdited() {
    return $this->isEdited;
  }

  public function setIsPreview($is_preview) {
    $this->isPreview = $is_preview;
    return $this;
  }

  public function getIsPreview() {
    return $this->isPreview;
  }

  public function setIsEditable($is_editable) {
    $this->isEditable = $is_editable;
    return $this;
  }

  public function getIsEditable() {
    return $this->isEditable;
  }

  public function setIsRemovable($is_removable) {
    $this->isRemovable = $is_removable;
    return $this;
  }

  public function getIsRemovable() {
    return $this->isRemovable;
  }

  public function setDateCreated($date_created) {
    $this->dateCreated = $date_created;
    return $this;
  }

  public function getDateCreated() {
    return $this->dateCreated;
  }

  public function setContentSource(PhabricatorContentSource $content_source) {
    $this->contentSource = $content_source;
    return $this;
  }

  public function getContentSource() {
    return $this->contentSource;
  }

  public function setUserHandle(PhabricatorObjectHandle $handle) {
    $this->userHandle = $handle;
    return $this;
  }

  public function setAnchor($anchor) {
    $this->anchor = $anchor;
    return $this;
  }

  public function getAnchor() {
    return $this->anchor;
  }

  public function setTitle($title) {
    $this->title = $title;
    return $this;
  }

  public function addClass($class) {
    $this->classes[] = $class;
    return $this;
  }

  public function setIcon($icon) {
    $this->icon = $icon;
    return $this;
  }

  public function setColor($color) {
    $this->color = $color;
    return $this;
  }

  public function setReallyMajorEvent($me) {
    $this->reallyMajorEvent = $me;
    return $this;
  }

  public function setToken($token, $removed = false) {
    $this->token = $token;
    $this->tokenRemoved = $removed;
    return $this;
  }

  public function getEventGroup() {
    return array_merge(array($this), $this->eventGroup);
  }

  public function addEventToGroup(PHUITimelineEventView $event) {
    $this->eventGroup[] = $event;
    return $this;
  }

  protected function shouldRenderEventTitle() {
    if ($this->title === null) {
      return false;
    }

    return true;
  }

  protected function renderEventTitle($force_icon, $has_menu, $extra) {
    $title = $this->title;

    $title_classes = array();
    $title_classes[] = 'phui-timeline-title';

    $icon = null;
    if ($this->icon || $force_icon) {
      $title_classes[] = 'phui-timeline-title-with-icon';
    }

    if ($has_menu) {
      $title_classes[] = 'phui-timeline-title-with-menu';
    }

    if ($this->icon) {
      $fill_classes = array();
      $fill_classes[] = 'phui-timeline-icon-fill';
      if ($this->color) {
        $fill_classes[] = 'phui-timeline-icon-fill-'.$this->color;
      }

      $icon = id(new PHUIIconView())
        ->setIconFont($this->icon.' white')
        ->addClass('phui-timeline-icon');

      $icon = phutil_tag(
        'span',
        array(
          'class' => implode(' ', $fill_classes),
        ),
        $icon);
    }

    $token = null;
    if ($this->token) {
      $token = id(new PHUIIconView())
        ->addClass('phui-timeline-token')
        ->setSpriteSheet(PHUIIconView::SPRITE_TOKENS)
        ->setSpriteIcon($this->token);
      if ($this->tokenRemoved) {
        $token->addClass('strikethrough');
      }
    }

    $title = phutil_tag(
      'div',
      array(
        'class' => implode(' ', $title_classes),
      ),
      array($icon, $token, $title, $extra));

    return $title;
  }

  public function render() {

    $events = $this->getEventGroup();

    // Move events with icons first.
    $icon_keys = array();
    foreach ($this->getEventGroup() as $key => $event) {
      if ($event->icon) {
        $icon_keys[] = $key;
      }
    }
    $events = array_select_keys($events, $icon_keys) + $events;
    $force_icon = (bool)$icon_keys;

    $menu = null;
    $items = array();
    $has_menu = false;
    if (!$this->getIsPreview()) {
      foreach ($this->getEventGroup() as $event) {
        $items[] = $event->getMenuItems($this->anchor);
        if ($event->hasChildren()) {
          $has_menu = true;
        }
      }
      $items = array_mergev($items);
    }

    if ($items || $has_menu) {
      $icon = id(new PHUIIconView())
        ->setIconFont('fa-caret-down');
      $aural = javelin_tag(
        'span',
        array(
          'aural' => true,
        ),
        pht('Comment Actions'));

      if ($items) {
        $sigil = 'phui-timeline-menu';
        Javelin::initBehavior('phui-timeline-dropdown-menu');
      } else {
        $sigil = null;
      }

      $action_list = id(new PhabricatorActionListView())
        ->setUser($this->getUser());
      foreach ($items as $item) {
        $action_list->addAction($item);
      }

      $menu = javelin_tag(
        $items ? 'a' : 'span',
        array(
          'href' => '#',
          'class' => 'phui-timeline-menu',
          'sigil' => $sigil,
          'aria-haspopup' => 'true',
          'aria-expanded' => 'false',
          'meta' => array(
            'items' => hsprintf('%s', $action_list),
          ),
        ),
        array(
          $aural,
          $icon,
        ));

      $has_menu = true;
    }

    // Render "extra" information (timestamp, etc).
    $extra = $this->renderExtra($events);

    $group_titles = array();
    $group_items = array();
    $group_children = array();
    foreach ($events as $event) {
      if ($event->shouldRenderEventTitle()) {
        $group_titles[] = $event->renderEventTitle(
          $force_icon,
          $has_menu,
          $extra);

        // Don't render this information more than once.
        $extra = null;
      }

      if ($event->hasChildren()) {
        $group_children[] = $event->renderChildren();
      }
    }

    $image_uri = $this->userHandle->getImageURI();

    $wedge = phutil_tag(
      'div',
      array(
        'class' => 'phui-timeline-wedge phui-timeline-border',
        'style' => (nonempty($image_uri)) ? '' : 'display: none;',
      ),
      '');

    $image = phutil_tag(
      'div',
      array(
        'style' => 'background-image: url('.$image_uri.')',
        'class' => 'phui-timeline-image',
      ),
      '');

    $content_classes = array();
    $content_classes[] = 'phui-timeline-content';

    $classes = array();
    $classes[] = 'phui-timeline-event-view';
    if ($group_children) {
      $classes[] = 'phui-timeline-major-event';
      $content = phutil_tag(
        'div',
        array(
          'class' => 'phui-timeline-inner-content',
        ),
        array(
          $group_titles,
          $menu,
          phutil_tag(
            'div',
            array(
              'class' => 'phui-timeline-core-content',
            ),
            $group_children),
        ));
    } else {
      $classes[] = 'phui-timeline-minor-event';
      $content = $group_titles;
    }

    $content = phutil_tag(
      'div',
      array(
        'class' => 'phui-timeline-group phui-timeline-border',
      ),
      $content);

    $content = phutil_tag(
      'div',
      array(
        'class' => implode(' ', $content_classes),
      ),
      array($image, $wedge, $content));

    $outer_classes = $this->classes;
    $outer_classes[] = 'phui-timeline-shell';
    $color = null;
    foreach ($this->getEventGroup() as $event) {
      if ($event->color) {
        $color = $event->color;
        break;
      }
    }

    if ($color) {
      $outer_classes[] = 'phui-timeline-'.$color;
    }

    $sigil = null;
    $meta = null;
    if ($this->getTransactionPHID()) {
      $sigil = 'transaction';
      $meta = array(
        'phid' => $this->getTransactionPHID(),
        'anchor' => $this->anchor,
      );
    }

    $major_event = null;
    if ($this->reallyMajorEvent) {
      $major_event = phutil_tag(
        'div',
        array(
          'class' => 'phui-timeline-event-view '.
                     'phui-timeline-spacer '.
                     'phui-timeline-spacer-bold',
        '',));
    }

    return array(
      javelin_tag(
        'div',
        array(
          'class' => implode(' ', $outer_classes),
          'id' => $this->anchor ? 'anchor-'.$this->anchor : null,
          'sigil' => $sigil,
          'meta' => $meta,
        ),
        phutil_tag(
          'div',
          array(
            'class' => implode(' ', $classes),
          ),
          $content)),
      $major_event,);
  }

  private function renderExtra(array $events) {
    $extra = array();

    if ($this->getIsPreview()) {
      $extra[] = pht('PREVIEW');
    } else {
      foreach ($events as $event) {
        if ($event->getIsEdited()) {
          $extra[] = pht('Edited');
          break;
        }
      }

      $source = $this->getContentSource();
      if ($source) {
        $extra[] = id(new PhabricatorContentSourceView())
          ->setContentSource($source)
          ->setUser($this->getUser())
          ->render();
      }

      $date_created = null;
      foreach ($events as $event) {
        if ($event->getDateCreated()) {
          if ($date_created === null) {
            $date_created = $event->getDateCreated();
          } else {
            $date_created = min($event->getDateCreated(), $date_created);
          }
        }
      }

      if ($date_created) {
        $date = phabricator_datetime(
          $date_created,
          $this->getUser());
        if ($this->anchor) {
          Javelin::initBehavior('phabricator-watch-anchor');

          $anchor = id(new PhabricatorAnchorView())
            ->setAnchorName($this->anchor)
            ->render();

          $date = array(
            $anchor,
            phutil_tag(
              'a',
              array(
                'href' => '#'.$this->anchor,
              ),
              $date),
          );
        }
        $extra[] = $date;
      }
    }

    $extra = javelin_tag(
      'span',
      array(
        'class' => 'phui-timeline-extra',
      ),
      phutil_implode_html(
        javelin_tag(
          'span',
          array(
            'aural' => false,
          ),
          self::DELIMITER),
        $extra));

    return $extra;
  }

  private function getMenuItems($anchor) {
    $xaction_phid = $this->getTransactionPHID();

    $items = array();

    if ($this->getIsEditable()) {
      $items[] = id(new PhabricatorActionView())
        ->setIcon('fa-pencil')
        ->setHref('/transactions/edit/'.$xaction_phid.'/')
        ->setName(pht('Edit Comment'))
        ->addSigil('transaction-edit')
        ->setMetadata(
          array(
            'anchor' => $anchor,
          ));
    }

    if ($this->getQuoteTargetID()) {
      $ref = null;
      if ($this->getQuoteRef()) {
        $ref = $this->getQuoteRef();
        if ($anchor) {
          $ref = $ref.'#'.$anchor;
        }
      }

      $items[] = id(new PhabricatorActionView())
        ->setIcon('fa-quote-left')
        ->setHref('#')
        ->setName(pht('Quote'))
        ->addSigil('transaction-quote')
        ->setMetadata(
          array(
            'targetID' => $this->getQuoteTargetID(),
            'uri' => '/transactions/quote/'.$xaction_phid.'/',
            'ref' => $ref,
          ));

      // if there is something to quote then there is something to view raw
      $items[] = id(new PhabricatorActionView())
        ->setIcon('fa-cutlery')
        ->setHref('/transactions/raw/'.$xaction_phid.'/')
        ->setName(pht('View Raw'))
        ->addSigil('transaction-raw')
        ->setMetadata(
          array(
            'anchor' => $anchor,
          ));

      $content_source = $this->getContentSource();
      $source_email = PhabricatorContentSource::SOURCE_EMAIL;
      if ($content_source->getSource() == $source_email) {
        $source_id = $content_source->getParam('id');
        if ($source_id) {
          $items[] = id(new PhabricatorActionView())
            ->setIcon('fa-envelope-o')
            ->setHref('/transactions/raw/'.$xaction_phid.'/?email')
            ->setName(pht('View Email Body'))
            ->addSigil('transaction-raw')
            ->setMetadata(
              array(
                'anchor' => $anchor,
              ));
        }
      }
    }

    if ($this->getIsRemovable()) {
      $items[] = id(new PhabricatorActionView())
        ->setIcon('fa-times')
        ->setHref('/transactions/remove/'.$xaction_phid.'/')
        ->setName(pht('Remove Comment'))
        ->addSigil('transaction-remove')
        ->setMetadata(
          array(
            'anchor' => $anchor,
          ));

    }

    if ($this->getIsEdited()) {
      $items[] = id(new PhabricatorActionView())
        ->setIcon('fa-list')
        ->setHref('/transactions/history/'.$xaction_phid.'/')
        ->setName(pht('View Edit History'))
        ->setWorkflow(true);
    }

    return $items;
  }

}
