<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\SaleService;

class SaleController extends Controller
{
    protected $saleService;

    public function __construct(SaleService $saleService) {
        $this->saleService = $saleService;
    }

    public function index() {
        $sales = $this->saleService->getAll();

        return response()->json([
            'status' => 'success',
            'sales' => $sales
        ], 200);
    }

    public function showWithProducts($id) {
        $sale = $this->saleService->getByIdWithProducts($id);

        return response()->json([
            'status' => 'success',
            'sale' => $sale
        ], 200);
    }

    public function store(Request $request) {
        $data = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_id' => 'required|string|max:255',
            'customer_email' => 'nullable|email',
        ]);

        $sale = $this->saleService->create($data);

        return response()->json([
            'status' => 'success',
            'message' => 'Sale registered successfully.',
            'sale' => $sale
        ], 201);
    }

    public function cancel($id) {
        $this->saleService->cancelSale($id);

        return response()->json([
            'status' => 'success',
            'message' => 'Sale canceled successfully.',
        ], 200);
    }

    public function confirm($id) {
        $sale = $this->saleService->confirmSale($id);

        return response()->json([
            'status' => 'success',
            'message' => 'Sale confirmed successfully.',
            'sale' => $sale
        ], 200);
    }
}
