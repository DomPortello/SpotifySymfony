<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('admin/user')]
class UserController extends AbstractController
{
    #[Route('/', name: 'admin_user_index')]
    public function index(): Response
    {
        return $this->render('Admin/user/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }
}
