<?php

namespace App\Controller\Admin;

use App\Entity\Genre;
use App\Repository\GenreRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('admin/genre')]
class GenreController extends AbstractController
{

    private GenreRepository $genreRepository;
    private PaginatorInterface $paginator;

    /**
     * @param GenreRepository $genreRepository
     * @param PaginatorInterface $paginator
     */
    public function __construct(GenreRepository $genreRepository, PaginatorInterface $paginator)
    {
        $this->genreRepository = $genreRepository;
        $this->paginator = $paginator;
    }


    /**
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    #[Route('/', name: 'admin_genre_index')]
    public function index(Request $request): Response
    {
        $qb = $this->genreRepository->findGenreByAlpha();
        return $this->render('Admin/genre/index.html.twig', [
            'controller_name' => 'GenreController',
            'paginationGenres' => $this->paginator->paginate($qb, $request->query->getInt('page', 1), 10)
        ]);
    }

    /**
     * @Route("/{id}", name="genre_show")
     */
    public function show(GenreRepository $genreRepository, string $id): Response
    {
        $genre = $this->genreRepository->findWithRelations($id);
        return $this->render('genre/show.html.twig',[
            'genre' => $genreRepository
        ]);
    }

//    /**
//     * @Route("/edit/{slug}", name="genre_edit")
//     * @param Request $request
//     * @param Genre $genre
//     * @return Response
//     */
//    public function edit(Request $request, Genre $genre): Response {
//        return $this->createFormFromEntity($request, $genre, 'genre/edit.html.twig');
//    }

}
