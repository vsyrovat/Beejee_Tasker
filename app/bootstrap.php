<?php

require_once 'autoload.php';
require_once 'config.php';

$app = new \Framework\Application();

$app['debug'] = defined('DEBUG') && DEBUG;

$app['locale'] = 'en';

$app->register(new \Framework\Twig\TwigServiceProvider(), [
    'twig.path' => APP_ROOT.'/src/Tasker/Presentation/View',
    'twig.options' => [
        'cache' => TWIG_CACHE_DIR,
    ],
]);

$app->register(new \Framework\Translator\TranslatorServiceProvider());

$app->register(new \Framework\Form\FormServiceProvider());

$app->register(new \Framework\Pagination\PaginationServiceProvider());

$app->register(new \Framework\Security\SecurityServiceProvider(), [
    'auth.users' => [
        'admin' => [['ADMIN'], @file_get_contents(APP_ADMIN_PASSWORD_STORAGE_FILE)]
    ],
]);


require_once 'routes.php';
require_once 'use_cases.php';
require_once 'infrastructure.php';
