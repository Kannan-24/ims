<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Quotation</title>
    <style>
        @page {
            size: A4;
            margin: 10mm;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            margin: 0;
            padding: 0;
            font-size: 12px;
            line-height: 1.3;
        }

        .container {
            border: 2px solid #000;
            width: 100%;
            min-height: 270mm;
        }

        .header {
            border-bottom: 2px solid #000;
            text-align: center;
            padding: 10px 0;
            position: relative;
        }

        .logo {
            position: absolute;
            left: 20px;
            top: 10px;
            width: 50px;
            height: 50px;
        }

        .company {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .address {
            font-size: 11px;
            margin-bottom: 5px;
        }

        .info-row {
            border-bottom: 2px solid #000;
            font-size: 11px;
            padding: 8px 10px;
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
            padding: 15px 10px;
        }

        .section-title {
            font-size: 14px;
            font-weight: bold;
            margin: 15px 0 10px 0;
            text-align: center;
        }

        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .items-table th,
        .items-table td {
            border: 1px solid #000;
            padding: 5px 3px;
            font-size: 10px;
            text-align: center;
        }

        .items-table th {
            background-color: #f0f0f0;
            font-weight: bold;
        }

        .desc-col {
            text-align: left !important;
            width: 25%;
        }

        .amount-col {
            text-align: right !important;
            width: 10%;
        }

        .summary-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
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
            border: 2px solid #000;
            padding: 10px;
            background-color: #f9f9f9;
            font-size: 12px;
            font-weight: bold;
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
            <div class="address">3/90, VOC Nagar Colony, Salem, Tamil Nadu - 636 014, India.</div>
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
                $serviceItems = $quotation->items->filter(fn($item) => $item->service_id === 1);
            @endphp

            {{-- PRODUCT TABLE --}}
            @if ($productItems->count())
                <div class="section-title">Product Items</div>
                <table class="items-table">
                    <thead>
                        <tr>
                            <th style="width: 5%;">S.No</th>
                            <th class="desc-col">Description of Goods</th>
                            <th style="width: 8%;">HSN Code</th>
                            <th style="width: 6%;">QTY</th>
                            <th style="width: 6%;">Units</th>
                            <th class="amount-col">Rate</th>
                            <th style="width: 6%;">GST %</th>
                            <th style="width: 8%;">CGST</th>
                            <th style="width: 8%;">SGST</th>
                            <th style="width: 8%;">IGST</th>
                            <th class="amount-col">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($productItems as $index => $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td class="desc-col">{{ $item->product->name }} - {{ $item->product->description }}
                                </td>
                                <td>{{ $item->product->hsn_code }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>{{ $item->unit_type }}</td>
                                <td class="amount-col">{{ number_format($item->unit_price, 2) }}</td>
                                <td>{{ $item->product->gst_percentage }}%</td>
                                <td>{{ number_format($item->cgst, 2) }}</td>
                                <td>{{ number_format($item->sgst, 2) }}</td>
                                <td>{{ number_format($item->igst, 2) }}</td>
                                <td class="amount-col">{{ number_format($item->total, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif

            {{-- SERVICE TABLE --}}
            @if ($serviceItems->count())
                <div class="section-title">Service Items</div>
                <table class="items-table">
                    <thead>
                        <tr>
                            <th style="width: 5%;">S.No</th>
                            <th class="desc-col">Description of Services</th>
                            <th style="width: 10%;">HSN Code</th>
                            <th style="width: 8%;">QTY</th>
                            <th style="width: 8%;">Units</th>
                            <th class="amount-col">Rate</th>
                            <th class="amount-col">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($serviceItems as $index => $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td class="desc-col">{{ $item->service->name }} - {{ $item->service->description }}
                                </td>
                                <td>{{ $item->service->hsn_code }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>nos</td>
                                <td class="amount-col">{{ number_format($item->unit_price, 2) }}</td>
                                <td class="amount-col">{{ number_format($item->total, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif

            {{-- SUMMARY TABLE --}}
            <div class="section-title">Quotation Summary</div>
            <table class="summary-table">
                <thead>
                    <tr>
                        <td class="summary-header">Total</td>
                        <td class="summary-header">Less Discount</td>
                        <td class="summary-header">Taxable Value</td>
                        <td class="summary-header">ADD CGST</td>
                        <td class="summary-header">ADD SGST</td>
                        <td class="summary-header">ADD IGST</td>
                        <td class="summary-header">Grand Total</td>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ number_format($quotation->sub_total, 2) }}</td>
                        <td>-</td>
                        <td>{{ number_format($quotation->sub_total, 2) }}</td>
                        <td>{{ number_format($quotation->cgst ?? 0, 2) }}</td>
                        <td>{{ number_format($quotation->sgst ?? 0, 2) }}</td>
                        <td>{{ number_format($quotation->igst ?? 0, 2) }}</td>
                        <td style="font-weight: bold;">{{ number_format($quotation->total, 2) }}</td>
                    </tr>
                </tbody>
            </table>

            {{-- AMOUNT IN WORDS --}}
            <div class="amount-words">
                <strong>Amount Chargeable (in words):</strong>
                {{ ucwords(\App\Helpers\NumberToWords::convert($quotation->total)) }} Rupees Only
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
