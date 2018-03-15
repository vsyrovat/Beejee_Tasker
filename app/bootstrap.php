<?php

require_once 'autoload.php';
require_once 'config.php';

$app = new \Framework\Application();

$app['debug'] = defined('DEBUG') && DEBUG;

$twig = (new \Framework\TwigFactory($app->getUrlGenerator()))
    ->createTwig([APP_ROOT.'/src/Tasker/Presentation/View'], TWIG_CACHE_DIR, $app['debug']);
$app->registerTwig($twig);

$formFactory = (new \Framework\FormFactoryFactory($app))
    ->createFormFactory($twig);
$app->registerFormFactory($formFactory);

require_once 'routes.php';
require_once 'use_cases.php';
require_once 'infrastructure.php';
