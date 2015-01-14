<?php

final class PhabricatorMailManagementListOutboundWorkflow
  extends PhabricatorMailManagementWorkflow {

  protected function didConstruct() {
    $this
      ->setName('list-outbound')
      ->setSynopsis('List outbound messages sent by Phabricator.')
      ->setExamples(
        '**list-outbound**')
      ->setArguments(
        array(
          array(
            'name'    => 'limit',
            'param'   => 'N',
            'default' => 100,
            'help'    => 'Show a specific number of messages (default 100).',
          ),
        ));
  }

  public function execute(PhutilArgumentParser $args) {
    $console = PhutilConsole::getConsole();
    $viewer = $this->getViewer();

    $mails = id(new PhabricatorMetaMTAMail())->loadAllWhere(
      '1 = 1 ORDER BY id DESC LIMIT %d',
      $args->getArg('limit'));

    if (!$mails) {
      $console->writeErr("%s\n", pht('No sent mail.'));
      return 0;
    }

    $table = id(new PhutilConsoleTable())
      ->setShowHeader(false)
      ->addColumn('id',      array('title' => 'ID'))
      ->addColumn('status',  array('title' => 'Status'))
      ->addColumn('subject', array('title' => 'Subject'));

    foreach (array_reverse($mails) as $mail) {
      $status = $mail->getStatus();

      $table->addRow(array(
        'id'      => $mail->getID(),
        'status'  => PhabricatorMetaMTAMail::getReadableStatus($status),
        'subject' => $mail->getSubject(),
      ));
    }

    $table->draw();
    return 0;
  }

}
