<?php

namespace App\Controller\Front;

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
        $qbArtist = $this->artistRepository->getAllArtistsWithRelations();
        dump($qbArtist);
        $qbTracks = $this->trackRepository->getAllTracksWithRelations('track.rank');
        $qbAlbums = $this->albumRepository->getAllAlbumsWithRelations();
        dump($qbAlbums);
        $paginationAlbum = $this->paginator->paginate($qbAlbums, $request->query->getInt('page', 1), 5);
        $paginationTracks = $this->paginator->paginate($qbTracks, $request->query->getInt('page', 1), 9);
        $paginationArtist = $this->paginator->paginate($qbArtist, $request->query->getInt('page', 1), 5);

        return $this->render('Front/home/index.html.twig', [
            'controller_name' => 'HomeController',
            'albums' => $paginationAlbum,
            'tracks' => $paginationTracks,
            'artists' => $paginationArtist,
            'genres' => $this->genreRepository->findAll()
        ]);
    }
}