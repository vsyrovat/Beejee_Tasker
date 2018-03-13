<?php

declare(strict_types=1);

define('DEBUG', true);

require_once __DIR__ . '/../app/bootstrap.php';

$app = new \Framework\Application();

$twig = new Twig_Environment(
    new Twig_Loader_Filesystem(__DIR__.'/../src/Tester/Presentation/View'),
    ['cache' => DEBUG ? false : TWIG_CACHE_DIR]
);
$twig->addFunction(new \Framework\Twig\Functions\Url($app->getUrlGenerator()));
$app->registerTwig($twig);

require __DIR__ . '/../app/routes.php';

$app->run();
