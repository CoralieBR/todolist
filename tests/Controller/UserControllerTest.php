<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{
    public function testCreateUser(): void
    {
        $client = static::createClient();    
        $crawler = $client->request('GET', '/users/create');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('button', 'Ajouter');

        $buttonCrawlerNode = $crawler->selectButton('Ajouter');
        $form = $buttonCrawlerNode->form();

        $client->submit($form, [
            'user[username]' => 'Coralie',
            'user[password][first]' => 'mot-de-passe',
            'user[password][second]' => 'mot-de-passe',
            'user[email]' => 'coralie@mail.com',
        ]);

        $crawler = $client->followRedirect();
        $this->assertSelectorTextContains('.alert.alert-success', "L'utilisateur a bien été ajouté.");
        $this->assertAnySelectorTextContains('td', 'Coralie');
    }

    public function testUpdateUser(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/users');

        $this->assertAnySelectorTextContains('a', 'Edit');
        $crawler = $client->clickLink('Edit');

        $this->assertResponseIsSuccessful();

        $buttonCrawlerNode = $crawler->selectButton('Modifier');
        $form = $buttonCrawlerNode->form();

        $client->submit($form, [
            'user[username]' => 'Coralie',
            'user[password][first]' => 'mot-de-passe',
            'user[password][second]' => 'mot-de-passe',
            'user[email]' => 'coralie@mail.com',
        ]);

        $crawler = $client->followRedirect();
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('.alert.alert-success', "L'utilisateur a bien été modifié");
        $this->assertAnySelectorTextContains('td', 'Coralie');
    }
}
