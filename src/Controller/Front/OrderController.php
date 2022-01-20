<?php

namespace App\Controller\Front;

use App\Entity\Album;
use App\Entity\Order;
use App\Entity\OrderLine;
use App\Entity\User;
use App\Repository\AlbumRepository;
use App\Repository\OrderLineRepository;
use App\Repository\OrderRepository;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\Types\This;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use function Symfony\Component\String\u;

#[Route('/order')]
class OrderController extends AbstractController
{

    public function __construct(
        private OrderRepository $orderRepository,
        private OrderLineRepository $orderLineRepository,
        private AlbumRepository $albumRepository,
        private EntityManagerInterface $em
    )
    {
    }

    /**
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    #[Route('/album/{id}', name: 'order_index')]
    public function index(int $id): Response
    {
        $user = $this->getUser();
        $order = $this->orderRepository->getActiveOrderByUserAndStatus($id);
        $album = $this->albumRepository->getQbAll()->where('album.id = :id')->setParameter(':id', $id)->getQuery()->getOneOrNullResult();

        if ($album && $user){
            if (!$order)
            {
                $order = new Order();
                /** @var User $user */
                $order->setUser($user);
                $order->setStatus('active')->setCreatedAt(new \DateTime());
            }

            $orderLine = new OrderLine();
            $orderLine->setOrderEntity($order)->setQuantity(1)->setAlbum($album);
//        $order->addOrderLine($orderLine);

            $this->em->persist($order);
            $this->em->persist($orderLine);
            $this->em->flush();
            return $this->redirectToRoute('front_user_profile');
        }

        return $this->redirectToRoute('front_home_index');
    }
}
