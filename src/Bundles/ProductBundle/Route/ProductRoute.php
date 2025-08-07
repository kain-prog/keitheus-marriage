<?php

namespace App\Bundles\ProductBundle\Route;

use App\Bundles\ProductBundle\Controller\ProductController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ProductRoute extends AbstractController
{
    public function __construct(private readonly ProductController $productController)
    {}

    #[Route('/product/{sku}', name: 'app_payment_product', methods: ['GET'])]
    public function paymentProduct(Request $request): Response
    {
        $sku = $request->get('sku');

        return $this->productController->paymentProduct($sku);
    }

    #[Route('/product/category/{categories}', name: 'app_product_category', methods: ['GET'])]
    public function productsByCategories(Request $request): Response
    {
        $categories = $request->get('categories');

        return $this->productController->getProductsByCategories($categories);
    }
}

