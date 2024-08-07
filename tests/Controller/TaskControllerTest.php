<?php

namespace App\Tests\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TaskControllerTest extends WebTestCase
{
    public function testCreateTask(): void
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('admin@mail.com');
        $client->loginUser($testUser);

        $crawler = $client->request('GET', '/tasks/create');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('button', 'Ajouter');

        $buttonCrawlerNode = $crawler->selectButton('Ajouter');
        $form = $buttonCrawlerNode->form();

        $client->submit($form, [
            'task[title]' => 'Titre créé',
            'task[content]' => 'contenu créé',
        ]);

        $crawler = $client->followRedirect();
        $this->assertSelectorTextContains('.alert.alert-success', 'La tâche a bien été ajoutée.');
        $this->assertAnySelectorTextContains('h4', 'Titre créé');
    }

    public function testUpdateTask(): void
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('admin@mail.com');
        $client->loginUser($testUser);

        $crawler = $client->request('GET', '/tasks');

        $crawler = $client->clickLink('Modifier');

        $this->assertResponseIsSuccessful();

        $buttonCrawlerNode = $crawler->selectButton('Modifier');
        $form = $buttonCrawlerNode->form();

        $client->submit($form, [
            'task[title]' => 'Titre modifié',
            'task[content]' => 'contenu modifié',
        ]);

        $crawler = $client->followRedirect();
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('.alert.alert-success', 'La tâche a bien été modifiée.');
        $this->assertAnySelectorTextContains('h4', 'Titre modifié');
    }

    public function testUserCannotDeleteAnonymous(): void
    {
        $client = static::createClient();
        $client->request('GET', '/tasks');
        $this->assertAnySelectorTextNotContains('a', 'Supprimer');
    }
    
    public function testAdminCanDeleteAnonymous(): void
    {
        $client = static::createClient();

        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('admin@mail.com');
        $client->loginUser($testUser);

        $client->request('GET', '/tasks');
        $client->clickLink('Supprimer');
        $client->followRedirect();
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('.alert.alert-success', 'La tâche a bien été supprimée.');
    }
    
    public function testUserCannotDeleteTask(): void
    {
        $client = static::createClient();

        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('user@mail.com');
        $client->loginUser($testUser);

        $client->request('GET', '/tasks');
        $this->assertAnySelectorTextNotContains('a', 'Supprimer');
    }
    
    public function testOwnerCanDeleteTask(): void
    {
        $client = static::createClient();

        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('owner@mail.com');
        $client->loginUser($testUser);

        $client->request('GET', '/tasks');
        $client->clickLink('Supprimer');
        $client->followRedirect();
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('.alert.alert-success', 'La tâche a bien été supprimée.');
    }

    public function testToggleTaskDone(): void
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('admin@mail.com');
        $client->loginUser($testUser);

        $client->request('GET', '/tasks');
        $client->clickLink('Marquer comme faite');
        $client->followRedirect();

        $this->assertResponseIsSuccessful();
        $this->assertAnySelectorTextContains('a', 'Marquer non terminée');
        $this->assertSelectorTextContains('.alert.alert-success', 'La tâche Un titre de tache a bien été marquée comme faite.');
    }

    public function testToggleTaskNotDone(): void
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('admin@mail.com');
        $client->loginUser($testUser);

        $client->request('GET', '/tasks');
        $client->clickLink('Marquer non terminée');
        $client->followRedirect();

        $this->assertResponseIsSuccessful();
        $this->assertAnySelectorTextContains('a', 'Marquer comme faite');
        $this->assertSelectorTextContains('.alert.alert-success', 'La tâche Un second titre de tache a bien été marquée comme faite.');
    }
}
