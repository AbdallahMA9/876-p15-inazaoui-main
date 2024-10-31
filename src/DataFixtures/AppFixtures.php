<?php

namespace App\DataFixtures;

use App\Entity\Album;
use App\Entity\Media;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // Créer les albums
        $album1 = new Album();
        $album1->setName('first Album');
        $manager->persist($album1);

        $album2 = new Album();
        $album2->setName('second album');
        $manager->persist($album2);

        // Créer des utilisateurs (nécessaire pour assigner aux médias)
        $user1 = new User();
        $user1->setEmail('user1@example.com')
              ->setPassword('hashed_password')
              ->setRoles(['ROLE_USER'])
              ->setAuthorized(true)
              ->setName('User 1')
              ->setDescription('Description for User 1');
        $manager->persist($user1);

        $user2 = new User();
        $user2->setEmail('user2@example.com')
              ->setPassword('hashed_password')
              ->setRoles(['ROLE_USER'])
              ->setAuthorized(true)
              ->setName('User 2')
              ->setDescription('Description for User 2');
        $manager->persist($user2);

        // Créer les médias et les associer aux utilisateurs et albums
        $media1 = new Media();
        $media1->setUser($user1)
               ->setAlbum($album1)
               ->setPath('uploads/9282d7e63a00c3771f6717a759bca68a.jpg')
               ->setTitle('Himalaya');
        $manager->persist($media1);

        $media2 = new Media();
        $media2->setUser($user2)
               ->setAlbum($album2)
               ->setPath('uploads/f77c038a6e7f379c430aff7b3815ef65.jpg')
               ->setTitle('Alpes');
        $manager->persist($media2);

        $media3 = new Media();
        $media3->setUser($user2)
               ->setAlbum($album2)
               ->setPath('uploads/75eec760d84cbe45ff85262ae5b9f6d7.jpg')
               ->setTitle('Annecy');
        $manager->persist($media3);

        // Enregistrement en base de données
        $manager->flush();
    }
}
