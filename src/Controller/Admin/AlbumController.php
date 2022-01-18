<?php

namespace App\Controller\Admin;

use App\Repository\AlbumRepository;
use App\Repository\ArtistRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('admin/album')]
class AlbumController extends AbstractController
{
    public function __construct(
        private AlbumRepository $albumRepository,
        private PaginatorInterface $paginator,
    )
    {
    }

    #[Route('/', name: 'admin_album_index')]
    public function index(Request $request): Response
    {
        $qb = $this->albumRepository->findByAlpha();
        return $this->render('admin/album/index.html.twig', [
            'pagination' => $this->paginator->paginate($qb, $request->query->getInt('page', 1), 10)
        ]);
    }
}
