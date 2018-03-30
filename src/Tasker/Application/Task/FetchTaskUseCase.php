<?php

declare(strict_types=1);

namespace Tasker\Application\Task;

use Tasker\Domain\Task;
use Tasker\Domain\TaskRepositoryInterface;

class FetchTaskUseCase
{
    private $taskRepository;

    public function __construct(TaskRepositoryInterface $taskRepository)
    {
        $this->taskRepository = $taskRepository;
    }

    public function run(int $id): Task
    {
        return $this->taskRepository->findById($id);
    }
}
