<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\SaleReportService;

class SaleReportController extends Controller
{
    protected $saleReportService;

    public function __construct(SaleReportService $saleReportService) {
        $this->saleReportService = $saleReportService;
    }

    public function generate(Request $request) {
        $filters = $request->validate([
            'type' => 'required|in:json,xlsx',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date'
        ]);

        $file = $this->saleReportService->generate($filters);

        return $file;
    }
}
