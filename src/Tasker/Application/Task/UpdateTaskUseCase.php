<?php

declare(strict_types=1);

namespace Tasker\Application\Task;

use phpDocumentor\Reflection\Types\Void_;
use Tasker\Domain\Task;
use Tasker\Domain\TaskRepositoryInterface;

class UpdateTaskUseCase
{
    private $taskRepository;

    public function __construct(TaskRepositoryInterface $taskRepository)
    {
        $this->taskRepository = $taskRepository;
    }

    public function run(Task $task): void
    {
        $this->taskRepository->updateTask($task);
    }
}
