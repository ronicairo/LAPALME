<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Carousel;
use App\Entity\Commentaire;
use Detection\MobileDetect;
use App\Entity\Carouselmobile;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends AbstractController
{
    #[Route('/', name: 'show_home', methods: ['GET'])]
    public function showHome(EntityManagerInterface $entityManager, Request $request) : Response
    {
        $articles = $entityManager->getRepository(Article::class)->findBy(['deletedAt' => null]);
        $carousels = $entityManager->getRepository(Carousel::class)->findBy(['deletedAt' => null]);
        $commentaires = $entityManager->getRepository(Commentaire::class)->findBy(['deletedAt' => null]);
        $mobileDetect = new MobileDetect($request->headers->all());

        return $this->render('default/show_home.html.twig', [
            'articles' => $articles,
            'carousels' => $carousels,
            'commentaires' => $commentaires,
            'isMobile' => $mobileDetect->isMobile(),
        ]);
    } // end function showHome()

    
}
