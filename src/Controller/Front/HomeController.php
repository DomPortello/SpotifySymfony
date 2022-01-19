<?php

namespace App\Controller\Front;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'front_home_index')]
    public function index(): Response
    {
        return $this->render('Front/home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }
}
