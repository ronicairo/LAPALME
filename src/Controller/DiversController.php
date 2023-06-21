<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DiversController extends AbstractController
{
    #[Route('/mentions-legales', name: 'mentions_legales',methods:['GET'])]
    public function mentionsLegals(): Response
    {
        return $this->render('divers/mentions_legales.html.twig');
    }

}
