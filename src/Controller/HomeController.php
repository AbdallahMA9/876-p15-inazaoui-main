<?php

namespace App\Controller;

use App\Entity\Album;
use App\Entity\Media;
use App\Entity\User;
use App\Form\MediaType;
use App\Repository\AlbumRepository;
use App\Repository\MediaRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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

    #[Route('/user/media', name: 'user_media_index')]
    public function userMedia(Request $request, MediaRepository $mediaRepository): Response
    {
        $page = $request->query->getInt('page', 1);

        $criteria = [];

        if (!$this->isGranted('ROLE_ADMIN')) {
            $criteria['user'] = $this->getUser();
        }

        $medias = $mediaRepository->findBy(
            $criteria,
            ['id' => 'ASC'],
            25,
            25 * ($page - 1)
        );
        $total = $mediaRepository->count([]);

        return $this->render('front/medias.html.twig', [
            'medias' => $medias,
            'total' => $total,
            'page' => $page
        ]);
    }

    #[Route('/user/media/add', name: 'user_media_add')]
    public function add(Request $request, EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
        $media = new Media();
        $form = $this->createForm(MediaType::class, $media, ['is_admin' => $this->isGranted('ROLE_ADMIN')]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($user instanceof User) {
                $media->setUser($user);
            } else {
                // Gérer le cas où l'utilisateur n'est pas du type attendu
                throw new \LogicException('L\'utilisateur actuel n\'est pas de type App\Entity\User.');
            }
            $media->setPath('uploads/' . md5(uniqid()) . '.' . $media->getFile()->guessExtension());
            $media->getFile()->move('uploads/', $media->getPath());
            $em->persist($media);
            $em->flush();

            return $this->redirectToRoute('user_media_index');
        }

        return $this->render('front/addmedia.html.twig', ['form' => $form->createView()]);
    }

    #[Route('/user/media/delete/{id}', name: 'user_media_delete')]
    public function delete(int $id ,MediaRepository $mediaRepository , EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
        $media = $mediaRepository->find($id);
        $mediaUser = $media->getUser(); 
        if ($mediaUser == $user) {
        $em->remove($media);
        $em->flush();
        if ($media->getPath() != null) {
            unlink($media->getPath());
        }
    }

        return $this->redirectToRoute('user_media_index');
    }

    
    
}