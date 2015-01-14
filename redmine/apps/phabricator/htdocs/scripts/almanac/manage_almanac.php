#!/usr/bin/env php
<?php

$root = dirname(dirname(dirname(__FILE__)));
require_once $root.'/scripts/__init_script__.php';

$args = new PhutilArgumentParser($argv);
$args->setTagline('manage host directory');
$args->setSynopsis(<<<EOSYNOPSIS
**almanac** __commmand__ [__options__]
    Manage Almanac stuff. NEW AND EXPERIMENTAL.

EOSYNOPSIS
);
$args->parseStandardArguments();

$workflows = id(new PhutilSymbolLoader())
  ->setAncestorClass('AlmanacManagementWorkflow')
  ->loadObjects();
$workflows[] = new PhutilHelpArgumentWorkflow();
$args->parseWorkflows($workflows);
