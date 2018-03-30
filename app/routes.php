<?php

$app->map('/', \Tasker\Presentation\Controller\DefaultController::class.'::indexAction', '/');

$app->map('/task/add', \Tasker\Presentation\Controller\TaskController::class.'::addAction', 'task.add');

$app->map('/task/preview', \Tasker\Presentation\Controller\TaskController::class.'::previewAction', 'task.preview');


$app->map('/admin/', \Tasker\Presentation\Controller\Admin\AdminController::class.'::indexAction', 'admin');

$app->map('/admin/login', \Tasker\Presentation\Controller\Admin\AdminController::class.'::loginAction', 'admin.login');

$app->map('/admin/logout', \Tasker\Presentation\Controller\Admin\AdminController::class.'::logoutAction', 'admin.logout');
