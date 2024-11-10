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
              $manager->flush();

              $album2 = new Album();
              $album2->setName('second album');
              $manager->persist($album2);
              $manager->flush();
              // Créer des utilisateurs (nécessaire pour assigner aux médias)
              $user1 = new User();
              $user1->setEmail('user1@example.com')
                     ->setPassword('$2y$13$7JS0ehfU8vZhB3Q8o1sPGuoQxkiPGXRGgrAizmNfI5Sgy.Dqt9xoW')
                     ->setRoles([])
                     ->setAuthorized(true)
                     ->setName('User 1')
                     ->setDescription('Description for User 1');
              $manager->persist($user1);
              $manager->flush();

              $user2 = new User();
              $user2->setEmail('user2@example.com')
                     ->setPassword('$2y$13$7JS0ehfU8vZhB3Q8o1sPGuoQxkiPGXRGgrAizmNfI5Sgy.Dqt9xoW')
                     ->setRoles([])
                     ->setAuthorized(true)
                     ->setName('User 2')
                     ->setDescription('Description for User 2');
              $manager->persist($user2);
              $manager->flush();

              $user3 = new User();
              $user3->setEmail('user3@example.com')
                     ->setPassword('$2y$13$7JS0ehfU8vZhB3Q8o1sPGuoQxkiPGXRGgrAizmNfI5Sgy.Dqt9xoW')
                     ->setRoles([])
                     ->setAuthorized(true)
                     ->setName('User 3')
                     ->setDescription('Description for User 3');
              $manager->persist($user3);
              $manager->flush();

              $admin = new User();
              $admin->setEmail('admin@example.com')
                     ->setPassword('$2y$13$7JS0ehfU8vZhB3Q8o1sPGuoQxkiPGXRGgrAizmNfI5Sgy.Dqt9xoW')
                     ->setRoles(["ROLE_ADMIN"])
                     ->setAuthorized(true)
                     ->setName('Admin')
                     ->setDescription('Description for Admin');
              $manager->persist($admin);
              $manager->flush();

              // Créer les médias et les associer aux utilisateurs et albums
              $media1 = new Media();
              $media1->setUser($user1);
              $media1->setAlbum($album1);
              $media1->setPath('uploads/9282d7e63a00c3771f6717a759bca68a.jpg');
              $media1->setTitle('Himalaya');
              $manager->persist($media1);
              $manager->flush();

              $media2 = new Media();
              $media2->setUser($user2);
              $media2->setAlbum($album2);
              $media2->setPath('uploads/f77c038a6e7f379c430aff7b3815ef65.jpg');
              $media2->setTitle('Alpes');
              $manager->persist($media2);
              $manager->flush();

              $media3 = new Media();
              $media3->setUser($user2);
              $media3->setAlbum($album2);
              $media3->setPath('uploads/75eec760d84cbe45ff85262ae5b9f6d7.jpg');
              $media3->setTitle('Annecy');
              $manager->persist($media3);

              // Enregistrement en base de données
              $manager->flush();
       }
}
