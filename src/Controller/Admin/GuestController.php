<?php

namespace App\Controller\Admin;

use App\Entity\Album;
use App\Entity\Media;
use App\Entity\User;
use App\Repository\AlbumRepository;
use App\Repository\MediaRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class GuestController extends AbstractController
{

    #[Route('/admin/guest', name: 'admin_guest_index')]
    public function index(Request $request, UserRepository $userRepository, MediaRepository $mediaRepository)
    {
        // Récupérer tous les utilisateurs
        $allUsers = $userRepository->findAll();
        // Filtrer pour obtenir uniquement les invités
        $guests = array_filter($allUsers, function ($user) {
            return !in_array('ROLE_ADMIN', $user->getRoles());
        });
    
        // Pagination
        $page = $request->query->getInt('page', 1);
        $limit = 25; // Limite par page
    
        // Calculer le nombre total d'invités
        $total = count($guests);
        $totalPages = (int) ceil($total / $limit); // Total de pages
    
        // Récupérer les invités pour la page actuelle
        $offset = ($page - 1) * $limit;
        $guestsPage = array_slice($guests, $offset, $limit);
    
        return $this->render(
            'admin/guest/index.html.twig',
            [
                'guests' => $guestsPage,
                'total' => $total,
                'page' => $page,
                'totalPages' => $totalPages
            ]
        );
    }
    

}