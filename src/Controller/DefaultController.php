<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Carousel;
use App\Entity\Commentaire;

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

        return $this->render('default/show_home.html.twig', [
            'articles' => $articles,
            'carousels' => $carousels,
            'commentaires' => $commentaires,
        ]);
    } // end function showHome()

    
}
