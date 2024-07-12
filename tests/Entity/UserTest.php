<?php

namespace App\Tests\Entity;

use App\Entity\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function testDefaults()
    {
        $user = new User();

        $user->setUsername('Prénom');
        $this->assertSame('Prénom', $user->getUsername());

        $user->setEmail('email');
        $this->assertSame('email', $user->getEmail());
        
    }
}
