<?php

namespace Tasker\Application\Task;

use Tasker\Domain\Task;
use Tasker\Domain\TaskRepositoryInterface;

class CreateTaskUseCase
{
    private $taskRepository;

    public function __construct(TaskRepositoryInterface $taskRepository)
    {
        $this->taskRepository = $taskRepository;
    }

    public function run($userName, $email, $text, $image): Task
    {
        $task = new Task($userName, $email, $text, $image);

        $this->taskRepository->add($task);

        return $task;
    }
}
