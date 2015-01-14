<?php

final class PhabricatorStorageManagementAdjustWorkflow
  extends PhabricatorStorageManagementWorkflow {

  public function didConstruct() {
    $this
      ->setName('adjust')
      ->setExamples('**adjust** [__options__]')
      ->setSynopsis(
        pht(
          'Make schemata adjustments to correct issues with characters sets, '.
          'collations, and keys.'))
      ->setArguments(
        array(
          array(
            'name' => 'unsafe',
            'help' => pht(
              'Permit adjustments which truncate data. This option may '.
              'destroy some data, but the lost data is usually not '.
              'important (most commonly, the ends of very long object '.
              'titles).'),
          ),
        ));
  }

  public function execute(PhutilArgumentParser $args) {
    $force = $args->getArg('force');
    $unsafe = $args->getArg('unsafe');
    $dry_run = $args->getArg('dryrun');

    $this->requireAllPatchesApplied();
    return $this->adjustSchemata($force, $unsafe, $dry_run);
  }

  private function requireAllPatchesApplied() {
    $api = $this->getAPI();
    $applied = $api->getAppliedPatches();

    if ($applied === null) {
      throw new PhutilArgumentUsageException(
        pht(
          'You have not initialized the database yet. You must initialize '.
          'the database before you can adjust schemata. Run `storage upgrade` '.
          'to initialize the database.'));
    }

    $applied = array_fuse($applied);

    $patches = $this->getPatches();
    $patches = mpull($patches, null, 'getFullKey');
    $missing = array_diff_key($patches, $applied);

    if ($missing) {
      throw new PhutilArgumentUsageException(
        pht(
          'You have not applied all available storage patches yet. You must '.
          'apply all available patches before you can adjust schemata. '.
          'Run `storage status` to show patch status, and `storage upgrade` '.
          'to apply missing patches.'));
    }
  }

}
