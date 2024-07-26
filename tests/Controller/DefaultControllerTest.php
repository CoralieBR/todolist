<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    public function testHomepageIsUp()
    {
        $client = static::createClient();
        $urlGenerator = $client->getContainer()->get('router.default');

        $client->request('GET', '/');
        $this->assertResponseIsSuccessful();
    }
}