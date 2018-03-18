<?php

declare(strict_types=1);

namespace Tasker\Application\Task;

use Tasker\Domain\TaskRepositoryInterface;

class FetchTasksUseCase
{
    private $taskRepository;

    public function __construct(TaskRepositoryInterface $taskRepository)
    {
        $this->taskRepository = $taskRepository;
    }

    public function run(int $limit = null, int $offset = null): array
    {
        return $this->taskRepository->list($limit, $offset);
    }
}
