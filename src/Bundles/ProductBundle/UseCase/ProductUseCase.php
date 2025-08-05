<?php

namespace App\Bundles\ProductBundle\UseCase;

use App\Bundles\ProductBundle\Repository\ProductRepository;

readonly class ProductUseCase
{
    public function __construct(
        private ProductRepository $productRepository
    )
    {}

    public function paymentProduct(string $sku): void
    {
        $product = $this->productRepository->findOneBy(['sku' => $sku]);

        $product->setIsPresented(true);
        $this->productRepository->save($product);
    }
}
