<?php

declare(strict_types=1);

namespace Tasker\Presentation\Controller;

use Framework\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController
{
    public static function indexAction(Request $request, Application $app)
    {
        return $app->render('Default/index.twig');
    }
}