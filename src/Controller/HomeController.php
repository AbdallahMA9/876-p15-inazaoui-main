<?php

namespace App\Controller;

use App\Entity\Album;
use App\Entity\Media;
use App\Entity\User;
use App\Repository\AlbumRepository;
use App\Repository\MediaRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class HomeController extends AbstractController
{

    #[Route('/', name: 'home')]
    public function home(): Response
    {
        return $this->render('front/home.html.twig');
    }

    #[Route('/guests', name: 'guests')]
    public function guests(UserRepository $userRepository): Response
    {
        $allUsers = $userRepository->findBy(['isAuthorized' => true]);
        $guests = array_filter($allUsers, function($user) {
            return !in_array('ROLE_ADMIN', $user->getRoles());
        });
    
        return $this->render('front/guests.html.twig', [
            'guests' => $guests
        ]);
    }

    #[Route('/guest/{id}', name: 'guest')]
    public function guest(int $id, UserRepository $userRepository): Response
    {
        $guest = $userRepository->find($id);
    
        // Vérifier si l'utilisateur existe et s'il est autorisé
        if (!$guest || !$guest->isAuthorized()) {
            throw $this->createNotFoundException("Cet utilisateur n'est pas autorisé ou n'existe pas.");
        }
    
        return $this->render('front/guest.html.twig', [
            'guest' => $guest
        ]);
    }
    

    #[Route('/portfolio/{id}', name: 'portfolio')]
    public function portfolio(UserRepository $userRepository, MediaRepository $mediaRepository, AlbumRepository $albumRepository, ?int $id = null): Response
    {
        $albums = $albumRepository->findAll();

        $album = $id ? $albumRepository->find($id) : null;
        $medias = ($album) ? $mediaRepository->findBy(['album' => $album]) : $mediaRepository->findAll();
    
        // Exclure les médias dont l'utilisateur n'est pas autorisé
        $medias = array_filter($medias, function(Media $media) {
            return $media->getUser()->isAuthorized() != 0;
        });
    
        return $this->render('front/portfolio.html.twig', [
            'albums' => $albums,
            'album' => $album,
            'medias' => $medias
        ]);
    }
    
    

    #[Route('/about', name: 'about')]
    public function about(): Response
    {
        return $this->render('front/about.html.twig');
    }
}