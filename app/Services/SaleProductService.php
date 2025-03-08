<?php

namespace App\Services;

use Symfony\Component\HttpKernel\Exception\HttpException;
use App\Repositories\SaleProductRepository;
use App\Repositories\SaleRepository;
use App\Services\SaleValidatorService;
use DB;

class SaleProductService
{
    protected $saleProductRepository, $saleValidatorService, $saleRepository;

    public function __construct(
        SaleProductRepository $saleProductRepository,
        SaleValidatorService $saleValidatorService,
        SaleRepository $saleRepository
    ) {
        $this->saleProductRepository = $saleProductRepository;
        $this->saleValidatorService = $saleValidatorService;
        $this->saleRepository = $saleRepository;
    }

    public function create(array $data, $id) {
        try {
            DB::beginTransaction();

            $sale = $this->saleRepository->getById($id);

            if ($sale->confirmed == 1) {
                throw new HttpException(400, 'Sale already confirmed');
            }

            $product = $this->saleValidatorService->getAvailableProduct($data['product_id'], $data['quantity']);

            $existingSaleProduct = $this->saleProductRepository->findBySaleIdAndProductId($sale->id, $data['product_id']);
            $total = $this->calculateTotal($data['quantity'], $product->price);
          
            if($existingSaleProduct) {
                $existingSaleProduct->quantity += $data['quantity'];
                $existingSaleProduct->total += $total;
                $existingSaleProduct->save();
            }
            else {
                $data['total'] = $total;
                $data['sale_id'] = $sale->id;

                $existingSaleProduct = $this->saleProductRepository->create($data);
            }

            $sale->total += $total;
            $sale->save();

            DB::commit();

            return $existingSaleProduct;
        
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function delete($saleId, $productId) {
        try {
            DB::beginTransaction();

            $sale = $this->saleRepository->getById($saleId);

            if ($sale->confirmed == 1) {
                throw new HttpException(400, 'Sale already confirmed');
            }

            $saleProduct = $this->saleProductRepository->findBySaleIdAndProductId($saleId, $productId);

            if (!$saleProduct) {
                throw new HttpException(404, 'Product not found in sale');
            }

            $sale->total -= $saleProduct->total;
            $sale->save();

            $saleProduct->forceDelete();

            DB::commit();

            return $sale;

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
    
    private function calculateTotal($quantity, $price) {
        return $quantity * $price;
    }
}