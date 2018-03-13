<?php

$app->map('/', \Tester\Presentation\Controller\DefaultController::class.'::indexAction', '/');

$app->map('/task/add', \Tester\Presentation\Controller\TaskController::class.'::addAction', 'task.add');
