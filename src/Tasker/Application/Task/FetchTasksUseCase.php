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

    public function run(string $sort = null, int $limit = null, int $offset = null): array
    {
        if (!empty($sort) &&
            !in_array($sort, ['name.asc', 'name.desc', 'email.asc', 'email.desc', 'done.first', 'undone.first'])) {
            throw new \InvalidArgumentException('Unknown sort param: '.$sort);
        }

        return $this->taskRepository->list($sort, $limit, $offset);
    }
}
