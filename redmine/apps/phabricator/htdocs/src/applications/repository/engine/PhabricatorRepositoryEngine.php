<?php

/**
 * @task config     Configuring Repository Engines
 * @task internal   Internals
 */
abstract class PhabricatorRepositoryEngine {

  private $repository;
  private $verbose;

  /**
   * @task config
   */
  public function setRepository(PhabricatorRepository $repository) {
    $this->repository = $repository;
    return $this;
  }


  /**
   * @task config
   */
  protected function getRepository() {
    if ($this->repository === null) {
      throw new Exception('Call setRepository() to provide a repository!');
    }

    return $this->repository;
  }


  /**
   * @task config
   */
  public function setVerbose($verbose) {
    $this->verbose = $verbose;
    return $this;
  }


  /**
   * @task config
   */
  public function getVerbose() {
    return $this->verbose;
  }


  public function getViewer() {
    return PhabricatorUser::getOmnipotentUser();
  }

  /**
   * Verify that the "origin" remote exists, and points at the correct URI.
   *
   * This catches or corrects some types of misconfiguration, and also repairs
   * an issue where Git 1.7.1 does not create an "origin" for `--bare` clones.
   * See T4041.
   *
   * @param   PhabricatorRepository Repository to verify.
   * @return  void
   */
  protected function verifyGitOrigin(PhabricatorRepository $repository) {
    list($remotes) = $repository->execxLocalCommand(
      'remote show -n origin');

    $matches = null;
    if (!preg_match('/^\s*Fetch URL:\s*(.*?)\s*$/m', $remotes, $matches)) {
      throw new Exception(
        "Expected 'Fetch URL' in 'git remote show -n origin'.");
    }

    $remote_uri = $matches[1];
    $expect_remote = $repository->getRemoteURI();

    if ($remote_uri == 'origin') {
      // If a remote does not exist, git pretends it does and prints out a
      // made up remote where the URI is the same as the remote name. This is
      // definitely not correct.

      // Possibly, we should use `git remote --verbose` instead, which does not
      // suffer from this problem (but is a little more complicated to parse).
      $valid = false;
      $exists = false;
    } else {
      $normal_type_git = PhabricatorRepositoryURINormalizer::TYPE_GIT;

      $remote_normal = id(new PhabricatorRepositoryURINormalizer(
        $normal_type_git,
        $remote_uri))->getNormalizedPath();

      $expect_normal = id(new PhabricatorRepositoryURINormalizer(
        $normal_type_git,
        $expect_remote))->getNormalizedPath();

      $valid = ($remote_normal == $expect_normal);
      $exists = true;
    }

    if (!$valid) {
      if (!$exists) {
        // If there's no "origin" remote, just create it regardless of how
        // strongly we own the working copy. There is almost no conceivable
        // scenario in which this could do damage.
        $this->log(
          pht(
            'Remote "origin" does not exist. Creating "origin", with '.
            'URI "%s".',
            $expect_remote));
        $repository->execxLocalCommand(
          'remote add origin %P',
          $repository->getRemoteURIEnvelope());

        // NOTE: This doesn't fetch the origin (it just creates it), so we won't
        // know about origin branches until the next "pull" happens. That's fine
        // for our purposes, but might impact things in the future.
      } else {
        if ($repository->canDestroyWorkingCopy()) {
          // Bad remote, but we can try to repair it.
          $this->log(
            pht(
              'Remote "origin" exists, but is pointed at the wrong URI, "%s". '.
              'Resetting origin URI to "%s.',
              $remote_uri,
              $expect_remote));
          $repository->execxLocalCommand(
            'remote set-url origin %P',
            $repository->getRemoteURIEnvelope());
        } else {
          // Bad remote and we aren't comfortable repairing it.
          $message = pht(
            'Working copy at "%s" has a mismatched origin URI, "%s". '.
            'The expected origin URI is "%s". Fix your configuration, or '.
            'set the remote URI correctly. To avoid breaking anything, '.
            'Phabricator will not automatically fix this.',
            $repository->getLocalPath(),
            $remote_uri,
            $expect_remote);
          throw new Exception($message);
        }
      }
    }
  }




  /**
   * @task internal
   */
  protected function log($pattern /* ... */) {
    if ($this->getVerbose()) {
      $console = PhutilConsole::getConsole();
      $argv = func_get_args();
      array_unshift($argv, "%s\n");
      call_user_func_array(array($console, 'writeOut'), $argv);
    }
    return $this;
  }

}
