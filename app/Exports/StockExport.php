<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class StockExport implements FromCollection, WithHeadings, WithEvents, ShouldAutoSize
{
    protected $stocks;
    protected $generatedAt;
    protected $dateRange;

    public function __construct(Collection $stocks, $generatedAt, $dateRange)
    {
        $this->stocks = $stocks;
        $this->generatedAt = $generatedAt;
        $this->dateRange = $dateRange;
    }

    public function collection()
    {
        if ($this->stocks->isEmpty()) {
            return collect([['No Stocks Found']]);
        }

        return $this->stocks->map(function ($stock, $index) {
            return [
                '#' => $index + 1,
                'Product Name' => $stock->product->name ?? 'N/A',
                'Supplier Name' => $stock->supplier->name ?? 'N/A',
                'Unit Type' => $stock->unit_type,
                'Quantity' => $stock->quantity,
                'Sold' => $stock->sold,
                'Batch Code' => $stock->batch_code,
                'Created At' => $stock->created_at->format('Y-m-d'),
            ];
        });
    }

    public function headings(): array
    {
        return $this->stocks->isEmpty() ? [] : [
            '#',
            'Product Name',
            'Supplier Name',
            'Unit Type',
            'Quantity',
            'Sold',
            'Batch Code',
            'Created At',
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet;

                // Add metadata: Generated At + Date Range above the table
                $sheet->insertNewRowBefore(1, 2);

                $sheet->insertNewRowBefore(1, 3);

                // Add company details
                $sheet->setCellValue('A1', 'SKM AND COMPANY');
                $sheet->setCellValue('A2', '32/1, Adhi Selvan Street, Ammapet, Salem - 636 003');
                $sheet->getStyle('A1:A2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $sheet->mergeCells('A1:H1');
                $sheet->mergeCells('A2:H2');
                $sheet->getStyle('A1:A2')->getFont()->setBold(true);

                // Add metadata
                $sheet->setCellValue('A3', 'Generated At: ' . $this->generatedAt);
                $sheet->setCellValue('A4', 'Selected Range: ' . $this->dateRange);
                $sheet->mergeCells('A3:H3');
                $sheet->mergeCells('A4:H4');
                $sheet->getStyle('A3:A4')->getFont()->setBold(true);
            }
        ];
    }
}
