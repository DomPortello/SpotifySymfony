<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin')]
class AdminHomeController extends AbstractController
{
    #[Route('/', name: 'admin_home')]
    public function index(): Response
    {
        return $this->render('admin/admin_home/index.html.twig', [
            'controller_name' => 'AdminHomeController',
        ]);
    }
}
