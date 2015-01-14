<?php

final class PhabricatorMetaMTAWorker
  extends PhabricatorWorker {

  public function getMaximumRetryCount() {
    return 250;
  }

  public function getWaitBeforeRetry(PhabricatorWorkerTask $task) {
    return ($task->getFailureCount() * 15);
  }

  public function doWork() {
    $message = $this->loadMessage();
    if (!$message) {
      throw new PhabricatorWorkerPermanentFailureException(
        pht('Unable to load message!'));
    }

    if ($message->getStatus() != PhabricatorMetaMTAMail::STATUS_QUEUE) {
      return;
    }

    try {
      $message->sendNow();
    } catch (PhabricatorMetaMTAPermanentFailureException $ex) {
      // If the mailer fails permanently, fail this task permanently.
      throw new PhabricatorWorkerPermanentFailureException($ex->getMessage());
    }
  }

  private function loadMessage() {
    $message_id = $this->getTaskData();
    return id(new PhabricatorMetaMTAMail())->load($message_id);
  }

  public function renderForDisplay(PhabricatorUser $viewer) {
    return phutil_tag(
      'pre',
      array(
      ),
      'phabricator/ $ ./bin/mail show-outbound --id '.$this->getTaskData());
  }

}
