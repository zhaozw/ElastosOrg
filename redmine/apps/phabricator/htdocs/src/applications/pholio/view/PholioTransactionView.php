<?php

final class PholioTransactionView
  extends PhabricatorApplicationTransactionView {

  private $mock;

  public function setMock($mock) {
    $this->mock = $mock;
    return $this;
  }

  public function getMock() {
    return $this->mock;
  }

  protected function shouldGroupTransactions(
    PhabricatorApplicationTransaction $u,
    PhabricatorApplicationTransaction $v) {

    if ($u->getAuthorPHID() != $v->getAuthorPHID()) {
      // Don't group transactions by different authors.
      return false;
    }

    if (($v->getDateCreated() - $u->getDateCreated()) > 60) {
      // Don't group if transactions happened more than 60s apart.
      return false;
    }

    switch ($u->getTransactionType()) {
      case PhabricatorTransactions::TYPE_COMMENT:
      case PholioTransactionType::TYPE_INLINE:
        break;
      default:
        return false;
    }

    switch ($v->getTransactionType()) {
      case PholioTransactionType::TYPE_INLINE:
        return true;
    }

    return parent::shouldGroupTransactions($u, $v);
  }

  protected function renderTransactionContent(
    PhabricatorApplicationTransaction $xaction) {

    $out = array();

    $group = $xaction->getTransactionGroup();
    if ($xaction->getTransactionType() == PholioTransactionType::TYPE_INLINE) {
      array_unshift($group, $xaction);
    } else {
      $out[] = parent::renderTransactionContent($xaction);
    }

    if (!$group) {
      return $out;
    }

    $inlines = array();
    foreach ($group as $xaction) {
      switch ($xaction->getTransactionType()) {
        case PholioTransactionType::TYPE_INLINE:
          $inlines[] = $xaction;
          break;
        default:
          throw new Exception('Unknown grouped transaction type!');
      }
    }

    if ($inlines) {
      $icon = id(new PHUIIconView())
        ->setIconFont('fa-comment bluegrey msr');
      $header = phutil_tag(
        'div',
        array(
          'class' => 'phabricator-transaction-subheader',
        ),
        array($icon, pht('Inline Comments')));

      $out[] = $header;
      foreach ($inlines as $inline) {
        if (!$inline->getComment()) {
          continue;
        }
        $out[] = $this->renderInlineContent($inline);
      }
    }

    return $out;
  }

  private function renderInlineContent(PholioTransaction $inline) {
    $comment = $inline->getComment();
    $mock = $this->getMock();
    $images = $mock->getAllImages();
    $images = mpull($images, null, 'getID');

    $image = idx($images, $comment->getImageID());
    if (!$image) {
      throw new Exception('No image attached!');
    }

    $file = $image->getFile();
    if (!$file->isViewableImage()) {
      throw new Exception('File is not viewable.');
    }

    $image_uri = $file->getBestURI();

    $thumb = id(new PHUIImageMaskView())
      ->addClass('mrl')
      ->setImage($image_uri)
      ->setDisplayHeight(100)
      ->setDisplayWidth(200)
      ->withMask(true)
      ->centerViewOnPoint(
        $comment->getX(), $comment->getY(),
        $comment->getHeight(), $comment->getWidth());

    $link = phutil_tag(
      'a',
      array(
        'href' => '#',
        'class' => 'pholio-transaction-inline-image-anchor',
      ),
      $thumb);

    $inline_comment = parent::renderTransactionContent($inline);

    return phutil_tag(
      'div',
      array('class' => 'pholio-transaction-inline-comment'),
      array($link, $inline_comment));
  }

}
