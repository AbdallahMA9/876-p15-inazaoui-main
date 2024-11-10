<?php 

namespace App\Tests\Controller\Admin;

use App\Entity\Album;
use App\Entity\Media;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class MediaControllerTest extends WebTestCase
{
    private KernelBrowser $client;


    protected function setUp(): void
    {
        $this->client = static::createClient();

        // Rechercher un utilisateur administrateur de test
        $entityManager = $this->client->getContainer()->get('doctrine')->getManager();
        $adminUser = $entityManager->getRepository(User::class)->findOneBy(['email' => 'admin@example.com']);

        // Vérifiez si l'utilisateur existe dans la base de données de test
        $this->assertNotNull($adminUser, 'Le compte administrateur n\'existe pas dans la base de données de test.');

        // Connexion en tant qu'administrateur
        $this->client->loginUser($adminUser);
    }

    public function testIndexAccess(): void
    {
        $this->client->request('GET', '/admin/media');
        $this->assertResponseIsSuccessful();
    }

    public function testDeleteMedia(): void
    {
        $entityManager = $this->client->getContainer()->get('doctrine')->getManager();

        // Récupérer le média avec le titre "Annecy"
        $media = $entityManager->getRepository(Media::class)->findOneBy(['title' => 'Alpes']);

        // Effectuer la requête de suppression avec l'ID du média
        $this->client->request('GET', '/admin/media/delete/' . $media->getId());
    }


}