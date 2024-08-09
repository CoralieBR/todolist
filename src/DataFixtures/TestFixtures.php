<?php

namespace App\DataFixtures;

use App\Entity\{Task, User};
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class TestFixtures extends Fixture
{
    public const OWNER_USER_REFERENCE = 'owner-user';

    public function load(ObjectManager $manager): void
    {
        $user1 = new User();
        $user1->setName('User');
        $user1->setPassword('pass');
        $user1->setEmail('user@mail.com');
        $user1->setRoles(['ROLE_USER']);
        $manager->persist($user1);

        $user2 = new User();
        $user2->setName('Admin');
        $user2->setPassword('pass');
        $user2->setEmail('admin@mail.com');
        $user2->setRoles(['ROLE_ADMIN']);
        $manager->persist($user2);

        $user3 = new User();
        $user3->setName('Owner');
        $user3->setPassword('pass');
        $user3->setEmail('owner@mail.com');
        $user3->setRoles(['ROLE_USER']);
        $manager->persist($user3);
        $this->addReference(self::OWNER_USER_REFERENCE, $user3);

        $task = new Task(
            'Un titre de tache',
            'Un contenu de tâche qui décrit parfaitement ce qu\'il faut faire'
        );
        $task->setUser($this->getReference(self::OWNER_USER_REFERENCE));
        $manager->persist($task);

        $task2 = new Task(
            'Un second titre de tache',
            'Un contenu tout aussi explicite que pour la première tâche.'
        );
        $task2->toggle(true);
        $task2->setUser($this->getReference(self::OWNER_USER_REFERENCE));
        $manager->persist($task2);

        $manager->flush();
    }
}
