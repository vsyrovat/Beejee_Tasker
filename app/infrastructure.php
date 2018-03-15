<?php

$app['app.infrastructure.pdo'] = function($app) {
    if (!is_dir(dirname(APP_PDO_SQLITE_FILE))) {
        mkdir(dirname(APP_PDO_SQLITE_FILE));
    }
    return new \PDO('sqlite:'.APP_PDO_SQLITE_FILE);
};

$app['app.infrastructure.task_repository'] = function($app) {
    return new \Tasker\Infrastructure\Repository\TaskRepository($app['app.infrastructure.pdo']);
};
