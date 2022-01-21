<?php

namespace App\Controller\Admin;

use App\Entity\Album;
use App\Form\AlbumType;
use App\Repository\AlbumRepository;
use App\Repository\ArtistRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/album')]
class AlbumController extends AbstractController
{
    public function __construct(
        private AlbumRepository $albumRepository,
        private PaginatorInterface $paginator,
        private EntityManagerInterface $em
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

    #[Route('/delete/{id}', name: 'admin_album_delete')]
    public function delete(int $id)
    {
        $this->em->remove($this->albumRepository->find($id));
        return $this->redirectToRoute('admin_album_index');
    }

    #[Route('/edit/{id}', name: 'admin_album_edit')]
    public function edit(int $id, Request $request)
    {
        $album = $this->albumRepository->find($id);
        $form = $this->createForm(AlbumType::class, $album);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $this->em->persist($album);
            $this->em->flush();
            return $this->redirectToRoute('admin_album_index');
        }

        return $this->render('Admin/album/edit.html.twig', [
            'form' => $form->createView(),
            ]
        );
    }

    #[Route('/create', name: 'admin_album_create')]
    public function create(Request $request)
    {
        $album = new Album();
        $form = $this->createForm(AlbumType::class, $album);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $this->em->persist($album);
            $this->em->flush();
            return $this->redirectToRoute('admin_album_index');
        }
        return $this->render('Admin/album/edit.html.twig', [
                'form' => $form->createView(),
            ]
        );
    }
}
