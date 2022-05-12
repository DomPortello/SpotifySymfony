<?php

namespace App\Controller\Admin;

use App\Repository\OrderRepository;
use App\Service\GeneratePdf;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/order')]
class OrderController extends AbstractController
{
    public function __construct(
        private OrderRepository $orderRepository,
        private PaginatorInterface $paginator,
    ){}

    #[Route('/', name: 'admin_order_index')]
    public function index(Request $request): Response
    {
        $qb = $this->orderRepository->findByAlpha();

        return $this->render('Admin/order/index.html.twig', [
            'pagination' => $this->paginator->paginate($qb, $request->query->getInt('page', 1), 10)
        ]);
    }

    #[Route('/download/{id}', name: 'admin_order_download')]
    public function downloadOrder(int $id, GeneratePdf $generatePdf): Response
    {
        $generatePdf->generate($id);
        die();
    }
}
