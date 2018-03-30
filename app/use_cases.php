<?php

$app['app.use_case.create_task'] = function ($app) {
    return new \Tasker\Application\Task\CreateTaskUseCase(
        $app['app.infrastructure.pdo'],
        $app['app.infrastructure.task_repository']
    );
};

$app['app.use_case.fetch_tasks'] = function ($app) {
    return new \Tasker\Application\Task\FetchTasksUseCase($app['app.infrastructure.task_repository']);
};

$app['app.use_case.count_tasks'] = function ($app) {
    return new \Tasker\Application\Task\CountTasksUseCase($app['app.infrastructure.task_repository']);
};

$app['app.use_case.fetch_task'] = function ($app) {
    return new \Tasker\Application\Task\FetchTaskUseCase($app['app.infrastructure.task_repository']);
};

$app['app.use_case.update_task'] = function ($app) {
    return new \Tasker\Application\Task\UpdateTaskUseCase($app['app.infrastructure.task_repository']);
};
