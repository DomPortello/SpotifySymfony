<?php

namespace App\Controller\Admin;

use App\Repository\ArtistRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('admin/artist')]
class ArtistController extends AbstractController
{


    public function __construct(
        private ArtistRepository $artistRepository,
        private PaginatorInterface $paginator,
    )
    {
    }

    #[Route('/', name: 'admin_artist_index')]
    public function index(Request $request): Response
    {
        $qb = $this->artistRepository->findByAlpha();
        return $this->render('Admin/artist/index.html.twig', [
            'pagination' => $this->paginator->paginate($qb, $request->query->getInt('page', 1), 4)
        ]);
    }
}
