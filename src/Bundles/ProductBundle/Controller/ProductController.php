<?php

namespace App\Bundles\ProductBundle\Controller;

use App\Bundles\ProductBundle\UseCase\ProductUseCase;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class ProductController extends AbstractController
{
    public function __construct(
        private readonly ProductUseCase $productUseCase
    )
    {}

    public function execute(?array $products): Response
    {
        $response = $this->productUseCase->renderGiftPage($products);

        return $this->render('@Views/Gifts/index.html.twig', [
            'products' => $response['products'],
            'categories' => $response['categories'],
            'title' => 'KeiTheus - Lista de Presentes',
        ]);
    }

    public function paymentProduct(string $sku): Response
    {
        $this->productUseCase->paymentProduct($sku);

        return $this->redirectToRoute('app_gift');
    }

    public function getProductsByCategories(?string $categories): Response
    {
        $products = $this->productUseCase->getProductsByCategories($categories);

        return $this->execute($products);
    }
}
