<?php

namespace Tasker\Infrastructure\Repository;

use Tasker\Domain\Task;
use Tasker\Domain\TaskRepositoryInterface;

class TaskRepository implements TaskRepositoryInterface
{
    private $pdo;

    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function add(Task $task): Task
    {
        // TODO: Implement add() method.

        return new Task(null, null, null, null);
    }

    public function list(): array
    {
        return [];
    }
}
