<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('admin/genre')]
class GenreController extends AbstractController
{
    #[Route('/', name: 'admin_genre_index')]
    public function index(): Response
    {
        return $this->render('Admin/genre/index.html.twig', [
            'controller_name' => 'GenreController',
        ]);
    }
}
