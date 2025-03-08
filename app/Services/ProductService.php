<?php

namespace App\Services;

use App\Repositories\ProductRepository;

class ProductService
{
    protected $productRepository;

    public function __construct(ProductRepository $productRepository) {
        $this->productRepository = $productRepository;
    }

    public function getAll() {
        $products = $this->productRepository->getAll();
        
        return $products;
    }

    public function getById($id) {
        $product = $this->productRepository->getById($id);
        
        return $product;
    }

    public function create(array $data) {
        $product = $this->productRepository->create($data);

        return $product;
    }

    public function update($id, array $data) {
        $product = $this->productRepository->update($id, $data);

        return $product;
    }
}