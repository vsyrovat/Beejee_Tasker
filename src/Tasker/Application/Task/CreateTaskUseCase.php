<?php

namespace Tasker\Application\Task;

use Tasker\Domain\Task;
use Tasker\Domain\TaskRepositoryInterface;

class CreateTaskUseCase
{
    private $pdo;
    private $taskRepository;

    public function __construct(\PDO $pdo, TaskRepositoryInterface $taskRepository)
    {
        $this->pdo = $pdo;
        $this->taskRepository = $taskRepository;
    }

    public function run($userName, $email, $text, $image): Task
    {
        $this->pdo->beginTransaction();

        try {
            $task = new Task($userName, $email, $text, $image);

            $this->taskRepository->add($task);

            $this->pdo->commit();

            return $task;
        } catch (\PDOException $e) {
            $this->pdo->rollBack();

            throw $e;
        }
    }
}
