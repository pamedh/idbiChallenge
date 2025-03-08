<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\ProductService;

class ProductController extends Controller
{
    protected $productService;

    public function __construct(ProductService $productService) {
        $this->productService = $productService;
    }

    public function index() {
        $products = $this->productService->getAll();

        return response()->json([
            'status' => 'success',
            'products' => $products
        ], 200);
    }

    public function show($id) {
        $product = $this->productService->getById($id);

        return response()->json([
            'status' => 'success',
            'product' => $product
        ], 200);
    }

    public function store(Request $request) {
        $data = $request->validate([
            'sku' => 'required|string|unique:products,sku',
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
        ]);

        $product = $this->productService->create($data);

        return response()->json([
            'status' => 'success',
            'message' => 'Product created successfully',
            'product' => $product
        ], 201);
    }

    public function update(Request $request, $id) {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
        ]);

        $product = $this->productService->update($id, $data);
        
        return response()->json([
            'status' => 'success',
            'message' => 'Product updated successfully',
            'product' => $product
        ], 200);
    }

    public function delete($id) {
        $this->productService->delete($id);
        
        return response()->json([
            'status' => 'success',
            'message' => 'Product deleted successfully',
        ], 200);
    }
}
