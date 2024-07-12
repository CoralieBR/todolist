<?php

namespace App\DataFixtures;

use App\Entity\Task;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class TestFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $task = new Task(
            'Un titre de tache',
            'Un contenu de tâche qui décrit parfaitement ce qu\'il faut faire'
        );
        $manager->persist($task);

        // $user = new User();

        $manager->flush();
    }
}
