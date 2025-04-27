<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Events\AfterSheet;

class PurchaseExport implements FromCollection, WithHeadings, WithEvents, ShouldAutoSize
{
    protected $purchases;
    protected $generatedAt;
    protected $dateRange;

    public function __construct(Collection $purchases, $generatedAt, $dateRange)
    {
        $this->purchases = $purchases;
        $this->generatedAt = $generatedAt;
        $this->dateRange = $dateRange;
    }

    public function collection()
    {
        if ($this->purchases->isEmpty()) {
            return collect([['No Purchases Found']]);
        }

        return $this->purchases->map(function ($purchase, $index) {
            return [
                '#' => $index + 1,
                'Invoice Number' => $purchase->invoice_no,
                'Invoice Date' => $purchase->invoice_date,
                'Supplier Name' => $purchase->supplier->name ?? 'N/A',
                'Supplier GST' => $purchase->supplier->gst_number ?? 'N/A',
                'Sub Total' => number_format($purchase->sub_total, 2),
                'CGST' => number_format($purchase->cgst, 2),
                'SGST' => number_format($purchase->sgst, 2),
                'IGST' => number_format($purchase->igst, 2),
                'GST' => number_format($purchase->gst, 2),
                'Total' => number_format($purchase->total, 2),
            ];
        });
    }

    public function headings(): array
    {
        return $this->purchases->isEmpty() ? [] : [
            '#',
            'Invoice Number',
            'Invoice Date',
            'Supplier Name',
            'Supplier GST',
            'Sub Total',
            'CGST',
            'SGST',
            'IGST',
            'GST',
            'Total',
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
                $sheet->mergeCells('A1:K1');
                $sheet->mergeCells('A2:K2');
                $sheet->getStyle('A1:A2')->getFont()->setBold(true);

                // Add metadata
                $sheet->setCellValue('A3', 'Generated At: ' . $this->generatedAt);
                $sheet->setCellValue('A4', 'Selected Range: ' . $this->dateRange);
                $sheet->mergeCells('A3:K3');
                $sheet->mergeCells('A4:K4');
                $sheet->getStyle('A3:A4')->getFont()->setBold(true);
            }
        ];
    }
}
