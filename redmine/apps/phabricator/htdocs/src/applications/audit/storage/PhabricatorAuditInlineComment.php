<?php

final class PhabricatorAuditInlineComment
  implements PhabricatorInlineCommentInterface {

  private $proxy;
  private $syntheticAuthor;

  public function __construct() {
    $this->proxy = new PhabricatorAuditTransactionComment();
  }

  public function __clone() {
    $this->proxy = clone $this->proxy;
  }

  public function getTransactionPHID() {
    return $this->proxy->getTransactionPHID();
  }

  public function getTransactionComment() {
    return $this->proxy;
  }

  public function getTransactionCommentForSave() {
    $content_source = PhabricatorContentSource::newForSource(
      PhabricatorContentSource::SOURCE_LEGACY,
      array());

    $this->proxy
      ->setViewPolicy('public')
      ->setEditPolicy($this->getAuthorPHID())
      ->setContentSource($content_source)
      ->setCommentVersion(1);

    return $this->proxy;
  }

  public static function loadID($id) {
    $inlines = id(new PhabricatorAuditTransactionComment())->loadAllWhere(
      'id = %d',
      $id);
    if (!$inlines) {
      return null;
    }

    return head(self::buildProxies($inlines));
  }

  public static function loadDraftComments(
    PhabricatorUser $viewer,
    $commit_phid) {

    $inlines = id(new PhabricatorAuditTransactionComment())->loadAllWhere(
      'authorPHID = %s AND commitPHID = %s AND transactionPHID IS NULL
        AND pathID IS NOT NULL',
      $viewer->getPHID(),
      $commit_phid);

    return self::buildProxies($inlines);
  }

  public static function loadPublishedComments(
    PhabricatorUser $viewer,
    $commit_phid) {

    $inlines = id(new PhabricatorAuditTransactionComment())->loadAllWhere(
      'commitPHID = %s AND transactionPHID IS NOT NULL
        AND pathID IS NOT NULL',
      $commit_phid);

    return self::buildProxies($inlines);
  }

  public static function loadDraftAndPublishedComments(
    PhabricatorUser $viewer,
    $commit_phid,
    $path_id = null) {

    if ($path_id === null) {
      $inlines = id(new PhabricatorAuditTransactionComment())->loadAllWhere(
        'commitPHID = %s AND (transactionPHID IS NOT NULL OR authorPHID = %s)
          AND pathID IS NOT NULL',
        $commit_phid,
        $viewer->getPHID());
    } else {
      $inlines = id(new PhabricatorAuditTransactionComment())->loadAllWhere(
        'commitPHID = %s AND pathID = %d AND
          (authorPHID = %s OR transactionPHID IS NOT NULL)',
        $commit_phid,
        $path_id,
        $viewer->getPHID());
    }

    return self::buildProxies($inlines);
  }

  private static function buildProxies(array $inlines) {
    $results = array();
    foreach ($inlines as $key => $inline) {
      $results[$key] = PhabricatorAuditInlineComment::newFromModernComment(
        $inline);
    }
    return $results;
  }

  public function setSyntheticAuthor($synthetic_author) {
    $this->syntheticAuthor = $synthetic_author;
    return $this;
  }

  public function getSyntheticAuthor() {
    return $this->syntheticAuthor;
  }

  public function openTransaction() {
    $this->proxy->openTransaction();
  }

  public function saveTransaction() {
    $this->proxy->saveTransaction();
  }

  public function save() {
    $this->getTransactionCommentForSave()->save();

    return $this;
  }

  public function delete() {
    $this->proxy->delete();

    return $this;
  }

  public function getID() {
    return $this->proxy->getID();
  }

  public function getPHID() {
    return $this->proxy->getPHID();
  }

  public static function newFromModernComment(
    PhabricatorAuditTransactionComment $comment) {

    $obj = new PhabricatorAuditInlineComment();
    $obj->proxy = $comment;

    return $obj;
  }

  public function isCompatible(PhabricatorInlineCommentInterface $comment) {
    return
      ($this->getAuthorPHID() === $comment->getAuthorPHID()) &&
      ($this->getSyntheticAuthor() === $comment->getSyntheticAuthor()) &&
      ($this->getContent() === $comment->getContent());
  }

  public function setContent($content) {
    $this->proxy->setContent($content);
    return $this;
  }

  public function getContent() {
    return $this->proxy->getContent();
  }

  public function isDraft() {
    return !$this->proxy->getTransactionPHID();
  }

  public function setPathID($id) {
    $this->proxy->setPathID($id);
    return $this;
  }

  public function getPathID() {
    return $this->proxy->getPathID();
  }

  public function setIsNewFile($is_new) {
    $this->proxy->setIsNewFile($is_new);
    return $this;
  }

  public function getIsNewFile() {
    return $this->proxy->getIsNewFile();
  }

  public function setLineNumber($number) {
    $this->proxy->setLineNumber($number);
    return $this;
  }

  public function getLineNumber() {
    return $this->proxy->getLineNumber();
  }

  public function setLineLength($length) {
    $this->proxy->setLineLength($length);
    return $this;
  }

  public function getLineLength() {
    return $this->proxy->getLineLength();
  }

  public function setCache($cache) {
    return $this;
  }

  public function getCache() {
    return null;
  }

  public function setAuthorPHID($phid) {
    $this->proxy->setAuthorPHID($phid);
    return $this;
  }

  public function getAuthorPHID() {
    return $this->proxy->getAuthorPHID();
  }

  public function setCommitPHID($commit_phid) {
    $this->proxy->setCommitPHID($commit_phid);
    return $this;
  }

  public function getCommitPHID() {
    return $this->proxy->getCommitPHID();
  }

  // When setting a comment ID, we also generate a phantom transaction PHID for
  // the future transaction.

  public function setAuditCommentID($id) {
    $this->proxy->setLegacyCommentID($id);
    $this->proxy->setTransactionPHID(
      PhabricatorPHID::generateNewPHID(
        PhabricatorApplicationTransactionTransactionPHIDType::TYPECONST,
        PhabricatorRepositoryCommitPHIDType::TYPECONST));
    return $this;
  }

  public function getAuditCommentID() {
    return $this->proxy->getLegacyCommentID();
  }

  public function setChangesetID($id) {
    return $this->setPathID($id);
  }

  public function getChangesetID() {
    return $this->getPathID();
  }


/* -(  PhabricatorMarkupInterface Implementation  )-------------------------- */


  public function getMarkupFieldKey($field) {
    return 'AI:'.$this->getID();
  }

  public function newMarkupEngine($field) {
    return PhabricatorMarkupEngine::newDifferentialMarkupEngine();
  }

  public function getMarkupText($field) {
    return $this->getContent();
  }

  public function didMarkupText($field, $output, PhutilMarkupEngine $engine) {
    return $output;
  }

  public function shouldUseMarkupCache($field) {
    // Only cache submitted comments.
    return ($this->getID() && $this->getAuditCommentID());
  }

}
