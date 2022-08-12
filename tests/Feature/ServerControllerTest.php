<?php

namespace App\Tests\Feature;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ServerControllerTest extends WebTestCase
{
    public function testDatabaseServersRoute(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/servers');

        $this->assertResponseIsSuccessful();
    }

    public function testInMemoryServersRoute(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/servers-inmemory');

        $this->assertResponseIsSuccessful();
    }
}
