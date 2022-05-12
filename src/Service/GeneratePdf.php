<?php

namespace App\Service;

use App\Repository\OrderLineRepository;
use App\Repository\OrderRepository;
use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;
use Knp\Snappy\Pdf;
use Twig\Environment;

class GeneratePdf
{

    public function __construct(
        private OrderRepository $orderRepository,
        private OrderLineRepository $orderLineRepository,
        private Environment $environment
    ){}

    public function generate(int $id)
    {
        $order = $this->orderRepository->findOrderByIdWithProducts($id);
        $total = $this->orderLineRepository->totalOrder($order->getId());

        $html = $this->environment->render('partials/front/cart.html.twig', [
            'orderLines' => $order->getOrderLines(),
            'total' => $total,
            'btnValide' => false
        ]);

        $pdf = new Pdf("\"C:\\Program Files\\wkhtmltopdf\\bin\\wkhtmltopdf.exe\"");
        $pdf->generateFromHtml($html, 'file.pdf');

    }
}