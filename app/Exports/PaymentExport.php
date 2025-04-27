<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Events\AfterSheet;

class PaymentExport implements FromCollection, WithHeadings, WithEvents, ShouldAutoSize
{
    protected $payments;
    protected $generatedAt;
    protected $dateRange;

    public function __construct(Collection $payments, $generatedAt, $dateRange)
    {
        $this->payments = $payments;
        $this->generatedAt = $generatedAt;
        $this->dateRange = $dateRange;
    }

    public function collection()
    {
        if ($this->payments->isEmpty()) {
            return collect([['No Payment Records Found']]);
        }

        return $this->payments->map(function ($payment, $index) {
            $paymentDates = $payment->paymentItems->pluck('payment_date')->map(function ($date) {
                return \Carbon\Carbon::parse($date)->format('d-m-Y');
            })->implode("\n");

            $amountsPaid = $payment->paymentItems->pluck('amount')->map(function ($amount) {
                return number_format($amount, 2);
            })->implode("\n");

            $paymentMethods = $payment->paymentItems->pluck('payment_method')->map(function ($method) {
                return ucfirst($method);
            })->implode("\n");

            $referenceNumbers = $payment->paymentItems->pluck('reference_number')->implode("\n");

            return [
                '#' => $index + 1,
                'Invoice No' => $payment->invoice->invoice_no ?? 'N/A',
                'Invoice Date' => $payment->invoice ? \Carbon\Carbon::parse($payment->invoice->invoice_date)->format('d-m-Y') : 'N/A',
                'Company Name' => $payment->invoice->customer->company_name ?? 'N/A',
                'Total Amount' => number_format($payment->total_amount, 2),
                'Pending Amount' => number_format($payment->pending_amount, 2),
                'Status' => ucfirst($payment->status),
                'Payment Date(s)' => $paymentDates ?: 'N/A',
                'Amount Paid(s)' => $amountsPaid ?: 'N/A',
                'Payment Method(s)' => $paymentMethods ?: 'N/A',
                'Reference Number(s)' => $referenceNumbers ?: 'N/A',
            ];
        });
    }

    public function headings(): array
    {
        return $this->payments->isEmpty() ? [] : [
            '#',
            'Invoice No',
            'Invoice Date',
            'Company Name',
            'Total Amount',
            'Pending Amount',
            'Status',
            'Payment Date(s)',
            'Amount Paid(s)',
            'Payment Method(s)',
            'Reference Number(s)',
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet;

                // Insert 4 rows before the headings for Company Information
                $sheet->insertNewRowBefore(1, 4);

                // Company Name and Address
                $sheet->setCellValue('A1', 'SKM AND COMPANY');
                $sheet->setCellValue('A2', '32/1, Adhi Selvan Street, Ammapet, Salem - 636 003');
                $sheet->mergeCells('A1:L1');
                $sheet->mergeCells('A2:L2');
                $sheet->getStyle('A1:A2')->getFont()->setBold(true)->setSize(14);
                $sheet->getStyle('A1:A2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

                // Metadata Info
                $sheet->setCellValue('A3', 'Generated At: ' . $this->generatedAt);
                $sheet->setCellValue('A4', 'Selected Date Range: ' . $this->dateRange);
                $sheet->mergeCells('A3:L3');
                $sheet->mergeCells('A4:L4');
                $sheet->getStyle('A3:A4')->getFont()->setBold(true)->setSize(12);

                // Enable wrap text for columns having multi-line values (Payment Details)
                foreach (range('H', 'K') as $col) {
                    $sheet->getDelegate()->getStyle($col)->getAlignment()->setWrapText(true);
                }

                // Optional: Bold the main headings (Row 5)
                $sheet->getStyle('A5:K5')->getFont()->setBold(true);
            }
        ];
    }
}
