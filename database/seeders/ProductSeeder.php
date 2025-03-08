<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use DB;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('products')->insert([
            [
                'sku' => 'SKU-1',
                'name' => 'Product 1',
                'price' => 100,
                'stock' => 10,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'sku' => 'SKU-2',
                'name' => 'Product 2',
                'price' => 200,
                'stock' => 20,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
