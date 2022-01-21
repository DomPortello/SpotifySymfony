<?php

namespace App\Controller\Front;

use App\Form\SearchAlbumByNameType;
use App\Repository\AlbumRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/album')]
class AlbumController extends AbstractController
{

    private AlbumRepository $albumRepository;
    private PaginatorInterface $paginator;

    /**
     * @param AlbumRepository $albumRepository
     * @param PaginatorInterface $paginator
     */
    public function __construct(AlbumRepository $albumRepository, PaginatorInterface $paginator)
    {
        $this->albumRepository = $albumRepository;
        $this->paginator = $paginator;
    }


    #[Route('/', name: 'front_album_index')]
    public function index(Request $request): Response
    {
        $form = $this->createForm(SearchAlbumByNameType::class);
        $form->handleRequest($request);

        $qb = $this->albumRepository->getQbAll();

        if ($form->isSubmitted() && $form->isValid()){
            $data = $form->get('title')->getData();

            $qb->where('album.title LIKE :data')
                ->orWhere('artist.name LIKE :data')
                ->orWhere('tracks.title LIKE :data')
                ->setParameter(':data', "%$data%");
        }

        $albumsPagination = $this->paginator->paginate($qb, $request->query->getInt('page', 1), 10);

        return $this->render('Front/album/index.html.twig', [
            'controller_name' => 'AlbumController',
//            'albums' => $this->albumRepository->getAllAlbumsWithRelations(),
            'albums' => $albumsPagination,
            'form' => $form->createView()
        ]);
    }

    /**
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    #[Route('/{id}', name: 'front_album_details')]
    public function show(int $id)
    {
//        $album = $this->albumRepository->getQbAll();
//        $album->where('album.id = :id')
//            ->setParameter(':id', $id)
//            ->getQuery()
//            ->getOneOrNullResult();
        
        $album = $this->albumRepository->find($id);
        
        return $this->render('Front/album/details.html.twig', [
            'album' => $album
        ]);
    }
}
