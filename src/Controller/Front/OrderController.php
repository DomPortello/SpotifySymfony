<?php

namespace App\Controller\Front;

use App\Entity\Album;
use App\Entity\Order;
use App\Entity\OrderLine;
use App\Entity\User;
use App\Repository\AlbumRepository;
use App\Repository\OrderLineRepository;
use App\Repository\OrderRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\Types\This;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use function Symfony\Component\String\u;

#[Route('/order')]
class OrderController extends AbstractController
{

    private User $user;

    public function __construct(
        private OrderRepository $orderRepository,
        private OrderLineRepository $orderLineRepository,
        private AlbumRepository $albumRepository,
        private EntityManagerInterface $em,
        private UserRepository $userRepository,
        private Security $security
    )
    {
        $this->user = $this->userRepository->findOneBy(['email' => $this->security->getUser()->getUserIdentifier()]);
    }

    #[Route('/', name: 'front_order_index')]
    public function index(): Response
    {
        return $this->render('Front/order/index.html.twig', [
            'order' => $this->orderRepository->findOrderByUserWithProducts($this->user)
        ]);
    }

    /**
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    #[Route('/add/album/{id}', name: 'front_order_add_album')]
    public function addAlbum(int $id): Response
    {
        $order = $this->orderRepository->getActiveOrderByUserAndStatus($this->getUser()->getId());
        $album = $this->albumRepository->getQbAll()->where('album.id = :id')->setParameter(':id', $id)->getQuery()->getOneOrNullResult();

        if ($album && $this->user){
            if (!$order)
            {
                $order = new Order();
                $order->setUser($this->user);
                $order->setStatus('active')->setCreatedAt(new \DateTime());
            }

            $orderLine = new OrderLine();
            $orderLine->setOrderEntity($order)->setQuantity(1)->setAlbum($album);
//        $order->addOrderLine($orderLine);

            $this->em->persist($order);
            $this->em->persist($orderLine);
            $this->em->flush();
            return $this->redirectToRoute('front_user_profil');
        }

        return $this->redirectToRoute('front_home_index');
    }
}
