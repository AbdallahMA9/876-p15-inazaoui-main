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
        $this->assertSelectorTextContains('h2', 'Photographe');  // Adjust 'h2' to match your template
    }

    public function testGuestsPageIsAccessible(): void
    {
        $client = static::createClient();
        $client->request('GET', '/guests');  // Simulating a request to the guests page

        $this->assertResponseIsSuccessful();  // Asserting the response is 200 OK
        $this->assertSelectorTextContains('h4', 'User 1');  // Adjust as necessary
    }

    public function testGuestPageIsAccessible(): void
    {
        $client = static::createClient();

        // Simulate a user that exists and is authorized
        $client->request('GET', '/guest/1');  // Adjust the ID as necessary

        $this->assertResponseIsSuccessful();  // Asserting the response is 200 OK
        $this->assertSelectorTextContains('p', 'Description for User 1');  // Adjust as necessary
    }

    public function testPortfolioPageIsAccessible(): void
    {
        $client = static::createClient();
        $client->request('GET', '/portfolio/1');  // Simulating a request to the portfolio page with an album ID

        $this->assertResponseIsSuccessful();  // Asserting the response is 200 OK
        $this->assertSelectorTextContains('p', 'Himalaya');  // Adjust as necessary
    }

    public function testAboutPageIsAccessible(): void
    {
        $client = static::createClient();
        $client->request('GET', '/about');  // Simulating a request to the about page

        $this->assertResponseIsSuccessful();  // Asserting the response is 200 OK
        $this->assertSelectorTextContains('h2', 'Qui suis-je ?');  // Adjust as necessary
    }
}
