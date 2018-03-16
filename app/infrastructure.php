<?php

$app['app.infrastructure.pdo'] = function($app) {
    return new \PDO(APP_DB_PDO_DSN, APP_DB_USER, APP_DB_PASSWORD);
};

$app['app.infrastructure.task_repository'] = function($app) {
    return new \Tasker\Infrastructure\Repository\TaskRepository($app['app.infrastructure.pdo']);
};
