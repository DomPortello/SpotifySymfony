<?php

namespace App\Controller\Front;

use App\Form\SearchAlbumByNameType;
use App\Repository\AlbumRepository;
use App\Repository\ArtistRepository;
use App\Repository\GenreRepository;
use App\Repository\TrackRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    private AlbumRepository $albumRepository;
    private TrackRepository $trackRepository;
    private ArtistRepository $artistRepository;
    private GenreRepository $genreRepository;
    private PaginatorInterface $paginator;

    /**
     * @param AlbumRepository $albumRepository
     * @param TrackRepository $trackRepository
     * @param ArtistRepository $artistRepository
     * @param GenreRepository $genreRepository
     * @param PaginatorInterface $paginator
     */
    public function __construct(AlbumRepository $albumRepository, TrackRepository $trackRepository, ArtistRepository $artistRepository, GenreRepository $genreRepository, PaginatorInterface $paginator)
    {
        $this->albumRepository = $albumRepository;
        $this->trackRepository = $trackRepository;
        $this->artistRepository = $artistRepository;
        $this->genreRepository = $genreRepository;
        $this->paginator = $paginator;
    }


    #[Route('/', name: 'front_home_index')]
    public function index(Request $request): Response
    {
        $form = $this->createForm(SearchAlbumByNameType::class);
        $form->handleRequest($request);

        $qbAlbums = $this->albumRepository->getQbAll();

        if ($form->isSubmitted() && $form->isValid()){
            $data = $form->get('title')->getData();

            $qbAlbums->where('album.title LIKE :data')
                ->orWhere('artist.name LIKE :data')
                ->orWhere('track.title LIKE :data')
                ->setParameter(':data', "%$data%");
        }
        $qbArtist = $this->artistRepository->getAllArtistsWithRelations();

        $qbTracks = $this->trackRepository->getAllTracksWithRelations('track.rank');
        $qbAlbums = $this->albumRepository->getAllAlbumsWithRelations();
        $paginationAlbum = $this->paginator->paginate($qbAlbums, $request->query->getInt('page', 1), 5);
        $paginationTracks = $this->paginator->paginate($qbTracks, $request->query->getInt('page', 1), 9);
        $paginationArtist = $this->paginator->paginate($qbArtist, $request->query->getInt('page', 1), 5);

        return $this->render('Front/home/index.html.twig', [
            'controller_name' => 'HomeController',
            'form' => $form->createView(),
            'albums' => $paginationAlbum,
            'tracks' => $paginationTracks,
            'artists' => $paginationArtist,
            'genres' => $this->genreRepository->findAll()
        ]);
    }
}