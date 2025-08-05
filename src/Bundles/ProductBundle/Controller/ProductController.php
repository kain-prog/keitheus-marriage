<?php

namespace App\Bundles\ProductBundle\Controller;

use App\Bundles\CategoryBundle\Repository\CategoryRepository;
use App\Bundles\ProductBundle\Repository\ProductRepository;
use App\Bundles\ProductBundle\UseCase\ProductUseCase;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class ProductController extends AbstractController
{
    public function __construct(
        private readonly ProductRepository  $productRepository,
        private readonly CategoryRepository $categoryRepository,
        private readonly ProductUseCase $productUseCase
    )
    {}

    public function execute(): Response
    {
        $products = $this->productRepository->findAll();
        $categories = $this->categoryRepository->findAll();

        return $this->render('@Views/Gifts/index.html.twig', [
            'products' => $products,
            'categories' => $categories,
            'title' => 'KeiTheus - Lista de Presentes',
        ]);
    }

    public function paymentProduct(string $sku): Response
    {
        $this->productUseCase->paymentProduct($sku);

        return $this->redirectToRoute('app_gift');
    }
}
