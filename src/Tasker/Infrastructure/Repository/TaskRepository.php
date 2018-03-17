<?php

namespace Tasker\Infrastructure\Repository;

use Tasker\Domain\Task;
use Tasker\Domain\TaskRepositoryInterface;

class TaskRepository implements TaskRepositoryInterface
{
    private $pdo;

    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function add(Task $task): Task
    {
        // TODO: Implement add() method.

        return new Task(null, null, null, null);
    }

    /**
     * @return Task[]
     */
    public function list(): array
    {
        $statement = $this->pdo->prepare("SELECT * FROM `tasks`");
        $statement->execute();

        $result = [];
        while ($row = $statement->fetch(\PDO::FETCH_ASSOC)) {
            $result[] = $this->reconstitute($row);
        }
        return $result;
    }

    protected function reconstitute(array $data): Task
    {
        $task = new Task($data['username'], $data['email'], $data['text'], $data['image']);

        $rpId = new \ReflectionProperty(get_class($task), 'id');
        $rpId->setAccessible(true);
        $rpId->setValue($task, intval($data['id']));

        $rpCreatedAt = new \ReflectionProperty(get_class($task), 'createdAt');
        $rpCreatedAt->setAccessible(true);
        $rpCreatedAt->setValue($task, new \DateTimeImmutable($data['created_at'], new \DateTimeZone('UTC')));

        return $task;
    }
}
