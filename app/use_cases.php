<?php

$app['app.use_case.create_task'] = function($app) {
    return new \Tasker\Application\Task\CreateTaskUseCase($app);
};
