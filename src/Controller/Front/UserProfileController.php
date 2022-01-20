<?php

namespace App\Controller\Front;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('user/')]
class UserProfileController extends AbstractController
{
    #[Route('/profile', name: 'front_user_profile')]
    public function index(): Response
    {
        return $this->render('Front/user_profile/index.html.twig', [
            'controller_name' => 'UserProfileController',
        ]);
    }
}
