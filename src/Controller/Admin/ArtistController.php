<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('admin/artist')]
class ArtistController extends AbstractController
{
    #[Route('/', name: 'admin_artist_index')]
    public function index(): Response
    {
        return $this->render('Admin/artist/index.html.twig', [
            'controller_name' => 'ArtistController',
        ]);
    }
}
