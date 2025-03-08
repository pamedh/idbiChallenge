<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class SalesExport implements
    FromCollection,
    WithHeadings,
    ShouldAutoSize,
    WithEvents
{
    protected $sales;

    public function __construct($sales) {
        $this->sales = $sales;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection(){
        return $this->sales;
    }

    public function headings(): array {
        return [
            'Código',
            'Nombre Cliente',
            'Identificación Cliente',
            'Correo Cliente',
            'Cantidad de Productos',
            'Monto Total',
            'Fecha y Hora',
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                // Bold headers
                $event->sheet->getDelegate()->getStyle('A1:Z1')->getFont()->setBold(true);
            },
        ];
    }
}
