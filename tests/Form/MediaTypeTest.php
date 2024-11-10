<?php

namespace App\Tests\Form;

use App\Entity\Album;
use App\Entity\Media;
use App\Entity\User;
use App\Form\MediaType;
use App\Repository\MediaRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Form\PreloadedExtension;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class MediaTypeTest extends WebTestCase
{
    private UserRepository $userRepository;
    private EntityManagerInterface $entityManager;
    private UploadedFile $uploadedFile;
    private FormFactoryInterface $formFactory;

    protected function setUp(): void
    {
        parent::setUp();

        // Créer le client
        $client = static::createClient();

        // Initialisation de l'EntityManager, UserRepository et FormFactory
        $this->entityManager = $client->getContainer()->get('doctrine')->getManager();
        $this->userRepository = $this->entityManager->getRepository(User::class);
        $this->formFactory = $client->getContainer()->get('form.factory'); // Ajout de la FormFactory

        // Rechercher un utilisateur administrateur de test
        $adminUser = $this->userRepository->findOneBy(['email' => 'admin@example.com']);
        $this->assertNotNull($adminUser, 'Le compte administrateur n\'existe pas dans la base de données de test.');

        // Connexion en tant qu'administrateur
        $client->loginUser($adminUser);

        $this->uploadedFile = new UploadedFile(
            __DIR__ . '/../../public/images/ina.png',
            'home.png',
            'image/png',
            null
        );
    }

    

    public function testSubmitValidData(): void
    {
        $albumRepository = $this->entityManager->getRepository(Album::class);
        $album = $albumRepository->findOneBy(['name' => 'first Album']);
        $this->assertNotNull($album, 'L\'album "first Album" n\'existe pas dans la base de données de test.');
    
        $user = $this->userRepository->findOneBy(['email' => 'user1@example.com']);

        $this->assertNotNull($user, 'L\'utilisateur avec l\'email "user1@example.com" n\'existe pas dans la base de données de test.');
    
        $formData = [
            'file' => $this->uploadedFile,
            'title' => 'Alpes',
            'user' => $user,
            'album' => $album,
        ];
    
        $model = new Media();
        $form = $this->formFactory->create(MediaType::class, $model, ['is_admin' => true]);
    
        // Soumettre les données au formulaire
        $form->submit($formData);
    
        // Vérifier que le formulaire est valide
        $this->assertTrue($form->isSynchronized());
    

    }
    
}
