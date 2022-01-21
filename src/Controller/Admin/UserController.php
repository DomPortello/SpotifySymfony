<?php

namespace App\Controller\Admin;

use App\Repository\UserRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/user')]
class UserController extends AbstractController
{
    public function __construct(
        private UserRepository $userRepository,
        private PaginatorInterface $paginator,
    )
    {
    }

    #[Route('/', name: 'admin_user_index')]
    public function index(Request $request): Response
    {
        $qb = $this->userRepository->findByAlpha();
        return $this->render('Admin/user/index.html.twig', [
            'pagination' => $this->paginator->paginate($qb, $request->query->getInt('page', 1), 10)
        ]);
    }
}
