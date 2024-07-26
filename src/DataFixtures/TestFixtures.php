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

        $task2 = new Task(
            'Un second titre de tache',
            'Un contenu tout aussi explicite que pour la première tâche.'
        );
        $task2->toggle(true);
        $manager->persist($task2);

        $user = new User();
        $user->setUsername('Burtin');
        $user->setPassword('pass');
        $user->setEmail('burtin@mail.com');
        $manager->persist($user);

        $manager->flush();
    }
}
