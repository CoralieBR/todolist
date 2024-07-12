<?php

namespace App\Tests\Entity;

use App\Entity\Task;
use PHPUnit\Framework\TestCase;

class TaskTest extends TestCase
{
    public function testDefaults()
    {
        $task = new Task('titre', 'contenu');

        $this->assertSame('titre', $task->getTitle());
        $task->setTitle('tiiitre');
        $this->assertSame('tiiitre', $task->getTitle());

        $this->assertSame('contenu', $task->getContent());
        $task->setContent('conteeeeenu');
        $this->assertSame('conteeeeenu', $task->getContent());
        
    }
}
