<?php

final class PhabricatorNotificationClearController
  extends PhabricatorNotificationController {

  public function processRequest() {
    $request = $this->getRequest();
    $chrono_key = $request->getStr('chronoKey');
    $user = $request->getUser();

    if ($request->isDialogFormPost()) {
      $table = new PhabricatorFeedStoryNotification();

      queryfx(
        $table->establishConnection('w'),
        'UPDATE %T SET hasViewed = 1 '.
        'WHERE userPHID = %s AND hasViewed = 0 and chronologicalKey <= %s',
        $table->getTableName(),
        $user->getPHID(),
        $chrono_key);

      return id(new AphrontReloadResponse())
        ->setURI('/notification/');
    }

    $dialog = new AphrontDialogView();
    $dialog->setUser($user);
    $dialog->addCancelButton('/notification/');
    if ($chrono_key) {
      $dialog->setTitle('Really mark all notifications as read?');
      $dialog->addHiddenInput('chronoKey', $chrono_key);

      $is_serious =
        PhabricatorEnv::getEnvConfig('phabricator.serious-business');
      if ($is_serious) {
        $dialog->appendChild(
          pht(
            'All unread notifications will be marked as read. You can not '.
            'undo this action.'));
      } else {
        $dialog->appendChild(
          pht(
            "You can't ignore your problems forever, you know."));
      }

      $dialog->addSubmitButton(pht('Mark All Read'));
    } else {
      $dialog->setTitle('No notifications to mark as read.');
      $dialog->appendChild(pht(
        'You have no unread notifications.'));
    }

    return id(new AphrontDialogResponse())->setDialog($dialog);
  }
}
