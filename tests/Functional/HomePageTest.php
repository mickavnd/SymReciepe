<?php

namespace App\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class HomePageTest extends WebTestCase
{
    public function testHomePage(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        $this->assertResponseIsSuccessful();

        $recipe =$crawler->filter('.recipes .card ');
        $this->assertEquals(3, count($recipe) );


        $this->assertSelectorTextContains('h1', 'Bienvenue sur SymRecipie');
    }
}
