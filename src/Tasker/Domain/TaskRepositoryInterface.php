<?php

declare(strict_types=1);

namespace Tasker\Domain;

interface TaskRepositoryInterface
{
    public function add(Task $task): Task;

    /**
     * @param string $sort
     * @param int $limit
     * @param int $offset
     * @return Task[]
     */
    public function list(string $sort = null, int $limit = null, int $offset = null): array;

    public function count(): int;

    public function updateTask(Task $task): void;

    /**
     * @param int $id
     * @return Task
     * @throws TaskNotFoundException
     */
    public function findById(int $id): Task;
}
