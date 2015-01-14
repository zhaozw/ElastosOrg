<?php

final class PhabricatorSetupCheckDaemons extends PhabricatorSetupCheck {

  protected function executeChecks() {

    $task_daemon = id(new PhabricatorDaemonLogQuery())
      ->setViewer(PhabricatorUser::getOmnipotentUser())
      ->withStatus(PhabricatorDaemonLogQuery::STATUS_ALIVE)
      ->withDaemonClasses(array('PhabricatorTaskmasterDaemon'))
      ->setLimit(1)
      ->execute();

    if (!$task_daemon) {
      $doc_href = PhabricatorEnv::getDocLink(
        'Managing Daemons with phd');

      $summary = pht(
        'You must start the Phabricator daemons to send email, rebuild '.
        'search indexes, and do other background processing.');

      $message = pht(
        'The Phabricator daemons are not running, so Phabricator will not '.
        'be able to perform background processing (including sending email, '.
        'rebuilding search indexes, importing commits, cleaning up old data, '.
        'and running builds).'.
        "\n\n".
        'Use %s to start daemons. See %s for more information.',
        phutil_tag('tt', array(), 'bin/phd start'),
        phutil_tag(
          'a',
          array(
            'href' => $doc_href,
            'target' => '_blank',
          ),
          pht('Managing Daemons with phd')));

      $this->newIssue('daemons.not-running')
        ->setShortName(pht('Daemons Not Running'))
        ->setName(pht('Phabricator Daemons Are Not Running'))
        ->setSummary($summary)
        ->setMessage($message)
        ->addCommand('phabricator/ $ ./bin/phd start');
    }

    $environment_hash = PhabricatorEnv::calculateEnvironmentHash();
    $all_daemons = id(new PhabricatorDaemonLogQuery())
      ->setViewer(PhabricatorUser::getOmnipotentUser())
      ->withStatus(PhabricatorDaemonLogQuery::STATUS_ALIVE)
      ->execute();
    foreach ($all_daemons as $daemon) {
      if ($daemon->getEnvHash() != $environment_hash) {
        $doc_href = PhabricatorEnv::getDocLink(
          'Managing Daemons with phd');

        $summary = pht(
          'At least one daemon is currently running with different '.
          'configuration than the Phabricator web application.');

        $message = pht(
          'At least one daemon is currently running with a different '.
          'configuration (config checksum %s) than the web application '.
          '(config checksum %s).'.
          "\n\n".
          'This usually means that you have just made a configuration change '.
          'from the web UI, but have not yet restarted the daemons. You '.
          'need to restart the daemons after making configuration changes '.
          'so they will pick up the new values: until you do, they will '.
          'continue operating with the old settings.'.
          "\n\n".
          '(If you plan to make more changes, you can restart the daemons '.
          'once after you finish making all of your changes.)'.
          "\n\n".
          'Use %s to restart daemons. You can find a list of running daemons '.
          'in the %s, which will also help you identify which daemon (or '.
          'daemons) have divergent configuration. For more information about '.
          'managing the daemons, see %s in the documentation.'.
          "\n\n".
          'This can also happen if you use the %s environmental variable to '.
          'choose a configuration file, but the daemons run with a different '.
          'value than the web application. If restarting the daemons does '.
          'not resolve this issue and you use %s to select configuration, '.
          'check that it is set consistently.'.
          "\n\n".
          'A third possible cause is that you run several machines, and '.
          'the %s configuration file differs between them. This file is '.
          'updated when you edit configuration from the CLI with %s. If '.
          'restarting the daemons does not resolve this issue and you '.
          'run multiple machines, check that all machines have identical '.
          '%s configuration files.'.
          "\n\n".
          'This issue is not severe, but usually indicates that something '.
          'is not configured the way you expect, and may cause the daemons '.
          'to exhibit different behavior than the web application does.',

          phutil_tag('tt', array(), substr($daemon->getEnvHash(), 0, 12)),
          phutil_tag('tt', array(), substr($environment_hash, 0, 12)),
          phutil_tag('tt', array(), 'bin/phd restart'),
          phutil_tag(
            'a',
            array(
              'href' => '/daemon/',
              'target' => '_blank',
            ),
            pht('Daemon Console')),
          phutil_tag(
            'a',
            array(
              'href' => $doc_href,
              'target' => '_blank',
            ),
            pht('Managing Daemons with phd')),
          phutil_tag('tt', array(), 'PHABRICATOR_ENV'),
          phutil_tag('tt', array(), 'PHABRICATOR_ENV'),
          phutil_tag('tt', array(), 'phabricator/conf/local/local.json'),
          phutil_tag('tt', array(), 'bin/config'),
          phutil_tag('tt', array(), 'phabricator/conf/local/local.json'));

        $this->newIssue('daemons.need-restarting')
          ->setName(pht('Daemons and Web Have Different Config'))
          ->setSummary($summary)
          ->setMessage($message)
          ->addCommand('phabricator/ $ ./bin/phd restart');
        break;
      }
    }
  }

}
