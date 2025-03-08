<?php

namespace App\Services;

use App\Repositories\SaleRepository;
use App\Exports\SalesExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\File;

class SaleReportService
{
    protected $saleRepository;

    public function __construct(SaleRepository $saleRepository) {
        $this->saleRepository = $saleRepository;
    }

    public function generate($filters) {
        $startDate = $filters['start_date'];
        $endDate = $filters['end_date'];

        $data = $this->saleRepository->getByDateRange($startDate, $endDate);

        switch($filters['type']) {
            case 'json':
                return $this->generateJson($data);
            case 'xlsx':
                return $this->generateXlsx($data);
        }
    }

    private function generateJson($data) {
        $jsonData = json_encode($data);

        // Create a temporary file
        $filePath = storage_path('app/salesReport.json');
        File::put($filePath, $jsonData);

        // Return the file for download
        return response()->download($filePath)->deleteFileAfterSend(true);
    }

    private function generateXlsx($data) {
        return Excel::download(new SalesExport($data), 'salesReport.xlsx');
    }
}