<?php

require_once __DIR__.'/../vendor/autoload.php';

$loader = new \Composer\Autoload\ClassLoader();

$loader->add('Framework', __DIR__.'/../src');
$loader->add('Tester', __DIR__.'/../src');

$loader->register();