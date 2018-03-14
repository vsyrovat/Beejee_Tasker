<?php

declare(strict_types=1);

define('DEBUG', true);

require_once __DIR__ . '/../app/bootstrap.php';

$app = new \Framework\Application();

$twig = (new \Framework\TwigFactory($app->getUrlGenerator()))
    ->createTwig([APP_ROOT.'/src/Tester/Presentation/View'], TWIG_CACHE_DIR, DEBUG);
$app->registerTwig($twig);

$formFactory = (new \Framework\FormFactoryFactory($app))
    ->createFormFactory($twig);
$app->registerFormFactory($formFactory);


require __DIR__ . '/../app/routes.php';

$app->run();
