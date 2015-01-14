<?php

final class PhabricatorDaemonConsoleController
  extends PhabricatorDaemonController {

  public function processRequest() {
    $request = $this->getRequest();
    $user = $request->getUser();

    $window_start = (time() - (60 * 15));

    // Assume daemons spend about 250ms second in overhead per task acquiring
    // leases and doing other bookkeeping. This is probably an over-estimation,
    // but we'd rather show that utilization is too high than too low.
    $lease_overhead = 0.250;

    $completed = id(new PhabricatorWorkerArchiveTask())->loadAllWhere(
      'dateModified > %d',
      $window_start);

    $failed = id(new PhabricatorWorkerActiveTask())->loadAllWhere(
      'failureTime > %d',
      $window_start);

    $usage_total = 0;
    $usage_start = PHP_INT_MAX;

    $completed_info = array();
    foreach ($completed as $completed_task) {
      $class = $completed_task->getTaskClass();
      if (empty($completed_info[$class])) {
        $completed_info[$class] = array(
          'n' => 0,
          'duration' => 0,
        );
      }
      $completed_info[$class]['n']++;
      $duration = $completed_task->getDuration();
      $completed_info[$class]['duration'] += $duration;

      // NOTE: Duration is in microseconds, but we're just using seconds to
      // compute utilization.
      $usage_total += $lease_overhead + ($duration / 1000000);
      $usage_start = min($usage_start, $completed_task->getDateModified());
    }

    $completed_info = isort($completed_info, 'n');

    $rows = array();
    foreach ($completed_info as $class => $info) {
      $rows[] = array(
        $class,
        number_format($info['n']),
        number_format((int)($info['duration'] / $info['n'])).' us',
      );
    }

    if ($failed) {
      // Add the time it takes to restart the daemons. This includes a guess
      // about other overhead of 2X.
      $usage_total += PhutilDaemonOverseer::RESTART_WAIT * count($failed) * 2;
      foreach ($failed as $failed_task) {
        $usage_start = min($usage_start, $failed_task->getFailureTime());
      }

      $rows[] = array(
        phutil_tag('em', array(), pht('Temporary Failures')),
        count($failed),
        null,
      );
    }

    $logs = id(new PhabricatorDaemonLogQuery())
      ->setViewer($user)
      ->withStatus(PhabricatorDaemonLogQuery::STATUS_ALIVE)
      ->setAllowStatusWrites(true)
      ->execute();

    $taskmasters = 0;
    foreach ($logs as $log) {
      if ($log->getDaemon() == 'PhabricatorTaskmasterDaemon') {
        $taskmasters++;
      }
    }

    if ($taskmasters && $usage_total) {
      // Total number of wall-time seconds the daemons have been running since
      // the oldest event. For very short times round up to 15s so we don't
      // render any ridiculous numbers if you reload the page immediately after
      // restarting the daemons.
      $available_time = $taskmasters * max(15, (time() - $usage_start));

      // Percentage of those wall-time seconds we can account for, which the
      // daemons spent doing work:
      $used_time = ($usage_total / $available_time);

      $rows[] = array(
        phutil_tag('em', array(), pht('Queue Utilization (Approximate)')),
        sprintf('%.1f%%', 100 * $used_time),
        null,
      );
    }

    $completed_table = new AphrontTableView($rows);
    $completed_table->setNoDataString(
      pht('No tasks have completed in the last 15 minutes.'));
    $completed_table->setHeaders(
      array(
        pht('Class'),
        pht('Count'),
        pht('Avg'),
      ));
    $completed_table->setColumnClasses(
      array(
        'wide',
        'n',
        'n',
      ));

    $completed_panel = new PHUIObjectBoxView();
    $completed_panel->setHeaderText(
      pht('Recently Completed Tasks (Last 15m)'));
    $completed_panel->appendChild($completed_table);

    $daemon_table = new PhabricatorDaemonLogListView();
    $daemon_table->setUser($user);
    $daemon_table->setDaemonLogs($logs);

    $daemon_panel = new PHUIObjectBoxView();
    $daemon_panel->setHeaderText(pht('Active Daemons'));
    $daemon_panel->appendChild($daemon_table);


    $tasks = id(new PhabricatorWorkerActiveTask())->loadAllWhere(
      'leaseOwner IS NOT NULL');

    $tasks_table = $this->renderTasksTable(
      $tasks,
      pht('No tasks are leased by workers.'));

    $leased_panel = id(new PHUIObjectBoxView())
      ->setHeaderText(pht('Leased Tasks'))
      ->appendChild($tasks_table);

    $task_table = new PhabricatorWorkerActiveTask();
    $queued = queryfx_all(
      $task_table->establishConnection('r'),
      'SELECT taskClass, count(*) N FROM %T GROUP BY taskClass
        ORDER BY N DESC',
      $task_table->getTableName());

    $rows = array();
    foreach ($queued as $row) {
      $rows[] = array(
        $row['taskClass'],
        number_format($row['N']),
      );
    }

    $queued_table = new AphrontTableView($rows);
    $queued_table->setHeaders(
      array(
        pht('Class'),
        pht('Count'),
      ));
    $queued_table->setColumnClasses(
      array(
        'wide',
        'n',
      ));
    $queued_table->setNoDataString(pht('Task queue is empty.'));

    $queued_panel = new PHUIObjectBoxView();
    $queued_panel->setHeaderText(pht('Queued Tasks'));
    $queued_panel->appendChild($queued_table);

    $upcoming = id(new PhabricatorWorkerLeaseQuery())
      ->setLimit(10)
      ->setSkipLease(true)
      ->execute();

    $upcoming_panel = id(new PHUIObjectBoxView())
      ->setHeaderText(pht('Next In Queue'))
      ->appendChild(
        $this->renderTasksTable($upcoming, pht('Task queue is empty.')));

    $crumbs = $this->buildApplicationCrumbs();
    $crumbs->addTextCrumb(pht('Console'));

    $nav = $this->buildSideNavView();
    $nav->selectFilter('/');
    $nav->appendChild(
      array(
        $crumbs,
        $completed_panel,
        $daemon_panel,
        $queued_panel,
        $leased_panel,
        $upcoming_panel,
      ));

    return $this->buildApplicationPage(
      $nav,
      array(
        'title' => pht('Console'),
        'device' => false,
      ));
  }

  private function renderTasksTable(array $tasks, $nodata) {
    $rows = array();
    foreach ($tasks as $task) {
      $rows[] = array(
        $task->getID(),
        $task->getTaskClass(),
        $task->getLeaseOwner(),
        $task->getLeaseExpires()
          ? phutil_format_relative_time($task->getLeaseExpires() - time())
          : '-',
        $task->getPriority(),
        $task->getFailureCount(),
        phutil_tag(
          'a',
          array(
            'href' => '/daemon/task/'.$task->getID().'/',
            'class' => 'button small grey',
          ),
          pht('View Task')),
      );
    }

    $table = new AphrontTableView($rows);
    $table->setHeaders(
      array(
        pht('ID'),
        pht('Class'),
        pht('Owner'),
        pht('Expires'),
        pht('Priority'),
        pht('Failures'),
        '',
      ));
    $table->setColumnClasses(
      array(
        'n',
        'wide',
        '',
        '',
        'n',
        'n',
        'action',
      ));
    $table->setNoDataString($nodata);

    return $table;
  }

}
