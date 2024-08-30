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
        $this->createUser($manager, 'User', 'user@mail.com', ['ROLE_USER']);
        $this->createUser($manager, 'Admin', 'admin@mail.com', ['ROLE_ADMIN']);
        $user3 = $this->createUser($manager, 'Owner', 'owner@mail.com', ['ROLE_USER']);
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

    public function createUser(ObjectManager $manager, string $name, string $email, array $role): User
    {
        $user = new User();
        $user->setName($name);
        $user->setPassword('pass');
        $user->setEmail($email);
        $user->setRoles($role);
        $manager->persist($user);

        return $user;
    }
}
