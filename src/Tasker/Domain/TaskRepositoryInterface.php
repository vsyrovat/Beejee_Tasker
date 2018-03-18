<?php

declare(strict_types=1);

namespace Tasker\Domain;

interface TaskRepositoryInterface
{
    public function add(Task $task): Task;

    /**
     * @param int $limit
     * @param int $offset
     * @return Task[]
     */
    public function list(int $limit = null, int $offset = null): array;

    public function updateTask(Task $task): void;
}
