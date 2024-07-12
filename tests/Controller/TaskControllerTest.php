<?php

namespace App\Tests\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DomCrawler\Link;

class TaskControllerTest extends WebTestCase
{
    public function testSomething(): void
    {
        $client = static::createClient();    
        $crawler = $client->request('GET', '/');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', "Bienvenue sur Todo List, l'application vous permettant de gérer l'ensemble de vos tâches sans effort !");

        // $client->clickLink('Créer une nouvelle tâche');
        // $this->assertResponseIsSuccessful();
        // $crawler->
        // $this->assertSelectorTextContains('h1', "Bienvenue sur Todo List, l'application vous permettant de gérer l'ensemble de vos tâches sans effort !");
    }

    public function testCreateTask(): void
    {
        $client = static::createClient();    
        $crawler = $client->request('GET', '/tasks/create');

        // $this->assertResponseIsSuccessful();

        $buttonCrawlerNode = $crawler->selectButton('Ajouter');
        $form = $buttonCrawlerNode->form();

        $client->submit($form, [
            'task[title]' => 'Titre créé',
            'task[content]' => 'contenu créé',
        ]);

        $crawler = $client->followRedirect();
        $this->assertSelectorTextContains('.alert.alert-success', 'La tâche a été bien été ajoutée.');
        $this->assertSelectorTextContains('h4', 'Titre créé');
    }

    // public function testVisitingWhileLoggedIn(): void
    // {
    //     $client = static::createClient();
    //     $userRepository = static::getContainer()->get(UserRepository::class);

    //     $testUser = $userRepository->findOneByEmail('...');

    //     $client->loginUser($testUser);

    //     $client->request('GET', '/profile');
    //     $this->assertResponseIsSuccessful();
    //     $this->assertSelectorTextContains('h1', 'Hello you');
    // }
}
