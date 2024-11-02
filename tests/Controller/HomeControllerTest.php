<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class HomeControllerTest extends WebTestCase
{
    public function testHomePageIsAccessible(): void
    {
        $client = static::createClient();
        $client->request('GET', '/');  // Simulating a request to the home page

        $this->assertResponseIsSuccessful();  // Asserting the response is 200 OK
        $this->assertSelectorTextContains('h2', 'Photographe');  // Adjust 'h1' and 'Home' as necessary to match your template
    }
}
