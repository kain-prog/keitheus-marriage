<?php

namespace App\Bundles\ProductBundle\Controller;

use App\Bundles\CategoryBundle\Repository\CategoryRepository;
use App\Bundles\ProductBundle\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class ProductController extends AbstractController
{
    public function __construct(
        private readonly ProductRepository  $productRepository,
        private readonly CategoryRepository $categoryRepository,
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
}
