<?php

namespace App\Bundles\ProductBundle\Route;

use App\Bundles\GuestBundle\Controller\GuestController;
use App\Bundles\ProductBundle\Controller\ProductController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Routing\Attribute\Route;

class ProductRoute extends AbstractController
{
    public function __construct(private ProductController $productController)
    {
    }

    /**
     * @throws ExceptionInterface
     */
    #[Route('/product/{sku}', name: 'app_guest_confirm', methods: ['GET'])]
    public function confirmPresence(Request $request): Response
    {
        $sku = $request->get('sku');

        return $this->productController->paymentProduct($sku);
    }
}

