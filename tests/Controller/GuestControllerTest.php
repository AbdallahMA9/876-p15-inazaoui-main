<?php

namespace App\Tests\Controller\Admin;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class GuestControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private UserRepository $userRepository;
    private EntityManagerInterface $entityManager;

    protected function setUp(): void
    {
        $this->client = static::createClient();

        // Initialisation de l'EntityManager et du UserRepository
        $this->entityManager = $this->client->getContainer()->get('doctrine')->getManager();
        $this->userRepository = $this->entityManager->getRepository(User::class);

        // Rechercher un utilisateur administrateur de test
        $adminUser = $this->userRepository->findOneBy(['email' => 'admin@example.com']);

        // Vérifiez si l'utilisateur existe dans la base de données de test
        $this->assertNotNull($adminUser, 'Le compte administrateur n\'existe pas dans la base de données de test.');

        // Connexion en tant qu'administrateur
        $this->client->loginUser($adminUser);
    }

    public function testIndex(): void
    {
        $this->client->request('GET', '/admin/guest');

        $this->assertResponseIsSuccessful();


    }

    public function testDisableGuest(): void
    {
        $user = $this->userRepository->findOneBy(['email' => 'user3@example.com']);
        $this->assertNotNull($user, 'L\'utilisateur de test n\'existe pas dans la base de données de test.');

        // Désactivez l'utilisateur
        $this->client->request('GET', '/admin/guest/disable/' . $user->getId());

        // Rafraîchit l'utilisateur de la base de données
        $this->entityManager->refresh($user);
        $this->assertFalse($user->isAuthorized(), 'L\'utilisateur devrait être désactivé');
    }

    public function testActiveGuest(): void
    {
        $user = $this->userRepository->findOneBy(['email' => 'user3@example.com']);
        $this->assertNotNull($user, 'L\'utilisateur de test n\'existe pas dans la base de données de test.');

        // Activez l'utilisateur
        $this->client->request('GET', '/admin/guest/active/' . $user->getId());

        // Rafraîchit l'utilisateur de la base de données
        $this->entityManager->refresh($user);
        $this->assertTrue($user->isAuthorized(), 'L\'utilisateur devrait être activé');
    }

    public function testDeleteGuest(): void
    {
        $user = $this->userRepository->findOneBy(['email' => 'user3@example.com']);
        $this->assertNotNull($user, 'L\'utilisateur de test n\'existe pas dans la base de données de test.');

        $userId = $user->getId();

        // Supprimez l'utilisateur
        $this->client->request('GET', '/admin/guest/delete/' . $userId);

        // Vérifiez que l'utilisateur n'existe plus dans la base de données
        $deletedUser = $this->userRepository->find($userId);
        $this->assertNull($deletedUser, 'L\'utilisateur devrait être supprimé');
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        // Réinitialise l'entité de test après chaque test
        $this->entityManager->clear();
    }
}
