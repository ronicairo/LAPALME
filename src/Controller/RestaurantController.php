<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class RestaurantController extends AbstractController
{
    #[Route('/acces', name: 'show_acces')]
    public function showAcces(): Response
    {
        return $this->render('restaurant/acces.html.twig');
    }

}
