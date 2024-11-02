<?php
namespace App\Tests;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RegistrationControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private UserRepository $userRepository;
    private EntityManager $entityManager;

    protected function setUp(): void
    {
        $this->client = static::createClient();

        $container = static::getContainer();
        $this->entityManager = $container->get('doctrine')->getManager();
        $this->userRepository = $container->get(UserRepository::class);

        // Supprime les utilisateurs spécifiques avant chaque test
        $this->removeTestUsers(['newuser@example.com', 'admin@admin.com']);

        // Créer un utilisateur administrateur
        $admin = new User();
        $admin->setName('admin');
        $admin->setDescription('test description');
        $admin->setEmail('admin@admin.com');
        $admin->setPassword('$2y$13$7JS0ehfU8vZhB3Q8o1sPGuoQxkiPGXRGgrAizmNfI5Sgy.Dqt9xoW'); // Mot de passe haché
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setAuthorized(true); // Assurez-vous que ce champ existe dans votre entité

        $this->entityManager->persist($admin);
        $this->entityManager->flush();
    }

    /**
     * @param string[] $emails
     */
    private function removeTestUsers(array $emails): void
    {
        foreach ($emails as $email) {
            $user = $this->userRepository->findOneBy(['email' => $email]);
            if ($user) {
                $this->entityManager->remove($user);
            }
        }
        $this->entityManager->flush();
    }

    public function testRegister(): void
    {
        // Accède à la page de connexion pour récupérer le jeton CSRF
        $this->client->request('GET', '/login');
        self::assertResponseIsSuccessful();

        // Récupérer le jeton CSRF
        $crawler = $this->client->getCrawler();
        $csrfToken = $crawler->filter('input[name="_csrf_token"]')->attr('value');

        // Connexion en tant qu'administrateur
        $this->client->request('POST', '/login', [
            'email' => 'admin@admin.com',
            'password' => 'password', // Assurez-vous que ce mot de passe correspond à celui de l'administrateur
            '_csrf_token' => $csrfToken, // Ajoute le jeton CSRF ici
        ]);

        // Vérifie si la connexion a réussi
        self::assertResponseRedirects('/');
        $this->client->followRedirect();

        // Maintenant, nous pouvons enregistrer un nouvel utilisateur
        $this->client->request('GET', '/register');
        self::assertResponseIsSuccessful();
        self::assertPageTitleContains('Ajouter un invité');

        // Soumission du formulaire d'enregistrement
        $this->client->submitForm('Ajouter', [
            'registration_form[name]' => 'newuser',
            'registration_form[description]' => 'text description',
            'registration_form[email]' => 'newuser@example.com',
            'registration_form[plainPassword]' => 'newpassword',
        ]);

        // Vérifie que la réponse redirige après la soumission du formulaire
        self::assertResponseRedirects('/admin/guest'); // Ajuste le chemin de redirection si nécessaire

        // Vérifie que l'utilisateur newuser@example.com existe dans la base de données
        $newUser = $this->userRepository->findOneBy(['email' => 'newuser@example.com']);
        self::assertNotNull($newUser); // Vérifie que l'utilisateur a été créé
        self::assertEquals('newuser', $newUser->getName()); // Vérifie le nom de l'utilisateur
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $this->entityManager->close();
    }
}
