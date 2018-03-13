<?php

namespace Tester\Domain;

class Task
{
    private $createdAt;
    private $userName;
    private $email;
    private $text;
    private $image;
    private $done = false;

    public function __construct($userName, $email, $text, $image)
    {
        $this->createdAt = new \DateTimeImmutable;
        $this->userName = $userName;
        $this->email = $email;
        $this->text = $text;
        $this->image = $image;
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
     * @return bool
     */
    public function isDone(): bool
    {
        return $this->done;
    }
}
