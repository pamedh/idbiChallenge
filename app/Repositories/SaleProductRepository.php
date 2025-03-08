<?php

namespace App\Repositories;

use App\Models\SaleProduct;

class SaleProductRepository
{
    public function create(array $data) {
        return SaleProduct::create($data);
    }

    public function findBySaleIdAndProductId($saleId, $productId) {
        return SaleProduct::where('sale_id', $saleId)
            ->where('product_id', $productId)
            ->first();
    }
}