<?php

declare(strict_types=1);

namespace Tester\Presentation\Controller;

use Framework\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TaskController
{
    public static function addAction(Request $request, Application $app)
    {
        return $app->render('Task/add.twig');
    }
}
