<?php

namespace App\Bundles\ProductBundle\Controller;

use App\Bundles\ProductBundle\UseCase\ProductUseCase;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\SerializerInterface;

class ProductController extends AbstractController
{
    public function __construct(
        private readonly ProductUseCase $productUseCase,
        private readonly SerializerInterface $serializer
    )
    {}

    /**
     * @throws ExceptionInterface
     */
    public function execute(?array $products): Response
    {
        $response = $this->productUseCase->renderGiftPage($products);

        $normalized = $this->serializer->serialize($response['products'], 'json', ['groups' => ['product', 'category']]);

        return $this->render('@Views/Gifts/index.html.twig', [
            'products' => $response['products'],
            'productsJson' => $normalized,
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
