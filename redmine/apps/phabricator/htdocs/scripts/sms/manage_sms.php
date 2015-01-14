#!/usr/bin/env php
<?php

$root = dirname(dirname(dirname(__FILE__)));
require_once $root.'/scripts/__init_script__.php';

$args = new PhutilArgumentParser($argv);
$args->setTagline('manage SMS');
$args->setSynopsis(<<<EOSYNOPSIS
**sms** __command__ [__options__]
    Manage Phabricator SMS stuff.

EOSYNOPSIS
  );
$args->parseStandardArguments();

$workflows = id(new PhutilSymbolLoader())
  ->setAncestorClass('PhabricatorSMSManagementWorkflow')
  ->loadObjects();
$workflows[] = new PhutilHelpArgumentWorkflow();
$args->parseWorkflows($workflows);
