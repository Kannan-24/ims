<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Quotation</title>
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
            left: 225px;
            top: 10px;
            width: 50px;
            height: 50px;
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

        .quotation-details {
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

        .qr-code {
            text-align: center;
            margin: 0 auto;
            padding-top: 15px;
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
            width: 22%;
            padding-left: 5px !important;
        }

        .amount-col {
            text-align: right !important;
            width: 8%;
            padding-right: 5px !important;
        }

        .sno-col {
            width: 4%;
        }

        .hsn-col {
            width: 8%;
        }

        .qty-col {
            width: 5%;
        }

        .rate-col {
            width: 8%;
            padding-right: 5px !important;
        }

        .gst-col {
            width: 6%;
        }

        .tax-col {
            width: 7%;
        }

        .summary-table {
            width: 100%;
            border-collapse: collapse;
            margin: 0px 0;
        }

        .summary-table td {
            border: 1px solid #000;
            padding: 8px 5px;
            font-size: 11px;
            text-align: center;
        }

        .summary-header {
            background-color: #f0f0f0;
            font-weight: bold;
        }

        .amount-words {
            font-size: 12px;
            margin: 15px 0;
        }

        .terms-section {
            margin: 15px 0;
            font-size: 11px;
        }

        .bank-details {
            margin: 15px 0;
            font-size: 11px;
        }

        .signature-section {
            margin-top: 30px;
            text-align: right;
            font-size: 11px;
        }

        .note {
            margin-top: 20px;
            text-align: center;
            font-size: 10px;
            font-weight: bold;
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
        <!-- Header Section -->
        <div class="header">
            @if (file_exists(public_path('assets/logo.png')))
                <img src="{{ public_path('assets/logo.png') }}" alt="Company Logo" class="logo">
            @endif
            <div class="company">SKM AND COMPANY</div>
            <div class="address">3/90, VOC Nagar Colony,<br> Salem, Tamil Nadu - 636 014, India.</div>
        </div>

        <!-- Info Row -->
        <div class="info-row clearfix">
            <div class="info-left">GSTIN No : 33ABSFS3535K1zq</div>
            <div class="info-middle">E-Mail : skmandcompany@yahoo.in</div>
            <div class="info-right">Ph : 8870820449, 9965066729.</div>
        </div>

        <!-- Details Section -->
        <div class="details-section">
            <div class="customer-details">
                <div class="detail-line"><span class="label">Customer Details:</span></div>
                <div class="detail-line"><span class="label">Name :</span> {{ $quotation->customer->company_name }}
                </div>
                <div class="detail-line"><span class="label">Address :</span> {{ $quotation->customer->address }},
                    {{ $quotation->customer->city }}, {{ $quotation->customer->state }} -
                    {{ $quotation->customer->zip_code }}, {{ $quotation->customer->country }}</div>
                <div class="detail-line"><span class="label">GSTIN No :</span>
                    {{ $quotation->customer->gst_number ?? 'N/A' }}</div>
                <div class="detail-line"><span class="label">Contact Person:</span>
                    @if ($quotation->customer->contactPersons->count() > 0)
                        {{ $quotation->customer->contactPersons->first()->name }}
                    @else
                        N/A
                    @endif
                </div>
                <div class="detail-line"><span class="label">Place of Supply :</span>
                    {{ $quotation->customer->city ?? 'N/A' }}</div>
            </div>
            <div class="quotation-details">
                <div class="detail-line"><span class="label">Quotation No :</span> {{ $quotation->quotation_code }}
                </div>
                <div class="detail-line"><span class="label">Quotation Date :</span>
                    {{ \Carbon\Carbon::parse($quotation->quotation_date)->format('d-m-Y') }}</div>
                <div class="detail-line"><span class="label">Customer ID :</span>
                    {{ $quotation->customer->cid ?? 'N/A' }}</div>
                <div class="detail-line"><span class="label">Valid Upto:</span>
                    {{ $quotation->valid_until ? \Carbon\Carbon::parse($quotation->valid_until)->format('d-m-Y') : \Carbon\Carbon::parse($quotation->quotation_date)->addDays(30)->format('d-m-Y') }}
                </div>
            </div>
            <div class="qr-section">
                <div class="qr-code">
                    <img src="{{ $qrCode }}" alt="QR Code" width="80" height="80">
                    <div class="qr-label">Scan to Download</div>
                </div>
            </div>

        </div>

        <!-- Main Content -->
        <div class="main-content">
            @php
                $productItems = $quotation->items->filter(fn($item) => $item->service_id === null);
                $serviceItems = $quotation->items->filter(fn($item) => $item->service_id !== null);
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
                            <th class="gst-col">DISC</th>
                            <th class="tax-col">Tax Val after DISC</th>
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
                        @foreach ($productItems as $index => $item)
                            <tr>
                                <td class="sno-col">{{ $loop->iteration }}</td>
                                <td class="desc-col">
                                    {{ $item->product->name }}<br><small>{{ $item->product->description }}</small>
                                </td>
                                <td class="hsn-col">{{ $item->product->hsn_code }}</td>
                                <td class="qty-col">{{ number_format($item->quantity, 2) }}</td>
                                <td class="rate-col">{{ number_format($item->unit_price * $item->quantity, 2) }}</td>
                                <td class="gst-col">{{ number_format($item->discount_amount ?? 0, 2) }}</td>
                                <td class="tax-col">
                                    {{ number_format($item->taxable_amount ?? $item->unit_price * $item->quantity, 2) }}
                                </td>
                                <td class="gst-col">
                                    {{ !$item->product->is_igst ? $item->product->gst_percentage / 2 . '%' : '-' }}
                                </td>
                                <td class="tax-col">
                                    {{ !$item->product->is_igst ? number_format($item->cgst, 2) : '0.00' }}
                                </td>

                                <td class="gst-col">
                                    {{ !$item->product->is_igst ? $item->product->gst_percentage / 2 . '%' : '-' }}
                                </td>
                                <td class="tax-col">
                                    {{ !$item->product->is_igst ? number_format($item->sgst, 2) : '0.00' }}
                                </td>

                                <td class="gst-col">
                                    {{ $item->product->is_igst ? $item->product->gst_percentage . '%' : '-' }}
                                </td>
                                <td class="tax-col">
                                    {{ $item->product->is_igst ? number_format($item->igst, 2) : '0.00' }}
                                </td>

                                <td class="amount-col">{{ number_format($item->total, 2) }}</td>
                            </tr>
                        @endforeach
                        <!-- Subtotal Row -->
                        <tr style="font-weight: bold;">
                            <td colspan="3" class="desc-col">SUB TOTAL</td>
                            <td class="qty-col">{{ number_format($productItems->sum('quantity'), 2) }}</td>
                            <td class="rate-col">
                                {{ number_format($productItems->sum(fn($item) => $item->unit_price * $item->quantity), 2) }}
                            </td>
                            <td class="gst-col">{{ number_format($productItems->sum('discount_amount'), 2) }}</td>
                            <td class="tax-col">{{ number_format($productItems->sum('taxable_amount'), 2) }}</td>
                            <td colspan="1"></td>
                            <td class="tax-col">{{ number_format($productItems->sum('cgst'), 2) }}</td>
                            <td></td>
                            <td class="tax-col">{{ number_format($productItems->sum('sgst'), 2) }}</td>
                            <td></td>
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
                            <th class="desc-col">PART NAME</th>
                            <th class="hsn-col">HSN CODE</th>
                            <th class="qty-col">QTY</th>
                            <th class="rate-col">Taxable Value</th>
                            <th class="gst-col">DISC</th>
                            <th class="tax-col">Tax Val after DISC</th>
                            <th class="gst-col">CGST %</th>
                            <th class="tax-col">CGST</th>
                            <th class="gst-col">SGST %</th>
                            <th class="tax-col">SGST</th>
                            <th class="amount-col">AMOUNT</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($serviceItems as $index => $item)
                            <tr>
                                <td class="sno-col">{{ $loop->iteration }}</td>
                                <td class="desc-col">
                                    {{ $item->service->name }}<br><small>{{ $item->service->description }}</small>
                                </td>
                                <td class="hsn-col">{{ $item->service->hsn_code }}</td>
                                <td class="qty-col">{{ number_format($item->quantity, 2) }}</td>
                                <td class="rate-col">{{ number_format($item->unit_price * $item->quantity, 2) }}</td>
                                <td class="gst-col">{{ number_format($item->discount_amount ?? 0, 2) }}</td>
                                <td class="tax-col">
                                    {{ number_format($item->taxable_amount ?? $item->unit_price * $item->quantity, 2) }}
                                </td>
                                <td class="gst-col">{{ ($item->service->gst_percentage ?? 18) / 2 }}%</td>
                                <td class="tax-col">
                                    {{ number_format(($item->unit_price * $item->quantity * (($item->service->gst_percentage ?? 18) / 2)) / 100, 2) }}
                                </td>
                                <td class="gst-col">{{ ($item->service->gst_percentage ?? 18) / 2 }}%</td>
                                <td class="tax-col">
                                    {{ number_format(($item->unit_price * $item->quantity * (($item->service->gst_percentage ?? 18) / 2)) / 100, 2) }}
                                </td>
                                <td class="amount-col">{{ number_format($item->total, 2) }}</td>
                            </tr>
                        @endforeach
                        <!-- Subtotal Row -->
                        <tr style="font-weight: bold;">
                            <td colspan="3" class="desc-col">SUB TOTAL</td>
                            <td class="qty-col">{{ number_format($serviceItems->sum('quantity'), 2) }}</td>
                            <td class="rate-col">
                                {{ number_format($serviceItems->sum(fn($item) => $item->unit_price * $item->quantity), 2) }}
                            </td>
                            <td class="gst-col">{{ number_format($serviceItems->sum('discount_amount'), 2) }}</td>
                            <td class="tax-col">{{ number_format($serviceItems->sum('taxable_amount'), 2) }}</td>
                            <td colspan="1"></td>
                            <td class="tax-col">
                                {{ number_format($serviceItems->sum(function ($item) {return ($item->unit_price * $item->quantity * (($item->service->gst_percentage ?? 18) / 2)) / 100;}),2) }}
                            </td>
                            <td></td>
                            <td class="tax-col">
                                {{ number_format($serviceItems->sum(function ($item) {return ($item->unit_price * $item->quantity * (($item->service->gst_percentage ?? 18) / 2)) / 100;}),2) }}
                            </td>
                            <td class="amount-col">{{ number_format($serviceItems->sum('total'), 2) }}</td>
                        </tr>
                    </tbody>
                </table>
            @endif

            {{-- TOTAL SUMMARY ROWS --}}
            @if ($productItems->count() || $serviceItems->count())
                <table class="items-table" style="margin-top: 0; border-top: none;">
                    <tbody>
                        <tr style="font-weight: bold; background-color: #f8f8f8;">
                            <td colspan="13" style="text-align: right; padding-right: 10px;">Product Total</td>
                            <td class="amount-col">{{ number_format($productItems->sum('total'), 2) }}</td>
                        </tr>
                        <tr style="font-weight: bold; background-color: #f8f8f8;">
                            <td colspan="13" style="text-align: right; padding-right: 10px;">Service Total</td>
                            <td class="amount-col">{{ number_format($serviceItems->sum('total'), 2) }}</td>
                        </tr>
                        <tr style="font-weight: bold; background-color: #e8e8e8; font-size: 11px;">
                            <td colspan="13" style="text-align: right; padding-right: 10px;">Grand Total</td>
                            <td class="amount-col">{{ number_format($quotation->total, 2) }}</td>
                        </tr>
                    </tbody>
                </table>
            @endif

            {{-- AMOUNT IN WORDS --}}
            <div class="amount-words">
                <strong>Amount Chargeable (in words):</strong>
                {{ ucwords(\App\Helpers\NumberToWords::convert($quotation->total)) }}.
            </div>

            {{-- TERMS --}}
            @if (!empty($quotation->terms_condition))
                <div class="terms-section">
                    <strong>Terms and Conditions:</strong>
                    <ol>
                        @foreach (explode('#', $quotation->terms_condition) as $term)
                            @if (trim($term))
                                <li>{{ $term }}</li>
                            @endif
                        @endforeach
                    </ol>
                </div>
            @endif

            {{-- BANK DETAILS --}}
            <div class="bank-details">
                <strong>Bank Account Details:</strong><br>
                Account Name: SKM AND COMPANY<br>
                Account Number: 31167308287<br>
                IFSC Code: SBIN0012772<br>
                Bank Name: State Bank of India<br>
                Branch: Ammapettai
            </div>

            {{-- FOOTER --}}
            <div class="signature-section">
                <strong>For SKM AND COMPANY</strong><br><br>
                _______________________<br>
                Authorised Signature
            </div>

            <div class="note">
                <strong>Note:</strong> Please make cheques in favour of "SKM AND COMPANY"
            </div>
        </div>
    </div>
</body>

</html>
