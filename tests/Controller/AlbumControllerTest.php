<?php

namespace App\Tests\Controller\Admin;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Repository\AlbumRepository;

class AlbumControllerTest extends WebTestCase
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

    public function testIndex(): void
    {
        // Utilise le client déjà créé dans setUp()
        $this->client->request('GET', '/admin/album');
        $this->assertResponseIsSuccessful();
        
    }

    public function testAddAlbum(): void
    {
        // Utilise le client déjà créé dans setUp()
        $this->client->request('GET', '/admin/album/add');
        $this->assertResponseIsSuccessful();
        $this->client->submitForm('Ajouter', [
            'album[name]' => 'Test Album',
        ]);
        $this->assertResponseRedirects('/admin/album');
        
    }

    public function testUpdateAlbum(): void
    {
        // Utilise le client déjà créé dans setUp()
        $albumRepository = static::getContainer()->get(AlbumRepository::class);
        $album = $albumRepository->findOneBy(['name'=>'Test Album' ]);

        $this->client->request('GET', '/admin/album/update/' . $album->getId());
        $this->assertResponseIsSuccessful();
        $this->client->submitForm('Modifier', [
            'album[name]' => 'Updated Album'
        ]);
        $this->assertResponseRedirects('/admin/album');
        
    }

    public function testDeleteAlbum(): void
    {
        // Utilise le client déjà créé dans setUp()
        $albumRepository = static::getContainer()->get(AlbumRepository::class);
        $album = $albumRepository->findOneBy(["name"=>"Updated Album"]);

        $this->client->request('GET', '/admin/album/delete/' . $album->getId());
        $this->assertResponseRedirects('/admin/album');
    }
}
