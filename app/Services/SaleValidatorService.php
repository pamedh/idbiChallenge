<?php

namespace App\Services;

use Symfony\Component\HttpKernel\Exception\HttpException;
use App\Repositories\ProductRepository;
use App\Repositories\SaleProductRepository;

class SaleValidatorService 
{
    protected $productRepository, $saleProductRepository;

    public function __construct(ProductRepository $productRepository, SaleProductRepository $saleProductRepository) {
        $this->productRepository = $productRepository;
        $this->saleProductRepository = $saleProductRepository;
    }

    public function getAvailableProduct($id, $quantity) {
        $product = $this->productRepository->getById($id);

        if($product->stock < $quantity) {
            throw new HttpException(400, "Product: {$product->sku} out of stock");
        }

        return $product;
    }

    public function getUpdatedStocks($sale) {
        $updatedStocks = [];

        foreach($sale->salesProducts as $saleProduct) {
            $product = $this->getAvailableProduct($saleProduct->product_id, $saleProduct->quantity);

            $updatedStocks[] = [
                'id' => $product->id,
                'stock' => $product->stock - $saleProduct->quantity
            ];
        }

        return $updatedStocks;
    }
}