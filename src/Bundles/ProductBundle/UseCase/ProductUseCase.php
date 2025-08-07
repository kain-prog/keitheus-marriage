<?php

namespace App\Bundles\ProductBundle\UseCase;

use App\Bundles\CategoryBundle\Repository\CategoryRepository;
use App\Bundles\ProductBundle\Repository\ProductRepository;

readonly class ProductUseCase
{
    public function __construct(
        private ProductRepository $productRepository,
        private readonly CategoryRepository $categoryRepository,
    )
    {}

    public function renderGiftPage(?array $products): array
    {
        if(!$products){
            $products = $this->productRepository->findAll();
        }

        $categories = $this->categoryRepository->findAll();

        return array('products' => $products, 'categories' => $categories);
    }

    public function paymentProduct(string $sku): void
    {
        $product = $this->productRepository->findOneBy(['sku' => $sku]);

        $product->setIsPresented(true);
        $this->productRepository->save($product);
    }

    public function getProductsByCategories(string $categories): ?array
    {
        if(!$categories){
            return null;
        }

        $categoriesArray = explode(',', $categories);

        return $this->productRepository->getProductsByCategories($categoriesArray);
    }

}
