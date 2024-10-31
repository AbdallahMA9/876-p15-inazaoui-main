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

    protected function setUp(): void
    {
        $this->client = static::createClient();

        // Assure que nous avons une base de données propre
        $container = static::getContainer();
        /** @var EntityManager $em */
        $em = $container->get('doctrine')->getManager();
        $this->userRepository = $container->get(UserRepository::class);

        // Supprime tous les utilisateurs existants
        foreach ($this->userRepository->findAll() as $user) {
            $em->remove($user);
        }

        $em->flush();

        // Créer un utilisateur administrateur
        $admin = new User();
        $admin->setName('admin');
        $admin->setDescription('test description');
        $admin->setEmail('admin@admin.com');
        // Assurez-vous que ce mot de passe est bien haché
        $admin->setPassword('$2y$13$7JS0ehfU8vZhB3Q8o1sPGuoQxkiPGXRGgrAizmNfI5Sgy.Dqt9xoW'); 
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setAuthorized(true); // Vérifie si tu as ce champ

        $em->persist($admin);
        $em->flush();
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
        self::assertPageTitleContains('Register');

        // Soumission du formulaire d'enregistrement
        $this->client->submitForm('Register', [
            'registration_form[name]' => 'newuser',
            'registration_form[description]' => 'text description',
            'registration_form[email]' => 'newuser@example.com',
            'registration_form[plainPassword]' => 'newpassword',
            'registration_form[agreeTerms]' => true,
        ]);

        // Vérifie que la réponse redirige après la soumission du formulaire
        self::assertResponseRedirects('/'); // Ajuste le chemin de redirection si nécessaire

        // Vérifie le nombre total d'utilisateurs (1 admin + 1 nouvel utilisateur)
        self::assertCount(2, $this->userRepository->findAll());
    }
}
