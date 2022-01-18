<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('admin/album')]
class AlbumController extends AbstractController
{
    #[Route('/', name: 'admin_album_index')]
    public function index(): Response
    {
        return $this->render('admin/album/index.html.twig', [
            'controller_name' => 'AlbumController',
        ]);
    }
}
