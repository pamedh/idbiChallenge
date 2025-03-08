<?php

namespace App\Services;

use Symfony\Component\HttpKernel\Exception\HttpException;
use App\Repositories\SaleRepository;
use App\Repositories\ProductRepository;
use App\Services\SaleValidatorService;
use Illuminate\Support\Str;
use DB;

class SaleService
{
    protected $saleRepository, $saleValidatorService;

    public function __construct(
        SaleRepository $saleRepository,
        SaleValidatorService $saleValidatorService,
        ProductRepository $productRepository
    ) {
        $this->saleRepository = $saleRepository;
        $this->saleValidatorService = $saleValidatorService;
        $this->productRepository = $productRepository;
    }

    public function getAll() {
        $sales = $this->saleRepository->getAll();
        
        return $sales;
    }

    public function getByIdWithProducts ($id) {
        $sale = $this->saleRepository->getByIdWithProducts($id);

        return $sale;
    }

    public function create(array $data) {
        $code = $this->generateCode();
        $data['code'] = $code;
        $data['seller_id'] = auth()->user()->id;
        $data['total'] = 0;

        $sale = $this->saleRepository->create($data);

        return $sale;
    }

    //Cancel sale without products
    public function cancelSale($id) {
        $sale = $this->saleRepository->getById($id);

        if ($sale->status == 'confirmed') {
            throw new HttpException(400, 'Sale already confirmed');
        }

        if ($sale->products->count() > 0) {
            throw new HttpException(400, 'Sale has products. Unable to cancel.');
        }

        $this->saleRepository->forceDelete($id);
    }

    //Sell
    public function confirmSale($id) {
        try {
            DB::beginTransaction();

            $sale = $this->saleRepository->getById($id);

            if ($sale->status == 'confirmed') {
                throw new HttpException(400, 'Sale already confirmed');
            }

            if ($sale->salesProducts->count() == 0) {
                throw new HttpException(400, 'Sale has no products. Unable to confirm.');
            }           

            //Validate and rested stock
            $updatedStocks = $this->saleValidatorService->getUpdatedStocks($sale);

            //Save updated stock
            foreach($updatedStocks as $updatedStock) {
                $this->productRepository->update($updatedStock['id'], ['stock' => $updatedStock['stock']]);
            }

            //Update sale status
            $sale->update(['status' => 'confirmed']);

            DB::commit();

            return $sale;
        }
        catch(\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    private function generateCode() {
        return Str::uuid()->toString();
    }
}