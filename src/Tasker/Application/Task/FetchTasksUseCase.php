<?php

namespace Tasker\Application\Task;

use Tasker\Domain\TaskRepositoryInterface;

class FetchTasksUseCase
{
    private $taskRepository;

    public function __construct(TaskRepositoryInterface $taskRepository)
    {
        $this->taskRepository = $taskRepository;
    }

    public function run()
    {
        return $this->taskRepository->list();
    }
}
