<?php

namespace App\Repositories;

use App\Models\Sale;

class SaleRepository
{
    public function getAll() {
       return Sale::all();
    }

    public function getById($id) {
        return Sale::findOrFail($id);
    }

    public function getByIdWithProducts($id) {
        return Sale::with('products')->findOrFail($id);
    }

    public function create(array $data) {
        return Sale::create($data);
    }

    public function update($id, array $data) {
        $sale = Sale::findOrFail($id);
        $sale->update($data);

        return $sale;
    }

    public function forceDelete($id) {
        $sale = Sale::findOrFail($id);
        $sale->forceDelete();
    }
}