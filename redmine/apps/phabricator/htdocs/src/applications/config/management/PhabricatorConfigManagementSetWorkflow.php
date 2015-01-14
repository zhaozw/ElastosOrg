<?php

final class PhabricatorConfigManagementSetWorkflow
  extends PhabricatorConfigManagementWorkflow {

  protected function didConstruct() {
    $this
      ->setName('set')
      ->setExamples('**set** __key__ __value__')
      ->setSynopsis(pht('Set a local configuration value.'))
      ->setArguments(
        array(
          array(
            'name'  => 'database',
            'help'  => pht('Update configuration in the database instead of '.
            'in local configuration.'),
          ),
          array(
            'name'      => 'args',
            'wildcard'  => true,
          ),
        ));
  }

  public function execute(PhutilArgumentParser $args) {
    $console = PhutilConsole::getConsole();
    $argv = $args->getArg('args');
    if (count($argv) == 0) {
      throw new PhutilArgumentUsageException(pht(
        'Specify a configuration key and a value to set it to.'));
    }

    $key = $argv[0];

    if (count($argv) == 1) {
      throw new PhutilArgumentUsageException(pht(
        "Specify a value to set the key '%s' to.",
        $key));
    }

    $value = $argv[1];

    if (count($argv) > 2) {
      throw new PhutilArgumentUsageException(pht(
        'Too many arguments: expected one key and one value.'));
    }

    $options = PhabricatorApplicationConfigOptions::loadAllOptions();
    if (empty($options[$key])) {
      throw new PhutilArgumentUsageException(pht(
        "No such configuration key '%s'! Use `config list` to list all ".
        "keys.",
        $key));
    }

    $option = $options[$key];

    $type = $option->getType();
    switch ($type) {
      case 'string':
      case 'class':
      case 'enum':
        $value = (string)$value;
        break;
      case 'int':
        if (!ctype_digit($value)) {
          throw new PhutilArgumentUsageException(pht(
            "Config key '%s' is of type '%s'. Specify an integer.",
            $key,
            $type));
        }
        $value = (int)$value;
        break;
      case 'bool':
        if ($value == 'true') {
          $value = true;
        } else if ($value == 'false') {
          $value = false;
        } else {
          throw new PhutilArgumentUsageException(pht(
            "Config key '%s' is of type '%s'. ".
            "Specify 'true' or 'false'.",
            $key,
            $type));
        }
        break;
      default:
        $value = json_decode($value, true);
        if (!is_array($value)) {
          throw new PhutilArgumentUsageException(pht(
            "Config key '%s' is of type '%s'. Specify it in JSON.",
            $key,
            $type));
        }
        break;
    }
    $use_database = $args->getArg('database');
    if ($option->getLocked() && $use_database) {
      throw new PhutilArgumentUsageException(pht(
        "Config key '%s' is locked and can only be set in local ".
        'configuration.',
        $key));
    }

    try {
      $option->getGroup()->validateOption($option, $value);
    } catch (PhabricatorConfigValidationException $validation) {
      // Convert this into a usage exception so we don't dump a stack trace.
      throw new PhutilArgumentUsageException($validation->getMessage());
    }

    if ($use_database) {
      $config_type = 'database';
      PhabricatorConfigEditor::storeNewValue(
        $this->getViewer(),
        id(new PhabricatorConfigEntry())
          ->loadOneWhere('namespace = %s AND key = %s', 'default', $key),
        $value,
        PhabricatorContentSource::newConsoleSource());
    } else {
      $config_type = 'local';
      id(new PhabricatorConfigLocalSource())
        ->setKeys(array($key => $value));
    }

    $console->writeOut(
      pht("Set '%s' in %s configuration.", $key, $config_type)."\n");
  }

}
