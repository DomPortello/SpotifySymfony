<?php

namespace App\Controller\Admin;

use App\Entity\Artist;
use App\Entity\Track;
use App\Form\ArtistType;
use App\Form\TrackType;
use App\Repository\TrackRepository;
use Doctrine\ORM\EntityManagerInterface;
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
        private EntityManagerInterface $em,
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

    #[Route('/create', name: 'admin_track_create')]
    public function create(Request $request)
    {
        $track = new Track();
        $form = $this->createForm(TrackType::class, $track);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $this->em->persist($track);
            $this->em->flush();
            return $this->redirectToRoute('admin_track_index');
        }

        return $this->render('Admin/track/edit.html.twig', [
                'form' => $form->createView(),
            ]
        );
    }
}
