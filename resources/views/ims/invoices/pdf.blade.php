<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice PDF</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 0;
            color: #333;
            line-height: 1.4;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 30px;
        }

        .header {
            border-bottom: 3px solid #2c3e50;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }

        .company-info {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }

        .company-details {
            flex: 1;
        }

        .company-name {
            font-size: 28px;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 8px;
            letter-spacing: 1px;
        }

        .company-tagline {
            font-size: 12px;
            color: #7f8c8d;
            font-style: italic;
            margin-bottom: 15px;
        }

        .company-address {
            font-size: 11px;
            color: #555;
            line-height: 1.5;
        }

        .invoice-title {
            text-align: right;
            flex: 0 0 200px;
        }

        .invoice-title h1 {
            font-size: 32px;
            color: #2c3e50;
            margin: 0;
            font-weight: bold;
        }

        .invoice-number {
            font-size: 14px;
            color: #e74c3c;
            font-weight: bold;
            margin-top: 5px;
        }

        .invoice-meta {
            display: flex;
            justify-content: space-between;
            margin: 30px 0;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            padding: 20px;
            border-radius: 8px;
            border-left: 5px solid #3498db;
        }

        .bill-to {
            flex: 1;
            margin-right: 30px;
        }

        .invoice-details {
            flex: 1;
        }

        .section-title {
            font-weight: bold;
            font-size: 14px;
            color: #2c3e50;
            margin-bottom: 10px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .customer-name {
            font-size: 16px;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 5px;
        }

        .table-container {
            margin: 30px 0;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
        }

        th {
            background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
            color: white;
            padding: 15px 10px;
            text-align: left;
            font-weight: bold;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        td {
            padding: 12px 10px;
            border-bottom: 1px solid #ecf0f1;
        }

        tr:nth-child(even) {
            background-color: #f8f9fa;
        }

        tr:hover {
            background-color: #e8f4f8;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .totals-section {
            margin-top: 30px;
            display: flex;
            justify-content: flex-end;
        }

        .totals-table {
            width: 400px;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .totals-table td {
            padding: 12px 20px;
            font-size: 13px;
        }

        .totals-table .label {
            background-color: #f8f9fa;
            font-weight: 600;
            color: #2c3e50;
        }

        .totals-table .amount {
            background-color: white;
            text-align: right;
            font-weight: bold;
        }

        .total-row .label,
        .total-row .amount {
            background: linear-gradient(135deg, #27ae60 0%, #229954 100%);
            color: white;
            font-size: 16px;
            font-weight: bold;
        }

        .terms-section {
            margin-top: 40px;
            padding: 20px;
            background-color: #f8f9fa;
            border-radius: 8px;
            border-left: 5px solid #f39c12;
        }

        .terms-title {
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 15px;
            font-size: 14px;
        }

        .terms-list {
            font-size: 11px;
            color: #555;
            line-height: 1.6;
        }

        .terms-list li {
            margin-bottom: 5px;
        }

        .signature-section {
            margin-top: 50px;
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
        }

        .payment-info {
            flex: 1;
            font-size: 11px;
            color: #555;
        }

        .signature {
            text-align: center;
            flex: 0 0 200px;
        }

        .signature-line {
            border-bottom: 2px solid #2c3e50;
            width: 150px;
            margin: 40px auto 10px;
        }

        .footer {
            margin-top: 40px;
            text-align: center;
            padding-top: 20px;
            border-top: 2px solid #ecf0f1;
        }

        .footer-text {
            font-size: 10px;
            color: #7f8c8d;
            line-height: 1.5;
        }

        .amount-words {
            background-color: #e8f6ff;
            padding: 15px;
            border-radius: 8px;
            margin: 20px 0;
            border-left: 5px solid #3498db;
        }

        .amount-words-label {
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 5px;
        }

        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 80px;
            color: rgba(52, 73, 94, 0.05);
            font-weight: bold;
            z-index: -1;
            pointer-events: none;
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- <div class="watermark">INVOICE</div> -->
        
        <div class="header">
            <div class="company-info">
                <div class="company-details">
                    <div class="company-name">{{ config('company.name') }}</div>
                    <div class="company-tagline">{{ config('company.tagline') }}</div>
                    <div class="company-address">
                        {{ config('company.address') }}<br>
                        Phone: {{ config('company.phone') }}<br>
                        Email: {{ config('company.email') }}<br>
                        @if(config('company.website'))
                            Website: {{ config('company.website') }}<br>
                        @endif
                        @if(config('company.gst_number'))
                            GST No: {{ config('company.gst_number') }}
                        @endif
                    </div>
                </div>
                <div class="invoice-title">
                    <h1>INVOICE</h1>
                    <div class="invoice-number">#{{ $invoice->invoice_no }}</div>
                    <div style="margin-top: 15px; text-align: center;">
                        <div style="border: 1px solid #ddd; padding: 5px; display: inline-block; background: #fff;">
                            <img src="{{ $qrCode }}" alt="QR Code" style="width: 80px; height: 80px;" />
                        </div>
                        <div style="font-size: 8px; color: #666; margin-top: 3px;">Scan to verify</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="invoice-meta">
            <div class="bill-to">
                <div class="section-title">Bill To</div>
                <div class="customer-name">{{ $invoice->customer->company_name }}</div>
                <div>
                    {{ $invoice->customer->address }}<br>
                    {{ $invoice->customer->city }}, {{ $invoice->customer->state }} - {{ $invoice->customer->zip_code }}<br>
                    {{ $invoice->customer->country }}<br>
                    @if ($invoice->customer->gst_number)
                        <strong>GST No:</strong> {{ $invoice->customer->gst_number }}
                    @endif
                </div>
            </div>
            <div class="invoice-details">
                <div class="section-title">Invoice Details</div>
                <table style="width: 100%; border: none; margin: 0;">
                    <tr style="background: none;">
                        <td style="border: none; padding: 3px 0; font-weight: bold;">Invoice Date:</td>
                        <td style="border: none; padding: 3px 0;">{{ \Carbon\Carbon::parse($invoice->invoice_date)->format('d M, Y') }}</td>
                    </tr>
                    <tr style="background: none;">
                        <td style="border: none; padding: 3px 0; font-weight: bold;">Due Date:</td>
                        <td style="border: none; padding: 3px 0;">{{ $invoice->due_date ? \Carbon\Carbon::parse($invoice->due_date)->format('d M, Y') : 'On Receipt' }}</td>
                    </tr>
                    <tr style="background: none;">
                        <td style="border: none; padding: 3px 0; font-weight: bold;">Payment Terms:</td>
                        <td style="border: none; padding: 3px 0;">{{ config('company.invoice.payment_terms') }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th style="width: 5%;">#</th>
                        <th style="width: 40%;">Description</th>
                        <th style="width: 10%;">Qty</th>
                        <th style="width: 15%;">Unit Price</th>
                        <th style="width: 15%;">Tax Amount</th>
                        <th style="width: 15%;">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($invoice->items as $item)
                        <tr>
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td>
                                @if ($item->product_id)
                                    <strong>{{ $item->product->name }}</strong>
                                @else
                                    <strong>{{ $item->service->name }}</strong>
                                @endif
                                @if ($item->description)
                                    <br><small style="color: #666; font-style: italic;">{{ $item->description }}</small>
                                @endif
                            </td>
                            <td class="text-center">{{ $item->quantity }}</td>
                            <td class="text-right">₹{{ number_format($item->unit_price, 2) }}</td>
                            <td class="text-right">₹{{ number_format($item->tax_amount, 2) }}</td>
                            <td class="text-right"><strong>₹{{ number_format($item->total, 2) }}</strong></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @php
            $amountInWords = \App\Helpers\NumberToWords::convert($invoice->total);
        @endphp

        <div class="amount-words">
            <div class="amount-words-label">Amount in Words:</div>
            <div style="font-style: italic; font-weight: bold;">{{ $amountInWords }} Rupees Only</div>
        </div>

        <div class="totals-section">
            <table class="totals-table">
                <tr>
                    <td class="label">Subtotal:</td>
                    <td class="amount">₹{{ number_format($invoice->sub_total, 2) }}</td>
                </tr>
                <tr>
                    <td class="label">Tax Amount:</td>
                    <td class="amount">₹{{ number_format($invoice->tax_amount, 2) }}</td>
                </tr>
                @if ($invoice->discount > 0)
                    <tr>
                        <td class="label">Discount:</td>
                        <td class="amount">-₹{{ number_format($invoice->discount, 2) }}</td>
                    </tr>
                @endif
                <tr class="total-row">
                    <td class="label">TOTAL AMOUNT:</td>
                    <td class="amount">₹{{ number_format($invoice->total, 2) }}</td>
                </tr>
            </table>
        </div>

        <div class="terms-section">
            <div class="terms-title">Terms & Conditions</div>
            <ol class="terms-list">
                <li>Payment is due within {{ $invoice->due_date ? \Carbon\Carbon::parse($invoice->due_date)->diffInDays(\Carbon\Carbon::parse($invoice->invoice_date)) : '30' }} days from the invoice date.</li>
                <li>Interest @ {{ config('company.invoice.late_fee_rate') }} will be charged on overdue amounts.</li>
                <li>All disputes are subject to {{ config('company.address') ? explode(',', config('company.address'))[0] : 'Local' }} jurisdiction only.</li>
                <li>Payment should be made in favor of "{{ config('company.name') }}".</li>
                <li>This invoice is computer generated and does not require physical signature.</li>
            </ol>
        </div>

        <div class="signature-section">
            <div class="payment-info">
                <strong>Payment Information:</strong><br>
                Account Name: {{ config('company.bank.account_name') }}<br>
                Bank: {{ config('company.bank.name') }}<br>
                Account No: {{ config('company.bank.account_number') }}<br>
                IFSC Code: {{ config('company.bank.ifsc_code') }}<br>
                <br>
                <strong>For any queries:</strong><br>
                Email: {{ config('company.email') }}<br>
                Phone: {{ config('company.phone') }}
            </div>
            <div class="signature">
                <div class="signature-line"></div>
                <div style="font-weight: bold; margin-top: 5px;">Authorized Signatory</div>
                <div style="font-size: 11px; color: #666;">{{ config('company.name') }}</div>
            </div>
        </div>

        <div class="footer">
            <div class="footer-text">
                <strong>{{ config('company.invoice.notes') }}</strong><br>
                This is a computer-generated invoice and does not require a signature.<br>
                {{ config('company.name') }} - {{ config('company.tagline') }}
            </div>
        </div>
    </div>
</body>

</html>
