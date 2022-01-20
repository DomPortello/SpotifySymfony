<?php

namespace App\Controller\Front;

use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\DocBlock\Tags\Var_;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('user/')]
class UserProfileController extends AbstractController
{

    public function __construct(
        private EntityManagerInterface $em,
    ){
    }
    #[Route('profil', name: 'front_user_profil')]
    public function index(): Response
    {
        return $this->render('Front/user_profile/index.html.twig', [
        ]);
    }

    #[Route('profil/edit', name: 'front_user_profil_edit')]
    public function edit(Request $request, SluggerInterface $sluger): Response
    {
        if (!$this->getUser()){
            return $this->redirectToRoute('app_login');
        }
        $user = $this->getUser();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $profilPicture = $form->get('picture')->getData();
            if($profilPicture){
                $originalFileName = pathinfo($profilPicture->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFileName = $sluger->slug($originalFileName);
                $newFileName = $safeFileName . '-' . uniqid() . '.' . $profilPicture->guessExtension();

                try{
                    $profilPicture->move($this->getParameter('upload_dir'), $newFileName);
                    $user->setPicture($newFileName);
                }catch(FileException $e){
                    dump($e->getMessage());
                }
            }
            $this->em->persist($user);
            $this->em->flush();

            return $this->redirectToRoute('front_user_profil');
        }
        return $this->render('Front/user_profile/edit.html.twig', [
            'form' => $form->createView(),
         ]);
    }
}
