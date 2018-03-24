<?php

declare(strict_types=1);

namespace Tasker\Application\Task;

use Tasker\Domain\TaskRepositoryInterface;

class CountTasksUseCase
{
    private $taskRepository;

    public function __construct(TaskRepositoryInterface $taskRepository)
    {
        $this->taskRepository = $taskRepository;
    }

    public function run()
    {
        return $this->taskRepository->count();
    }
}
