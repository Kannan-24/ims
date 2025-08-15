<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice PDF</title>
    <style>
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 0;
            color: #333;
        }

        .container {
            max-width: 800px;
            margin: auto;
            padding: 30px;
        }

        .header {
            border-bottom: 3px solid #2c3e50;
            padding-bottom: 15px;
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
        }

        .company-details {
            max-width: 60%;
        }

        .company-name {
            font-size: 24px;
            font-weight: bold;
            color: #2c3e50;
        }

        .company-tagline {
            font-size: 11px;
            color: #888;
            margin-bottom: 8px;
        }

        .company-address {
            font-size: 11px;
            color: #555;
            line-height: 1.5;
        }

        .invoice-title {
            text-align: right;
        }

        .invoice-title h1 {
            margin: 0;
            font-size: 28px;
            color: #2c3e50;
        }

        .invoice-number {
            font-weight: bold;
            color: #e74c3c;
            margin: 5px 0;
        }

        .qr-code {
            border: 1px solid #ddd;
            padding: 4px;
            background: #fff;
            display: inline-block;
        }

        .invoice-meta {
            display: flex;
            justify-content: space-between;
            margin: 20px 0;
        }

        .section-title {
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 8px;
            text-transform: uppercase;
            font-size: 13px;
        }

        .table-container {
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: #fff;
        }

        th {
            background: #34495e;
            color: #fff;
            padding: 10px;
            font-size: 11px;
            text-align: left;
        }

        td {
            padding: 8px;
            border-bottom: 1px solid #eee;
            font-size: 12px;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .text-right {
            text-align: right;
        }

        .amount-words {
            background: #e8f6ff;
            padding: 10px;
            border-left: 4px solid #3498db;
            margin: 20px 0;
            font-size: 12px;
        }

        .totals {
            width: 300px;
            margin-left: auto;
        }

        .totals td {
            padding: 8px;
        }

        .totals .label {
            font-weight: bold;
            background: #f8f9fa;
        }

        .totals .total-row {
            background: #27ae60;
            color: #fff;
            font-weight: bold;
        }

        .terms {
            margin-top: 30px;
            font-size: 11px;
            color: #555;
        }

        .signature-section {
            margin-top: 40px;
            display: flex;
            justify-content: space-between;
        }

        .signature-line {
            border-bottom: 2px solid #333;
            width: 150px;
            margin: auto;
        }
    </style>
</head>

<body>
    <div class="container">

        <!-- Header -->
        <div class="header">
            <div class="company-details">
                <div class="company-name">{{ config('company.name') }}</div>
                <div class="company-tagline">{{ config('company.tagline') }}</div>
                <div class="company-address">
                    {{ config('company.address') }}<br>
                    Phone: {{ config('company.phone') }} | Email: {{ config('company.email') }}<br>
                    @if (config('company.website'))
                        Website: {{ config('company.website') }}<br>
                    @endif
                    @if (config('company.gst_number'))
                        GST: {{ config('company.gst_number') }}
                    @endif
                </div>
            </div>
            <div class="invoice-title">
                <h1>INVOICE</h1>
                <div class="invoice-number">#{{ $invoice->invoice_no }}</div>
                <div class="qr-code">
                    <img src="{{ $qrCode }}" style="width:80px; height:80px;">
                </div>
                <div style="font-size:8px; color:#666;">Scan to verify</div>
            </div>
        </div>

        <!-- Bill To -->
        <div class="invoice-meta">
            <div>
                <div class="section-title">Bill To</div>
                <strong>{{ $invoice->customer->company_name }}</strong><br>
                {{ $invoice->customer->address }}<br>
                {{ $invoice->customer->city }}, {{ $invoice->customer->state }} -
                {{ $invoice->customer->zip_code }}<br>
                {{ $invoice->customer->country }}<br>
                @if ($invoice->customer->gst_number)
                    GST: {{ $invoice->customer->gst_number }}
                @endif
            </div>
            <div>
                <div class="section-title">Invoice Details</div>
                Date: {{ \Carbon\Carbon::parse($invoice->invoice_date)->format('d M, Y') }}<br>
                Due:
                {{ $invoice->due_date ? \Carbon\Carbon::parse($invoice->due_date)->format('d M, Y') : 'On Receipt' }}<br>
                Payment Terms: {{ config('company.invoice.payment_terms') }}
            </div>
        </div>

        <!-- Product Table -->
        @php $products = $invoice->items->whereNotNull('product_id'); @endphp
        @if ($products->count() > 0)
            <div class="table-container">
                <div class="section-title">Products</div>
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Description</th>
                            <th class="text-right">Qty</th>
                            <th class="text-right">Unit Price</th>
                            <th class="text-right">Tax</th>
                            <th class="text-right">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($products as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item->product->name }}<br>
                                    @if ($item->description)
                                        <small>{{ $item->description }}</small>
                                    @endif
                                </td>
                                <td class="text-right">{{ $item->quantity }}</td>
                                <td class="text-right">₹{{ number_format($item->unit_price, 2) }}</td>
                                <td class="text-right">₹{{ number_format($item->tax_amount, 2) }}</td>
                                <td class="text-right">₹{{ number_format($item->total, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

        <!-- Service Table -->
        @php $services = $invoice->items->whereNotNull('service_id'); @endphp
        @if ($services->count() > 0)
            <div class="table-container">
                <div class="section-title">Services</div>
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Description</th>
                            <th class="text-right">Qty</th>
                            <th class="text-right">Unit Price</th>
                            <th class="text-right">Tax</th>
                            <th class="text-right">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($services as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item->service->name }}<br>
                                    @if ($item->description)
                                        <small>{{ $item->description }}</small>
                                    @endif
                                </td>
                                <td class="text-right">{{ $item->quantity }}</td>
                                <td class="text-right">₹{{ number_format($item->unit_price, 2) }}</td>
                                <td class="text-right">₹{{ number_format($item->tax_amount, 2) }}</td>
                                <td class="text-right">₹{{ number_format($item->total, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

        <!-- Amount in Words -->
        @php $amountInWords = \App\Helpers\NumberToWords::convert($invoice->total); @endphp
        <div class="amount-words"><strong>Amount in Words:</strong> {{ $amountInWords }} Rupees Only</div>

        <!-- Totals -->
        <table class="totals">
            <tr>
                <td class="label">Subtotal</td>
                <td class="text-right">₹{{ number_format($invoice->sub_total, 2) }}</td>
            </tr>
            <tr>
                <td class="label">Tax</td>
                <td class="text-right">₹{{ number_format($invoice->tax_amount, 2) }}</td>
            </tr>
            @if ($invoice->discount > 0)
                <tr>
                    <td class="label">Discount</td>
                    <td class="text-right">-₹{{ number_format($invoice->discount, 2) }}</td>
                </tr>
            @endif
            <tr class="total-row">
                <td>Total</td>
                <td class="text-right">₹{{ number_format($invoice->total, 2) }}</td>
            </tr>
        </table>

        <!-- Terms -->
        <div class="terms">
            <strong>Terms & Conditions:</strong>
            <ol>
                <li>Payment is due within
                    {{ $invoice->due_date ? \Carbon\Carbon::parse($invoice->due_date)->diffInDays($invoice->invoice_date) : 30 }}
                    days.</li>
                <li>Interest @ {{ config('company.invoice.late_fee_rate') }} will be charged on overdue amounts.</li>
                <li>All disputes are subject to {{ explode(',', config('company.address'))[0] ?? 'Local' }}
                    jurisdiction only.</li>
            </ol>
        </div>

        <!-- Signature -->
        <div class="signature-section">
            <div>
                <strong>Payment Info:</strong><br>
                Account: {{ config('company.bank.account_name') }}<br>
                Bank: {{ config('company.bank.name') }}<br>
                A/C No: {{ config('company.bank.account_number') }}<br>
                IFSC: {{ config('company.bank.ifsc_code') }}
            </div>
            <div style="text-align:center;">
                <div class="signature-line"></div>
                <div>Authorized Signatory</div>
                <div style="font-size:11px; color:#666;">{{ config('company.name') }}</div>
            </div>
        </div>

    </div>
</body>

</html>
