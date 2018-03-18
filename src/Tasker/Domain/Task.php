<?php

namespace Tasker\Domain;

class Task
{
    private $id;
    private $createdAt;
    private $userName;
    private $email;
    private $text;
    private $image;
    private $done = false;

    public function __construct(string $userName, string $email, string $text, string $image = null)
    {
        $this->createdAt = new \DateTimeImmutable;
        $this->userName = $userName;
        $this->email = $email;
        $this->text = $text;
        $this->image = $image;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return \DateTimeImmutable
     */
    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    /**
     * @return mixed
     */
    public function getUserName()
    {
        return $this->userName;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @return mixed
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @return mixed
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param string $image
     */
    public function setImage(string $image): void
    {
        $this->image = $image;
    }

    /**
     * @return bool
     */
    public function isDone(): bool
    {
        return $this->done;
    }
}
