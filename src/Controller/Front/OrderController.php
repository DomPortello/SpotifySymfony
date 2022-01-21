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
use App\Service\Paiement;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\Types\This;
use Stripe\Checkout\Session;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Security;
use function Symfony\Component\String\u;

#[Route('/order')]
class OrderController extends AbstractController
{

    private ?User $user;

    public function __construct(
        private OrderRepository        $orderRepository,
        private OrderLineRepository    $orderLineRepository,
        private AlbumRepository        $albumRepository,
        private EntityManagerInterface $em,
        private UserRepository         $userRepository,
        private Security               $security
    )
    {
        if($this->security->getUser() != null){
            $this->user = $this->userRepository->findOneBy(['email' => $this->security->getUser()->getUserIdentifier()]);
        }else{
            $this->user = null;
        }
    }

    #[Route('/', name: 'front_order_index')]
    public function index(): Response
    {
        $order = $this->orderRepository->findActiveOrderByUserWithProducts($this->user);
        if ($order) {
            $total = $this->orderLineRepository->totalOrder($order->getId());
        } else {
            $total = 0;
        }
        return $this->render('Front/order/index.html.twig', [
            'order' => $order,
            'total' => $total
        ]);
    }

    #[Route('/payment', name: 'front_order_payment')]
    public function paymentIntent(Paiement $payment): Response
    {
        $order = $this->orderRepository->findActiveOrderByUserWithProducts($this->user);
        $total = $this->orderLineRepository->totalOrder($order->getId());
        $intent = $payment->paiementIntent($total * 100);

        $redirectPaymentUrl = $this->generateUrl('front_order_payment_proceed', ['id' => $order->getId()], UrlGeneratorInterface::ABSOLUTE_PATH);
        return $this->render('Front/payment/form_process.html.twig', [
            'intent' => $intent,
            'redirectPaymentUrl' => $redirectPaymentUrl
        ]);
    }

    #[Route('/payment/proceed/{id}', name: 'front_order_payment_proceed')]
    public function paymentProceed(Order $order, Request $request): Response
    {
        if ($order === null) {
            return $this->redirectToRoute('front_home_index');
        }

        $order->setEndedAt(new \DateTime());
        if ($request->request->get('status') === "succeeded") {
            $order->setStatus('payed');
        } else {
            $order->setStatus('failed');
        }
        $this->em->persist($order);
        $this->em->flush();

        if ($order->getStatus() === 'payed') {
            return $this->redirectToRoute('front_order_payment_success', ['id' => $order->getId()]);
        } else {
            return $this->redirectToRoute('front_order_payment_failed');
        }
    }

    #[Route('payment-accepted/{id}', name: 'front_order_payment_success')]
    public function successPayment(Order $order): Response
    {
        return $this->render('Front/order/payment_success.html.twig', [
            'order' => $order
        ]);
    }

    #[Route('payment-failed', name: 'front_order_payment_failed')]
    public function successFailed(): Response
    {
        return $this->render('Front/order/payment_failed.html.twig', [
        ]);
    }

    /**
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    #[Route('/add/album/{id}', name: 'front_order_add_album')]
    public function addAlbum(int $id): Response
    {
        if($this->user == null){
            return $this->redirectToRoute('app_login');
        }
        $order = $this->orderRepository->getActiveOrderByUserAndStatus($this->user->getId());
        $album = $this->albumRepository->getQbAll()->where('album.id = :id')->setParameter(':id', $id)->getQuery()->getOneOrNullResult();

        if ($album && $this->user) {
            if (!$order) {
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
            return $this->redirectToRoute('front_order_index');
        }

        return $this->redirectToRoute('front_home_index');
    }

    #[Route('/delete/orderLine/{id}', name: 'front_order_delete_line')]
    public function delete(int $id): Response
    {
        $order = $this->orderRepository->getActiveOrderByUserAndStatus($this->user->getId());
        if ($this->user && $order) {
            $this->em->remove($this->orderLineRepository->find($id));
            $this->em->flush();
            return $this->redirectToRoute('front_order_index');
        }
        return $this->redirectToRoute('front_home_index');
    }
}
