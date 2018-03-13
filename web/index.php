<?php

declare(strict_types=1);

define('DEBUG', true);

require_once __DIR__ . '/../app/bootstrap.php';

$app = new \Framework\Application(new Twig_Environment(
    new Twig_Loader_Filesystem(__DIR__.'/../src/Tester/Presentation/View'),
    ['cache' => DEBUG ? false : TWIG_CACHE_DIR]
));

require __DIR__ . '/../app/routes.php';

$app->run();
