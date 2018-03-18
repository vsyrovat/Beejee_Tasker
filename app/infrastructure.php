<?php

$app['app.infrastructure.pdo'] = function($app) {
    $pdo = new \PDO(APP_DB_PDO_DSN, APP_DB_USER, APP_DB_PASSWORD);
    $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    return $pdo;
};

$app['app.infrastructure.task_repository'] = function($app) {
    return new \Tasker\Infrastructure\Repository\TaskRepository($app['app.infrastructure.pdo']);
};
