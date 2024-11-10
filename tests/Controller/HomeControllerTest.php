<?php

namespace App\Tests\Controller;

use App\Entity\User;
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
    
        $entityManager = $client->getContainer()->get('doctrine')->getManager();
    
        // Récupérer le guest avec l'email 'user1@example.com'
        $guest = $entityManager->getRepository(User::class)->findOneBy(['email' => 'user1@example.com']);
        
        // Vérifie si l'utilisateur existe avant de faire la requête
        $this->assertNotNull($guest, 'Le guest avec l\'email "user1@example.com" n\'existe pas.');
    
        // Simuler une requête GET pour afficher la page du guest
        $client->request('GET', '/guest/' . $guest->getId());
    
        // Vérifie que la réponse est correcte
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('p', 'Description for User 1');
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
