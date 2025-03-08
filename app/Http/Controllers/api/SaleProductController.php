<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\SaleProductService;

class SaleProductController extends Controller
{
    protected $saleProductService;

    public function __construct(SaleProductService $saleProductService) {
        $this->saleProductService = $saleProductService;
    }

    public function store(Request $request, $id) {
        $data = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer',
        ]);

        $saleProduct = $this->saleProductService->create($data, $id);

        return response()->json([
            'status' => 'success',
            'message' => 'Product added to sale successfully.',
            'sale_product' => $saleProduct
        ], 201);
    }

    public function delete($saleId, $productId) {
        $sale = $this->saleProductService->delete($saleId, $productId);

        return response()->json([
            'status' => 'success',
            'message' => 'Product removed from sale successfully.',
            'sale' => $sale
        ], 200);
    }
}
