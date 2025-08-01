<?php

namespace App\Bundles\ProductBundle\UseCase;

use App\Bundles\ProductBundle\Repository\ProductRepository;

class ProductUseCase
{
    public function __construct(
        private ProductRepository $productRepository
    )
    {}


}
