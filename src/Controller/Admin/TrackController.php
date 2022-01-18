<?php

namespace App\Controller\Admin;

use App\Repository\TrackRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('admin/track')]
class TrackController extends AbstractController
{

    public function __construct(
        private TrackRepository $trackRepository,
        private PaginatorInterface $paginator,
    )
    {
    }

    #[Route('/', name: 'admin_track_index')]
    public function index(Request $request): Response
    {
        $qb = $this->trackRepository->findByAlpha('t.rank');
        return $this->render('Admin/track/index.html.twig', [
            'pagination' => $this->paginator->paginate($qb, $request->query->getInt('page', 1), 10)
        ]);
    }
}
