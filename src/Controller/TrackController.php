<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/track')]
class TrackController extends AbstractController
{
    #[Route('/', name: 'track')]
    public function index(): Response
    {
        return $this->render('track/index.html.twig', [
            'controller_name' => 'TrackController',
        ]);
    }
}
