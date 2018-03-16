<?php

namespace Tasker\Domain;

interface TaskRepositoryInterface
{
    public function add(Task $task): Task;

    /**
     * @return Task[]
     */
    public function list(): array;
}