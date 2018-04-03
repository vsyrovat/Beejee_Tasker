<?php

declare(strict_types=1);

namespace Tasker\Infrastructure\Repository;

use Framework\PDO\Helpers\Order\BaseOrderBy;
use Framework\PDO\Helpers\Order\OrderBy;
use Framework\PDO\Helpers\Order\OrderByChain;
use Framework\PDO\Helpers\QueryBuilder;
use Tasker\Domain\Task;
use Tasker\Domain\TaskNotFoundException;
use Tasker\Domain\TaskRepositoryInterface;

class TaskRepository implements TaskRepositoryInterface
{
    const SORT_NAME_ASC = 'name.asc';
    const SORT_NAME_DESC = 'name.desc';
    const SORT_EMAIL_ASC = 'email.asc';
    const SORT_EMAIL_DESC = 'email.desc';
    const SORT_DONE_FIRST = 'done.first';
    const SORT_UNDONE_FIRST = 'undone.first';

    private $pdo;

    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    protected function prepareOrderBy(?string $sort): BaseOrderBy
    {
        $result = new OrderByChain();

        switch ($sort) {
            case static::SORT_NAME_ASC:
                $result->add(new OrderBy('username', 'asc'));
                break;
            case static::SORT_NAME_DESC:
                $result->add(new OrderBy('username', 'desc'));
                break;
            case static::SORT_EMAIL_ASC:
                $result->add(new OrderBy('email', 'asc'));
                break;
            case static::SORT_EMAIL_DESC:
                $result->add(new OrderBy('email', 'desc'));
                break;
            case static::SORT_DONE_FIRST:
                $result->add(new OrderBy('is_done', 'desc'));
                break;
            case static::SORT_UNDONE_FIRST:
                $result->add(new OrderBy('is_done', 'asc'));
                break;
            case null:
            case '':
                break;
            default:
                throw new \InvalidArgumentException('Unexpected sort mode: '.$sort);
        }

        $result->add(new OrderBy('created_at', 'desc'));

        return $result;
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
    public function list(string $sort = null, int $limit = null, int $offset = null): array
    {
        $queryBuilder = new QueryBuilder("SELECT * FROM `tasks` {{ORDERBY}} {{LIMIT}}");
        $queryBuilder->prepareLimit('{{LIMIT}}', $limit, $offset);
        $queryBuilder->prepareOrderBy('{{ORDERBY}}', $this->prepareOrderBy($sort));

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
