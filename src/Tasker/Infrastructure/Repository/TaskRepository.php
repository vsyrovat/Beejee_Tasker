<?php

declare(strict_types=1);

namespace Tasker\Infrastructure\Repository;

use Framework\PDO\Helpers\QueryBuilder;
use Tasker\Domain\Task;
use Tasker\Domain\TaskNotFoundException;
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
        $statement = $this->pdo->prepare("INSERT INTO `tasks` 
(`created_at`, `username`, `email`, `text`, `image`) 
VALUES 
(:createdAt, :userName, :email, :text, :image)");
        $statement->execute([
            'createdAt' => $task->getCreatedAt()->setTimezone(new \DateTimeZone('UTC'))->format('Y-m-d H:i:s'),
            'userName' => $task->getUserName(),
            'email' => $task->getEmail(),
            'text' => $task->getText(),
            'image' => $task->getImage(),
        ]);

        $insertId = $this->pdo->lastInsertId();

        $returnTask = clone $task;
        $rpId = new \ReflectionProperty(get_class($returnTask), 'id');
        $rpId->setAccessible(true);
        $rpId->setValue($returnTask, intval($insertId));

        return $returnTask;
    }

    /**
     * {@inheritdoc}
     */
    public function list(int $limit = null, int $offset = null): array
    {
        $queryBuilder = new QueryBuilder("SELECT * FROM `tasks` ORDER BY `id` DESC {{LIMIT}}");
        $queryBuilder->prepareLimit('{{LIMIT}}', $limit, $offset);

        /* @var $statement \Framework\PDO\PDOStatement */
        $statement = $this->pdo->prepare($queryBuilder->getQuery());
        $statement->bindParamTypes($queryBuilder->getParamTypes());
        $statement->execute($queryBuilder->getParams());

        $result = [];
        while ($row = $statement->fetch(\PDO::FETCH_ASSOC)) {
            $result[] = $this->reconstitute($row);
        }

        return $result;
    }

    public function count(): int
    {
        $statement = $this->pdo->query("SELECT COUNT(*) FROM `tasks`");
        $statement->execute();
        return intval($statement->fetch()[0]);
    }

    public function updateTask(Task $task): void
    {
        $statement = $this->pdo->prepare("UPDATE `tasks`
SET
  `username`=:username,
  `email`=:email,
  `text`=:text,
  `image`=:image,
  `is_done`=:isDone
WHERE `id`=:id");
        $statement->execute([
            'username' => $task->getUserName(),
            'email' => $task->getEmail(),
            'text' => $task->getText(),
            'image' => $task->getImage(),
            'isDone' => $task->isDone() ? 1 : 0,
            'id' => $task->getId(),
        ]);
    }

    public function findById(int $id): Task
    {
        $statement = $this->pdo->prepare("SELECT * FROM `tasks` WHERE `id`=:id");
        $statement->execute(['id' => $id]);

        if ($statement->rowCount() == 0) {
            throw new TaskNotFoundException('id='.$id);
        }

        $data = $statement->fetch(\PDO::FETCH_ASSOC);

        return $this->reconstitute($data);
    }

    protected function reconstitute(array $data): Task
    {
        $task = new Task($data['username'], $data['email'], $data['text'], $data['image'], $data['is_done'] == '1');

        $rpId = new \ReflectionProperty(get_class($task), 'id');
        $rpId->setAccessible(true);
        $rpId->setValue($task, intval($data['id']));

        $rpCreatedAt = new \ReflectionProperty(get_class($task), 'createdAt');
        $rpCreatedAt->setAccessible(true);
        $rpCreatedAt->setValue($task, new \DateTimeImmutable($data['created_at'], new \DateTimeZone('UTC')));

        return $task;
    }
}
