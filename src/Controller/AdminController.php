<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Article;
use App\Entity\Contact;
use App\Entity\Carousel;
use App\Entity\Commentaire;
use App\Entity\Reservation;
use App\Entity\Carouselmobile;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

#[Route('/admin')]
class AdminController extends AbstractController
{
    #[Route('/tableau-de-bord', name: 'show_dashboard', methods: ['GET'])]
    public function showDashboard(EntityManagerInterface $entityManager): Response
    {
        # Ce bloc de code try/catch() permet de bloquer l'accès et de rediriger si le rôle n'est pas bon.
        # Il faudra désactiver access_controle dans config/packages/security.yaml.
        try {
            $this->denyAccessUnlessGranted("ROLE_ADMIN");
        } catch (AccessDeniedException) {
            $this->addFlash('danger', "Cette partie du site est réservé");
            return $this->redirectToRoute('app_login');
        }

        $articles = $entityManager->getRepository(Article::class)->findBy(['deletedAt' => null]);
        $users = $entityManager->getRepository(User::class)->findBy(['deletedAt' => null]);
        $messages = $entityManager->getRepository(Contact::class)->findBy(['deletedAt' => null]);
        $reservations = $entityManager->getRepository(Reservation::class)->findBy(['deletedAt' => null]);
        $carousels = $entityManager->getRepository(Carousel::class)->findBy(['deletedAt' => null]);

        return $this->render('admin/show_dashboard.html.twig', [
            'articles' => $articles,
            'users' => $users,
            'messages' => $messages,
            'reservations' => $reservations,
            'carousels' => $carousels,
        ]);
    } // end showDashboard

    #[Route('/voir-les-archives', name: 'show_archive', methods: ['GET'])]
    public function showArchives (EntityManagerInterface $entityManager): Response
    {

    $articles = $entityManager->getRepository(Article::class)->findAllArchived();
    $messages = $entityManager->getRepository(Contact::class)->findAllArchived();
    $reservations = $entityManager->getRepository(Reservation::class)->findAllArchived();
    $carousels = $entityManager->getRepository(Carousel::class)->findAllArchived();
    
    return $this->render('admin/show_archive.html.twig', [
        'articles' => $articles,
        'messages' => $messages,
        'reservations' => $reservations,
        'carousels' => $carousels,
    ]);

    } // endshowArchives

} // end class
