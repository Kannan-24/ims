<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Invoice</title>
    <style>
        @page {
            size: A4;
            margin: 5mm;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            margin: 0;
            padding: 0;
            font-size: 12px;
            height: 100%;
            line-height: 1.3;
        }

        .container {
            border: 2px solid #000;
            width: 100%;
            min-height: 285.5mm;
        }

        .header {
            border-bottom: 2px solid #000;
            text-align: center;
            padding: 8px 0;
            position: relative;
        }

        .logo {
            position: absolute;
            left: 220px;
            top: 10px;
            width: 60px;
            height: 60px;
        }

        .company {
            font-size: 18px;
            font-weight: bold;
        }

        .address {
            font-size: 11px;
        }

        .info-row {
            border-bottom: 2px solid #000;
            font-size: 11px;
            padding: 2px 9px;
            overflow: hidden;
        }

        .info-left {
            float: left;
            width: 33.33%;
        }

        .info-middle {
            float: left;
            width: 33.33%;
            text-align: center;
        }

        .info-right {
            float: right;
            width: 33.33%;
            text-align: right;
        }

        .details-section {
            border-bottom: 2px solid #000;
            padding: 0;
            height: 140px;
            font-size: 12px;
            overflow: hidden;
            display: table;
            width: 100%;
        }

        .customer-details {
            display: table-cell;
            width: 50%;
            border-right: 2px solid #000;
            padding: 10px 15px 10px 10px;
            vertical-align: top;
        }

        .invoice-details {
            display: table-cell;
            width: 33%;
            padding: 10px 10px 10px 15px;
            border-right: 2px solid #000;
            vertical-align: top;
        }

        .qr-section {
            display: table-cell;
            width: 17%;
            text-align: center;
            padding: 10px 5px;
            vertical-align: middle;
        }

        .qr-code img {
            border: 1px solid #ddd;
            padding: 2px;
            display: block;
            margin: 0 auto;
        }

        .qr-label {
            font-size: 8px;
            margin-top: 8px;
            color: #666;
            text-align: center;
        }

        .label {
            font-weight: bold;
        }

        .detail-line {
            margin-bottom: 3px;
        }

        .main-content {
            padding: 10px 10px;
        }

        .section-title {
            font-size: 12px;
            font-weight: bold;
            margin: 0px 0 2px 0;
        }

        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }

        .items-table th,
        .items-table td {
            border: 1px solid #000;
            padding: 4px 2px;
            font-size: 9px;
            text-align: center;
            vertical-align: middle;
        }

        .items-table th {
            background-color: #f0f0f0;
            font-weight: bold;
            font-size: 8px;
        }

        .desc-col {
            text-align: left !important;
            width: 30%;
            padding-left: 5px !important;
        }

        .amount-col {
            text-align: right !important;
            width: 12%;
            padding-right: 5px !important;
        }

        .sno-col {
            width: 4%;
        }

        .hsn-col {
            width: 8%;
        }

        .qty-col {
            width: 6%;
        }

        .rate-col {
            width: 10%;
            padding-right: 5px !important;
        }

        .gst-col {
            width: 6%;
        }

        .tax-col {
            width: 8%;
        }

        .summary-table {
            width: 100%;
            border-collapse: collapse;
            margin: 0px 0;
        }

        .amount-words {
            font-size: 12px;
            margin: 15px 0;
        }

        .footer-section {
            margin-top: 15px;
            border: 1px solid #000;
        }

        .footer-table {
            width: 100%;
            border-collapse: collapse;
        }

        .left-content {
            width: 60%;
            padding: 10px 15px;
            vertical-align: top;
            border-right: 1px solid #000;
            font-size: 11px;
            line-height: 1.4;
        }

        .right-content {
            width: 40%;
            padding: 15px;
            vertical-align: top;
            font-size: 11px;
        }

        .totals-table {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #000;
            margin-bottom: 10px;
        }

        .totals-table th,
        .totals-table td {
            border: 1px solid #000 !important;
            padding: 8px 12px;
        }

        .totals-table tr:last-child td {
            border-bottom: 1px solid #000 !important;
        }

        .totals-table td {
            padding: 8px 12px;
            border-bottom: 1px solid #ccc;
        }

        .total-label {
            text-align: left;
            width: 60%;
        }

        .total-amount {
            text-align: right;
            width: 40%;
            font-weight: bold;
        }

        .total-final {
            background-color: #f0f0f0;
            font-weight: bold;
        }

        .total-words-box {
            border: 1px solid #000;
            padding: 10px;
            background-color: #f8f8f8;
            font-size: 10px;
            line-height: 1.3;
        }

        .signature-area {
            text-align: right;
            margin: 30px 15px 15px 0;
            font-size: 11px;
        }

        .clearfix::after {
            content: "";
            display: table;
            clear: both;
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            @if (file_exists(public_path('assets/logo.png')))
                <img src="{{ public_path('assets/logo.png') }}" alt="Logo" class="logo">
            @endif
            <div class="company">{{ config('company.name') }}</div>
            <div class="address">
                {!! nl2br(e(config('company.address'))) !!}<br>
                Phone: {{ config('company.phone') }} | Email: {{ config('company.email') }}
            </div>
        </div>

        <!-- Info Row -->
        <div class="info-row clearfix">
            <div class="info-left">GSTIN : {{ config('company.gst_number') ?? 'N/A' }}</div>
            <div class="info-middle">Website : {{ config('company.website') ?? 'N/A' }}</div>
            <div class="info-right">Ph : {{ config('company.phone') }}</div>
        </div>

        <!-- Details Section -->
        <div class="details-section">
            <div class="customer-details">
                <div class="detail-line"><span class="label">Bill To:</span></div>
                <div class="detail-line"><span class="label">Name :</span> {{ $invoice->customer->company_name }}</div>
                <div class="detail-line"><span class="label">Address :</span>
                    {{ $invoice->customer->address }}, {{ $invoice->customer->city }}, {{ $invoice->customer->state }}
                    -
                    {{ $invoice->customer->zip_code }}, {{ $invoice->customer->country }}
                </div>
                <div class="detail-line"><span class="label">GSTIN :</span>
                    {{ $invoice->customer->gst_number ?? 'N/A' }}</div>
                <div class="detail-line"><span class="label">Contact Person :</span>
                    {{ $invoice->customer->contactPersons->first()->name ?? 'N/A' }}</div>
                <div class="detail-line"><span class="label">Place of Supply :</span>
                    {{ $invoice->customer->city ?? 'N/A' }}</div>
            </div>

            <div class="invoice-details">
                <div class="detail-line"><span class="label">Invoice No :</span> {{ $invoice->invoice_no }}</div>
                <div class="detail-line"><span class="label">Invoice Date :</span>
                    {{ \Carbon\Carbon::parse($invoice->invoice_date)->format('d-m-Y') }}</div>
                <div class="detail-line"><span class="label">Customer ID :</span>
                    {{ $invoice->customer->cid ?? 'N/A' }}</div>
                <div class="detail-line"><span class="label">Due Date :</span>
                    {{ $invoice->due_date ? \Carbon\Carbon::parse($invoice->due_date)->format('d-m-Y') : 'On Receipt' }}
                </div>
                <div class="detail-line"><span class="label">Payment Terms :</span>
                    {{ config('company.invoice.payment_terms') ?? 'N/A' }}</div>
            </div>

            <div class="qr-section">
                <div class="qr-code">
                    <img src="{{ $qrCode }}" alt="QR Code" width="90" height="90">
                    <div class="qr-label">Scan to Verify</div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            @php
                $productItems = $invoice->items->filter(fn($i) => $i->product_id !== null);
                $serviceItems = $invoice->items->filter(fn($i) => $i->service_id !== null);
                $prodHasDiscount = $productItems->contains(fn($it) => ($it->discount_amount ?? 0) > 0);
                $servHasDiscount = $serviceItems->contains(fn($it) => ($it->discount_amount ?? 0) > 0);
            @endphp

            {{-- PRODUCT TABLE --}}
            @if ($productItems->count())
                <div class="section-title">Product Items</div>
                <table class="items-table">
                    <thead>
                        <tr>
                            <th class="sno-col">S.NO</th>
                            <th class="desc-col">DESCRIPTION</th>
                            <th class="hsn-col">HSN/SAC</th>
                            <th class="qty-col">QTY</th>
                            <th class="rate-col">Taxable Value</th>
                            @if ($prodHasDiscount)
                                <th class="gst-col">DISC</th>
                                <th class="tax-col">Tax Val after DISC</th>
                            @endif
                            <th class="gst-col">CGST%</th>
                            <th class="tax-col">CGST</th>
                            <th class="gst-col">SGST%</th>
                            <th class="tax-col">SGST</th>
                            <th class="gst-col">IGST%</th>
                            <th class="tax-col">IGST</th>
                            <th class="amount-col">AMOUNT</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($productItems as $item)
                            <tr>
                                <td class="sno-col">{{ $loop->iteration }}</td>
                                <td class="desc-col" style="font-size:10px;">
                                    {{ $item->product->name }}@if ($item->description)
                                        - {{ $item->description }}
                                    @endif
                                </td>
                                <td class="hsn-col">{{ $item->product->hsn_code }}</td>
                                <td class="qty-col">{{ number_format($item->quantity) }}</td>
                                <td class="rate-col">{{ number_format($item->unit_price * $item->quantity, 2) }}</td>

                                @if ($prodHasDiscount)
                                    <td class="gst-col">
                                        {{ ($item->discount_amount ?? 0) > 0 ? number_format($item->discount_amount, 2) : '' }}
                                    </td>
                                    <td class="tax-col">
                                        {{ ($item->discount_amount ?? 0) > 0 ? number_format($item->taxable_amount ?? $item->unit_price * $item->quantity, 2) : '-' }}
                                    </td>
                                @endif

                                <td class="gst-col">
                                    {{ !$item->product->is_igst ? $item->product->gst_percentage / 2 . '%' : '-' }}
                                </td>
                                <td class="tax-col">
                                    {{ !$item->product->is_igst ? number_format($item->cgst, 2) : '0.00' }}</td>
                                <td class="gst-col">
                                    {{ !$item->product->is_igst ? $item->product->gst_percentage / 2 . '%' : '-' }}
                                </td>
                                <td class="tax-col">
                                    {{ !$item->product->is_igst ? number_format($item->sgst, 2) : '0.00' }}</td>
                                <td class="gst-col">
                                    {{ $item->product->is_igst ? $item->product->gst_percentage . '%' : '-' }}</td>
                                <td class="tax-col">
                                    {{ $item->product->is_igst ? number_format($item->igst, 2) : '0.00' }}</td>
                                <td class="amount-col">{{ number_format($item->total, 2) }}</td>
                            </tr>
                        @endforeach

                        {{-- subtotal --}}
                        <tr style="font-weight:bold;">
                            <td colspan="{{ $prodHasDiscount ? 3 : 5 }}" class="desc-col">SUB TOTAL</td>
                            <td class="qty-col">{{ number_format($productItems->sum('quantity')) }}</td>
                            <td class="rate-col">
                                {{ number_format($productItems->sum(fn($i) => $i->unit_price * $i->quantity), 2) }}
                            </td>
                            @if ($prodHasDiscount)
                                <td class="gst-col">{{ number_format($productItems->sum('discount_amount'), 2) }}</td>
                                <td class="tax-col">
                                    {{ number_format($productItems->sum(function ($it) {return ($it->discount_amount ?? 0) > 0 ? $it->taxable_amount ?? $it->unit_price * $it->quantity : 0;}),2) }}
                                </td>
                            @endif
                            <td class="gst-col"></td>
                            <td class="tax-col">{{ number_format($productItems->sum('cgst'), 2) }}</td>
                            <td class="gst-col"></td>
                            <td class="tax-col">{{ number_format($productItems->sum('sgst'), 2) }}</td>
                            <td class="gst-col"></td>
                            <td class="tax-col">{{ number_format($productItems->sum('igst'), 2) }}</td>
                            <td class="amount-col">{{ number_format($productItems->sum('total'), 2) }}</td>
                        </tr>
                    </tbody>
                </table>
            @endif

            {{-- SERVICE TABLE --}}
            @if ($serviceItems->count())
                <div class="section-title">Service Items</div>
                <table class="items-table">
                    <thead>
                        <tr>
                            <th class="sno-col">S.NO</th>
                            <th class="desc-col">DESCRIPTION</th>
                            <th class="hsn-col">HSN/SAC</th>
                            <th class="qty-col">QTY</th>
                            <th class="rate-col">Taxable Value</th>
                            @if ($servHasDiscount)
                                <th class="gst-col">DISC</th>
                                <th class="tax-col">Tax Val after DISC</th>
                            @endif
                            <th class="gst-col">CGST%</th>
                            <th class="tax-col">CGST</th>
                            <th class="gst-col">SGST%</th>
                            <th class="tax-col">SGST</th>
                            <th class="amount-col">AMOUNT</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($serviceItems as $item)
                            <tr>
                                <td class="sno-col">{{ $loop->iteration }}</td>
                                <td class="desc-col" style="font-size:10px;">
                                    {{ $item->service->name }}@if ($item->description)
                                        - {{ $item->description }}
                                    @endif
                                </td>
                                <td class="hsn-col">{{ $item->service->hsn_code }}</td>
                                <td class="qty-col">{{ number_format($item->quantity) }}</td>
                                <td class="rate-col">{{ number_format($item->unit_price * $item->quantity, 2) }}</td>

                                @if ($servHasDiscount)
                                    <td class="gst-col">
                                        {{ ($item->discount_amount ?? 0) > 0 ? number_format($item->discount_amount, 2) : '' }}
                                    </td>
                                    <td class="tax-col">
                                        {{ ($item->discount_amount ?? 0) > 0 ? number_format($item->taxable_amount ?? $item->unit_price * $item->quantity, 2) : '-' }}
                                    </td>
                                @endif

                                @php
                                    $gstPerc = $item->service->gst_percentage ?? 18;
                                    $half = $gstPerc / 2;
                                @endphp
                                <td class="gst-col">{{ $half }}%</td>
                                <td class="tax-col">
                                    {{ number_format(($item->unit_price * $item->quantity * $half) / 100, 2) }}</td>
                                <td class="gst-col">{{ $half }}%</td>
                                <td class="tax-col">
                                    {{ number_format(($item->unit_price * $item->quantity * $half) / 100, 2) }}</td>
                                <td class="amount-col">{{ number_format($item->total, 2) }}</td>
                            </tr>
                        @endforeach

                        {{-- subtotal --}}
                        <tr style="font-weight:bold;">
                            <td colspan="{{ $servHasDiscount ? 3 : 5 }}" class="desc-col">SUB TOTAL</td>
                            <td class="qty-col">{{ number_format($serviceItems->sum('quantity')) }}</td>
                            <td class="rate-col">
                                {{ number_format($serviceItems->sum(fn($i) => $i->unit_price * $i->quantity), 2) }}
                            </td>
                            @if ($servHasDiscount)
                                <td class="gst-col">{{ number_format($serviceItems->sum('discount_amount'), 2) }}</td>
                                <td class="tax-col">
                                    {{ number_format($serviceItems->sum(function ($it) {return ($it->discount_amount ?? 0) > 0 ? $it->taxable_amount ?? $it->unit_price * $it->quantity : 0;}),2) }}
                                </td>
                            @endif
                            <td class="gst-col"></td>
                            <td class="tax-col">
                                {{ number_format($serviceItems->sum(function ($it) {$perc = $it->service->gst_percentage ?? 18;$half = $perc / 2;return ($it->unit_price * $it->quantity * $half) / 100;}),2) }}
                            </td>
                            <td class="gst-col"></td>
                            <td class="tax-col">
                                {{ number_format($serviceItems->sum(function ($it) {$perc = $it->service->gst_percentage ?? 18;$half = $perc / 2;return ($it->unit_price * $it->quantity * $half) / 100;}),2) }}
                            </td>
                            <td class="amount-col">{{ number_format($serviceItems->sum('total'), 2) }}</td>
                        </tr>
                    </tbody>
                </table>
            @endif

            {{-- BOTTOM SECTION --}}
            <div class="footer-section">
                <table class="footer-table">
                    <tr>
                        <td class="left-content">
                            {{-- ACCOUNT DETAILS --}}
                            <div class="account-box">
                                <strong style="font-size:14px;"><u>Account Details</u></strong><br>
                                <strong>Account Name:</strong> SKM AND COMPANY<br>
                                <strong>Account Number:</strong> 31167308287<br>
                                <strong>IFSC Code:</strong> SBIN0012772<br>
                                <strong>Bank Name:</strong> State Bank of India<br>
                                <strong>Branch:</strong> Ammapettai
                            </div>
                        </td>
                        <td class="right-content">
                            @php
                                $prodTotal = $productItems->sum('total');
                                $servTotal = $serviceItems->sum('total');
                                $courierCharges = $invoice->courier_charges ?? 0;
                                $grand = $prodTotal + $servTotal + $courierCharges;
                            @endphp

                            {{-- TOTALS SUMMARY --}}
                            <table class="totals-table">
                                @if ($productItems->count())
                                    <tr>
                                        <td class="total-label">Product Total</td>
                                        <td class="total-amount">{{ number_format($prodTotal, 2) }}</td>
                                    </tr>
                                @endif

                                @if ($serviceItems->count())
                                    <tr>
                                        <td class="total-label">Service Total</td>
                                        <td class="total-amount">{{ number_format($servTotal, 2) }}</td>
                                    </tr>
                                @endif

                                @if (isset($invoice->courier_charges) && $invoice->courier_charges > 0)
                                    <tr>
                                        <td class="total-label">Courier Charges</td>
                                        <td class="total-amount">₹ {{ number_format($courierCharges, 2) }}</td>
                                    </tr>
                                @endif

                                <tr class="total-final">
                                    <td class="total-label"><strong>Grand Total</strong></td>
                                    <td class="total-amount">
                                        <strong>{{ number_format($grand, 2) }}</strong>
                                    </td>
                                </tr>
                            </table>

                            <div class="total-words-box">
                                <strong>Amount Chargeable (in words):</strong><br>
                                {{ ucwords(\App\Helpers\NumberToWords::convert($grand)) }} Only
                            </div>
                        </td>
                    </tr>
                </table>
            </div>

            {{-- SIGNATURE --}}
            <div class="signature-area">
                <div class="signature-right">
                    <strong>For {{ config('company.name') }}</strong><br><br><br>
                    Authorised Signature
                </div>
            </div>
        </div>
    </div>
</body>

</html>
