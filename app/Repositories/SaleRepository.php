<?php

namespace App\Repositories;

use App\Models\Sale;
use DB; 

class SaleRepository
{
    public function getAll() {
       return Sale::all();
    }

    public function getByDateRange($startDate, $endDate) {
        $sales = Sale::select(
            'sales.code',
            'sales.customer_name',
            'sales.customer_id',
            'sales.customer_email',
            DB::raw('COUNT(sales_products.id) as products_count'),
            'sales.total',
            DB::raw("DATE_FORMAT(sales.updated_at, '%Y-%m-%d %h:%i%p') as confirmed_at") 
        )
        ->leftJoin('sales_products', 'sales.id', '=', 'sales_products.sale_id')
        ->where('sales.confirmed', 1)
        ->when($startDate, function ($query) use ($startDate) {
            return $query->where('sales.updated_at', '>=', $startDate);
        })
        ->when($endDate, function ($query) use ($endDate) {
            return $query->where('sales.updated_at', '<=', $endDate);
        })
        ->groupBy(
            'sales.id', 
            'sales.code', 
            'sales.customer_name', 
            'sales.customer_id', 
            'sales.customer_email', 
            'sales.total', 
            'sales.updated_at'
        )
        ->get();

        return $sales;
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