<?php

namespace App\Tests\Entity;

use App\Entity\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function testDefaults()
    {
        $user = new User();

        $user->setName('Prénom');
        $this->assertSame('Prénom', $user->getName());

        $user->setEmail('email');
        $this->assertSame('email', $user->getEmail());
        
    }
}
