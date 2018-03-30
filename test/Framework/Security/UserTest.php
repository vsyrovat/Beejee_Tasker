<?php

namespace Framework\Security;

use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{

    public function testGetUsername()
    {
        $user = new User();
        self::assertNull($user->getUsername());

        $user = new User('anon');
        self::assertEquals('anon', $user->getUsername());
    }

    public function testIsLogged()
    {
        $user = new User();
        self::assertFalse($user->isLogged());

        $user = new User('anon');
        self::assertTrue($user->isLogged());
    }

    public function testIsGrant()
    {
        $user = new User();
        self::assertFalse($user->isGrant('ANY'));

        $user = new User('username', ['ANY']);
        self::assertTrue($user->isGrant('ANY'));

        $user = new User('username', ['any']);
        self::assertTrue($user->isGrant('ANY'));

        $user = new User('username', ['Any']);
        self::assertTrue($user->isGrant('any'));
    }

    public function testGetGrants()
    {
        $user = new User();
        self::assertEquals([], $user->getGrants());

        $user = new User('vasya', ['Any', 'beNy']);
        self::assertEquals(['ANY', 'BENY'], $user->getGrants());
    }
}
