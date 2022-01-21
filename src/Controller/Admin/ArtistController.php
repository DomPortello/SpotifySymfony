<?php

namespace App\Controller\Admin;

use App\Entity\Album;
use App\Entity\Artist;
use App\Form\AlbumType;
use App\Form\ArtistType;
use App\Repository\ArtistRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/artist')]
class ArtistController extends AbstractController
{

    public function __construct(
        private ArtistRepository $artistRepository,
        private PaginatorInterface $paginator,
        private EntityManagerInterface $em,
    )
    {
    }

    #[Route('/', name: 'admin_artist_index')]
    public function index(Request $request): Response
    {
        $qb = $this->artistRepository->findByAlpha();
        return $this->render('Admin/artist/index.html.twig', [
            'pagination' => $this->paginator->paginate($qb, $request->query->getInt('page', 1), 10)
        ]);
    }

    #[Route('/create', name: 'admin_artist_create')]
    public function create(Request $request)
    {
        $artist = new Artist();
        $form = $this->createForm(ArtistType::class, $artist);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $this->em->persist($artist);
            $this->em->flush();
            return $this->redirectToRoute('admin_artist_index');
        }

        return $this->render('Admin/artist/edit.html.twig', [
                'form' => $form->createView(),
            ]
        );
    }
}
