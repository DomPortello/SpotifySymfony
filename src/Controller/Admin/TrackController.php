<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('admin/track')]
class TrackController extends AbstractController
{
    #[Route('/', name: 'admin_track_index')]
    public function index(): Response
    {
        return $this->render('Admin/track/index.html.twig', [
            'controller_name' => 'TrackController',
        ]);
    }
}
