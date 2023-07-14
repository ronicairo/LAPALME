<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Carousel;
use App\Entity\Carouselmobile;
use App\Entity\Commentaire;
use App\Entity\Galerie;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DefaultController extends AbstractController
{
    #[Route('/', name: 'show_home', methods: ['GET'])]
    public function showHome(EntityManagerInterface $entityManager) : Response
    {
        $articles = $entityManager->getRepository(Article::class)->findBy(['deletedAt' => null]);
        $carousels = $entityManager->getRepository(Carousel::class)->findBy(['deletedAt' => null]);
        $commentaires = $entityManager->getRepository(Commentaire::class)->findBy(['deletedAt' => null]);
        $galeries = $entityManager->getRepository(Galerie::class)->findBy(['deletedAt' => null]);
        $carouselmobiles = $entityManager->getRepository(Carouselmobile::class)->findBy(['deletedAt' => null]);

        return $this->render('default/show_home.html.twig', [
            'articles' => $articles,
            'carousels' => $carousels,
            'carouselmobiles' => $carouselmobiles,
            'commentaires' => $commentaires,
            'galeries' => $galeries,
        ]);
    } // end function showHome()

    
}
