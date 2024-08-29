<?php

namespace App\Tests\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class LoginControllerTest extends WebTestCase
{
    public function testLogin(): void
    {
        // Denied - Can't login with invalid email address.
        $client = static::createClient();
        $client->request('GET', '/connexion');
        self::assertResponseIsSuccessful();

        $client->submitForm('Se connecter', [
            '_username' => 'doesNotExist@mail.com',
            '_password' => 'pass',
        ]);

        self::assertResponseRedirects('/connexion');
        $client->followRedirect();

        // Ensure we do not reveal if the user exists or not.
        self::assertSelectorTextContains('.alert-danger', 'Invalid credentials.');

        // Denied - Can't login with invalid password.
        $client->request('GET', '/connexion');
        self::assertResponseIsSuccessful();

        $client->submitForm('Se connecter', [
            '_username' => 'user@mail.com',
            '_password' => 'bad-password',
        ]);

        self::assertResponseRedirects('/connexion');
        $client->followRedirect();

        // Ensure we do not reveal the user exists but the password is wrong.
        self::assertSelectorTextContains('.alert-danger', 'Invalid credentials.');

        // Success - Login with valid credentials is allowed.
        $client->submitForm('Se connecter', [
            '_username' => 'user@mail.com',
            '_password' => 'pass',
        ]);

        self::assertSelectorNotExists('.alert-danger');
        $client->followRedirect();
        self::assertResponseIsSuccessful();
    }

    public function logout(): void
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('user@mail.com');
        $client->loginUser($testUser);

        $client->request('GET', '/deconnexion');
        $client->followRedirect();
        $this->assertResponseIsSuccessful();
    }
}
