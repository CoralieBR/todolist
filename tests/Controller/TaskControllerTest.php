<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TaskControllerTest extends WebTestCase
{
    public function testCreateTask(): void
    {
        $client = static::createClient();    
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
        $crawler = $client->request('GET', '/tasks');

        $this->assertAnySelectorTextContains('a', 'Un titre de tache');
        $crawler = $client->clickLink('Un titre de tache');

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

    public function testDeleteTask(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/tasks');

        $buttonCrawlerNode = $crawler->selectButton('Supprimer');
        $client->submit($buttonCrawlerNode->form());
        $crawler = $client->followRedirect();

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('.alert.alert-success', 'La tâche a bien été supprimée.');
    }

    public function testToggleTaskDone(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/tasks');

        $this->assertAnySelectorTextContains('button', 'Marquer comme faite');

        $buttonCrawlerNode = $crawler->selectButton('Marquer comme faite');
        $client->submit($buttonCrawlerNode->form());
        $crawler = $client->followRedirect();

        $this->assertAnySelectorTextContains('button', 'Marquer non terminée');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('.alert.alert-success', 'La tâche Un titre de tache a bien été marquée comme faite.');
    }

    public function testToggleTaskNotDone(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/tasks');

        $this->assertAnySelectorTextContains('button', 'Marquer non terminée');

        $buttonCrawlerNode = $crawler->selectButton('Marquer non terminée');
        $client->submit($buttonCrawlerNode->form());
        $crawler = $client->followRedirect();

        $this->assertAnySelectorTextContains('button', 'Marquer comme faite');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('.alert.alert-success', 'La tâche Un second titre de tache a bien été marquée comme faite.');
    }
}
