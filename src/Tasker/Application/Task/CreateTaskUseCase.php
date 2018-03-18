<?php

declare(strict_types=1);

namespace Tasker\Application\Task;

use Framework\Image\SimpleImage;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Tasker\Domain\ImageProcessException;
use Tasker\Domain\Task;
use Tasker\Domain\TaskRepositoryInterface;

class CreateTaskUseCase
{
    private $pdo;
    private $taskRepository;

    public function __construct(\PDO $pdo, TaskRepositoryInterface $taskRepository)
    {
        $this->pdo = $pdo;
        $this->taskRepository = $taskRepository;
    }

    public function run($userName, $email, $text, UploadedFile $image = null): Task
    {
        $this->pdo->beginTransaction();

        try {
            $task = new Task($userName, $email, $text);

            $task = $this->taskRepository->add($task);

            if ($image !== null && $image->isValid()) {
                try {
                    $i = new SimpleImage($image->getPathname());
                    $targetBasename = $task->getId().'.'.$i->suggestExtension();
                    $targetFile = APP_IMG_DIR.'/'.$task->getId().'/'.$targetBasename;
                    if (!is_dir(dirname($targetFile))) {
                        mkdir(dirname($targetFile), 0755 & ~umask(), true);
                    }
                    $i->bestFit(320, 240);
                    $i->toFile($targetFile);
                } catch (\Exception $e) {
                    throw new ImageProcessException($e->getMessage(), $e->getCode());
                }

                $task->setImage($targetBasename);

                $this->taskRepository->updateTask($task);
            }

            $this->pdo->commit();

            return $task;
        } catch (\PDOException $e) {
            $this->pdo->rollBack();

            throw $e;
        }
    }
}
