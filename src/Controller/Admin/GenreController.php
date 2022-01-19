<?php

namespace App\Controller\Admin;

use App\Entity\Genre;
use App\Form\GenreFormType;
use App\Repository\GenreRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('admin/genre')]
class GenreController extends AbstractController
{
    private EntityManagerInterface $em;
    private GenreRepository $genreRepository;
    private PaginatorInterface $paginator;

    /**
     * @param EntityManagerInterface $em
     * @param GenreRepository $genreRepository
     * @param PaginatorInterface $paginator
     */
    public function __construct(EntityManagerInterface $em, GenreRepository $genreRepository, PaginatorInterface $paginator)
    {
        $this->em = $em;
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
     * @param Request $request
     * @return Response
     */
    #[Route("/new", name: "admin_genre_create")]
    public function new(Request $request): Response {
        return $this->createFormFromEntity($request, new Genre(), 'Admin/genre/new.html.twig');
    }

    /**
     * !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
     * @Route("/{id}", name="admin_genre_show")
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function show(GenreRepository $genreRepository, string $id): Response
    {
        $genre = $this->genreRepository->findWithRelations($id);
        return $this->render('Admin/genre/show.html.twig',[
            'genre' => $genreRepository
        ]);
    }

    #[Route('/delete/{id}', name: 'admin_genre_delete')]
    public function delete(int $id)
    {
        $this->em->remove($this->genreRepository->find($id));
        return $this->redirectToRoute('admin_genre_index');
    }

    /**
     * !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
     * @Route("/edit/{id}", name="admin_genre_edit")
     * @param Request $request
     * @param Genre $genre
     * @return Response
     */
    public function edit(Request $request, Genre $genre): Response {
        return $this->createFormFromEntity($request, $genre, 'Admin/genre/edit.html.twig');
    }


    /**
     * !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
     * @param Request $request
     * @param Genre $genre
     * @param string $template
     * @return Response
     */
    private function createFormFromEntity(Request $request, Genre $genre, string $template): Response {
        $form = $this->createForm(GenreFormType::class, $genre);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->textService->slugify($genre->getName());
            $this->em->persist($genre);
            $this->em->flush();
            return $this->redirectToRoute('admin_genre_index');
        }
        return $this->render($template,[
            'form' => $form->createView(),
        ]);
    }

}
