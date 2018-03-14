<?php

$app->map('/', \Tasker\Presentation\Controller\DefaultController::class.'::indexAction', '/');

$app->map('/task/add', \Tasker\Presentation\Controller\TaskController::class.'::addAction', 'task.add');
