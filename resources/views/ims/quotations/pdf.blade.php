<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quotation PDF</title>
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
            border-bottom: 3px solid #e74c3c;
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
            color: #e74c3c;
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

        .quotation-title {
            text-align: right;
            flex: 0 0 200px;
        }

        .quotation-title h1 {
            font-size: 32px;
            color: #e74c3c;
            margin: 0;
            font-weight: bold;
        }

        .quotation-number {
            font-size: 14px;
            color: #c0392b;
            font-weight: bold;
            margin-top: 5px;
        }

        .quotation-meta {
            display: flex;
            justify-content: space-between;
            margin: 30px 0;
            background: linear-gradient(135deg, #fef9e7 0%, #fcf3cf 100%);
            padding: 20px;
            border-radius: 8px;
            border-left: 5px solid #f39c12;
        }

        .quote-to {
            flex: 1;
            margin-right: 30px;
        }

        .quotation-details {
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
            background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
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
            background-color: #fdf2f2;
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
            background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
            color: white;
            font-size: 16px;
            font-weight: bold;
        }

        .validity-section {
            margin-top: 30px;
            padding: 20px;
            background: linear-gradient(135deg, #e8f5e8 0%, #d5f4e6 100%);
            border-radius: 8px;
            border-left: 5px solid #27ae60;
        }

        .validity-title {
            font-weight: bold;
            color: #27ae60;
            margin-bottom: 10px;
            font-size: 14px;
        }

        .terms-section {
            margin-top: 30px;
            padding: 20px;
            background-color: #f8f9fa;
            border-radius: 8px;
            border-left: 5px solid #3498db;
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

        .contact-info {
            flex: 1;
            font-size: 11px;
            color: #555;
        }

        .signature {
            text-align: center;
            flex: 0 0 200px;
        }

        .signature-line {
            border-bottom: 2px solid #e74c3c;
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
            background: linear-gradient(135deg, #e8f6ff 0%, #d6eaff 100%);
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
            color: rgba(231, 76, 60, 0.05);
            font-weight: bold;
            z-index: -1;
            pointer-events: none;
        }

        .highlight-box {
            background: linear-gradient(135deg, #fff5f5 0%, #fed7d7 100%);
            border: 2px solid #e74c3c;
            border-radius: 8px;
            padding: 15px;
            margin: 20px 0;
            text-align: center;
        }

        .highlight-text {
            font-size: 14px;
            font-weight: bold;
            color: #c0392b;
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- <div class="watermark">QUOTATION</div> -->
        
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
                <div class="quotation-title">
                    <h1>QUOTATION</h1>
                    <div class="quotation-number">#{{ $quotation->quotation_no }}</div>
                </div>
            </div>
        </div>

        <div class="quotation-meta">
            <div class="quote-to">
                <div class="section-title">Quote To</div>
                <div class="customer-name">{{ $quotation->customer->company_name }}</div>
                <div>
                    {{ $quotation->customer->address }}<br>
                    {{ $quotation->customer->city }}, {{ $quotation->customer->state }} - {{ $quotation->customer->zip_code }}<br>
                    {{ $quotation->customer->country }}<br>
                    @if ($quotation->customer->gst_number)
                        <strong>GST No:</strong> {{ $quotation->customer->gst_number }}
                    @endif
                </div>
            </div>
            <div class="quotation-details">
                <div class="section-title">Quotation Details</div>
                <table style="width: 100%; border: none; margin: 0;">
                    <tr style="background: none;">
                        <td style="border: none; padding: 3px 0; font-weight: bold;">Quote Date:</td>
                        <td style="border: none; padding: 3px 0;">{{ \Carbon\Carbon::parse($quotation->quotation_date)->format('d M, Y') }}</td>
                    </tr>
                    <tr style="background: none;">
                        <td style="border: none; padding: 3px 0; font-weight: bold;">Valid Until:</td>
                        <td style="border: none; padding: 3px 0;">{{ $quotation->valid_until ? \Carbon\Carbon::parse($quotation->valid_until)->format('d M, Y') : \Carbon\Carbon::parse($quotation->quotation_date)->addDays(config('company.quotation.validity_days'))->format('d M, Y') }}</td>
                    </tr>
                    <tr style="background: none;">
                        <td style="border: none; padding: 3px 0; font-weight: bold;">Status:</td>
                        <td style="border: none; padding: 3px 0; color: #e74c3c; font-weight: bold;">{{ ucfirst($quotation->status ?? 'Pending') }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="highlight-box">
            <div class="highlight-text">This quotation is valid for {{ config('company.quotation.validity_days') }} days from the date of issue</div>
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
                    @foreach ($quotation->items as $item)
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
            $amountInWords = \App\Helpers\NumberToWords::convert($quotation->total);
        @endphp

        <div class="amount-words">
            <div class="amount-words-label">Amount in Words:</div>
            <div style="font-style: italic; font-weight: bold;">{{ $amountInWords }} Rupees Only</div>
        </div>

        <div class="totals-section">
            <table class="totals-table">
                <tr>
                    <td class="label">Subtotal:</td>
                    <td class="amount">₹{{ number_format($quotation->sub_total, 2) }}</td>
                </tr>
                <tr>
                    <td class="label">Tax Amount:</td>
                    <td class="amount">₹{{ number_format($quotation->tax_amount, 2) }}</td>
                </tr>
                @if ($quotation->discount > 0)
                    <tr>
                        <td class="label">Discount:</td>
                        <td class="amount">-₹{{ number_format($quotation->discount, 2) }}</td>
                    </tr>
                @endif
                <tr class="total-row">
                    <td class="label">TOTAL AMOUNT:</td>
                    <td class="amount">₹{{ number_format($quotation->total, 2) }}</td>
                </tr>
            </table>
        </div>

        <div class="validity-section">
            <div class="validity-title">✓ Quotation Validity & Acceptance</div>
            <div style="font-size: 12px; color: #27ae60;">
                <strong>This quotation is valid until {{ $quotation->valid_until ? \Carbon\Carbon::parse($quotation->valid_until)->format('d M, Y') : \Carbon\Carbon::parse($quotation->quotation_date)->addDays(config('company.quotation.validity_days'))->format('d M, Y') }}.</strong><br>
                To accept this quotation, please sign and return a copy or send us a written acceptance.
            </div>
        </div>

        <div class="terms-section">
            <div class="terms-title">Terms & Conditions</div>
            <ol class="terms-list">
                <li>This quotation is valid for {{ config('company.quotation.validity_days') }} days from the date of issue unless otherwise specified.</li>
                <li>Prices are subject to change without prior notice after the validity period.</li>
                <li>Payment terms: {{ config('company.quotation.payment_terms') }}.</li>
                <li>Delivery timeline will be confirmed upon order acceptance.</li>
                <li>All disputes are subject to {{ config('company.address') ? explode(',', config('company.address'))[0] : 'Local' }} jurisdiction only.</li>
                <li>This quotation becomes an order only after written acceptance from the client.</li>
            </ol>
        </div>

        <div class="signature-section">
            <div class="contact-info">
                <strong>For any queries or clarifications:</strong><br>
                Email: {{ env('COMPANY_EMAIL', 'email@company.com') }}<br>
                Phone: {{ env('COMPANY_PHONE', 'Phone Number') }}<br>
                <br>
                <strong>Prepared by:</strong><br>
                {{ env('COMPANY_NAME', 'Your Company') }}<br>
                Sales Team
            </div>
            <div class="signature">
                <div style="margin-bottom: 20px;">
                    <strong>Customer Acceptance:</strong><br>
                    <small style="color: #666;">Sign below to accept this quotation</small>
                </div>
                <div class="signature-line"></div>
                <div style="font-weight: bold; margin-top: 5px;">Customer Signature</div>
                <div style="font-size: 11px; color: #666; margin-top: 10px;">Date: ________________</div>
            </div>
        </div>

        <div class="footer">
            <div class="footer-text">
                <strong>Thank you for considering our services!</strong><br>
                We look forward to doing business with you.<br>
                {{ env('COMPANY_NAME', 'Your Company') }} - {{ env('COMPANY_TAGLINE', 'Professional Business Solutions') }}
            </div>
        </div>
    </div>
</body>

</html>
        }

        .bold {
            font-weight: bold;
        }

        .amount-box {
            border-top: 2px solid black;
            padding: 10px;
            margin-top: 20px;
        }

        .footer {
            text-align: right;
            font-size: 12px;
            margin-top: 30px;
        }

        .additional-info {
            text-align: center;
            font-size: 12px;
            margin-top: 10px;
        }
    </style>
</head>

<body>

    <img src="{{ public_path('assets/quotation.png') }}" alt="Quotation Template" id="quotation-template">

    {{-- <div class="header">
        <img src="{{ public_path('assets/logo.png') }}" alt="Company Logo" class="logo">
        <div class="header-text">
            <div>SKM AND COMPANY</div>
            <div>32/1, Adhi Selvan Street, Ammapet, <b>Salem - 636 003</b></div>
            <div>E-Mail: skmandcompany@yahoo.in | Mobile: 99650 66729, 88708 20449</div>
        </div>
    </div> --}}

    <div class="additional-info">
        <p><b>GSTIN No:</b> 33ABSFS3535K1ZQ</p>
        <p><b>E-Mail:</b> skmandcompany@yahoo.in | <b>Ph:</b> 8870820449, 9965066729</p>
    </div>

    <hr>

    <table>
        <tr>
            <td><b>Bill To:</b><br>{{ $quotation->customer->company_name }}</td>
            <td><b>Address:</b><br>{{ $quotation->customer->address }},<br>{{ $quotation->customer->city }},
                {{ $quotation->customer->state }},<br>{{ $quotation->customer->zip_code }},
                {{ $quotation->customer->country }}</td>
            <td><b>Place of Supply:</b><br>{{ $quotation->customer->city }}</td>
        </tr>
        <tr>
            <td><b>GSTIN No:</b><br>{{ $quotation->customer->gst_number }}</td>
            <td><b>Date:</b><br>{{ \Carbon\Carbon::parse($quotation->quotation_date)->format('d-m-Y') }}</td>
            <td><b>Invoice No:</b><br>{{ $quotation->quotation_code }}</td>
        </tr>
        <tr>
            <td><b>Customer Code:</b><br>{{ $quotation->customer->cid }}</td>
            <td><b>Valid
                    To:</b><br>{{ \Carbon\Carbon::parse($quotation->quotation_date)->addDays(15)->format('d-m-Y') }}
            </td>
        </tr>
    </table>

    @php
        $productItems = $quotation->items->filter(fn($item) => $item->service_id === null);
        $serviceItems = $quotation->items->filter(fn($item) => $item->service_id === 1);
    @endphp

    {{-- PRODUCT TABLE --}}
    @if ($productItems->count())
        <h3>Product Items</h3>
        <table>
            <thead>
                <tr class="bold">
                    <th>S.No</th>
                    <th>Description of Goods</th>
                    <th>HSN Code</th>
                    <th>QTY</th>
                    <th>Units</th>
                    <th>Rate</th>
                    <th>GST %</th>
                    <th>CGST</th>
                    <th>SGST</th>
                    <th>IGST</th>
                    <th>Amount</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($productItems as $index => $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $item->product->name }} - {{ $item->product->description }}</td>
                        <td>{{ $item->product->hsn_code }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>{{ $item->unit_type }}</td>
                        <td>{{ number_format($item->unit_price, 2) }}</td>
                        <td>{{ $item->product->gst_percentage }}%</td>
                        <td>{{ number_format($item->cgst, 2) }}</td>
                        <td>{{ number_format($item->sgst, 2) }}</td>
                        <td>{{ number_format($item->igst, 2) }}</td>
                        <td>{{ number_format($item->total, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    {{-- SERVICE TABLE --}}
    @if ($serviceItems->count())
        <h3>Service Items</h3>
        <table>
            <thead>
                <tr class="bold">
                    <th>S.No</th>
                    <th>Description of Services</th>
                    <th>HSN Code</th>
                    <th>QTY</th>
                    <th>Units</th>
                    <th>Rate</th>
                    <th>Amount</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($serviceItems as $index => $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $item->service->name }} - {{ $item->service->description }}</td>
                        <td>{{ $item->service->hsn_code }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>nos</td>
                        <td>{{ number_format($item->unit_price, 2) }}</td>
                        <td>{{ number_format($item->total, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    {{-- SUMMARY TABLE --}}
    <h3>Quotation Summary</h3>
    <table>
        <thead>
            <tr class="bold">
                <th>Total</th>
                <th>Less Discount</th>
                <th>Taxable Value</th>
                <th>ADD CGST</th>
                <th>ADD SGST</th>
                <th>ADD IGST</th>
                <th>Grand Total</th>
            </tr>
        </thead>
        <tr>
            <td>{{ number_format($quotation->sub_total, 2) }}</td>
            <td>-</td>
            <td>{{ number_format($quotation->sub_total, 2) }}</td>
            <td>{{ number_format($quotation->cgst, 2) }}</td>
            <td>{{ number_format($quotation->sgst, 2) }}</td>
            <td>{{ number_format($quotation->igst, 2) }}</td>
            <td class="bold">{{ number_format($quotation->total, 2) }}</td>
        </tr>
    </table>

    {{-- TERMS --}}
    @if (!empty($quotation->terms_condition))
        <div>
            <b>Terms and Conditions:</b>
            <ol>
                @foreach (explode('#', $quotation->terms_condition) as $term)
                    <li>{{ $term }}</li>
                @endforeach
            </ol>
        </div>
    @endif

    {{-- AMOUNT IN WORDS --}}
    <div class="amount-box">
        <b>Amount Chargeable (in words):</b> {{ ucwords(\App\Helpers\NumberToWords::convert($quotation->total)) }}
    </div>

    {{-- BANK DETAILS --}}
    <div>
        <b>Bank Account Details:</b><br>
        Account Name: SKM AND COMPANY<br>
        Account Number: 31167308287<br>
        IFSC Code: SBIN0012772<br>
        Bank Name: State Bank of India<br>
        Branch: Ammapettai
    </div>

    {{-- FOOTER --}}
    <div class="footer">
        <b>For SKM AND COMPANY</b><br><br>
        _______________________<br>
        Authorised Signature
    </div>

    <br>
    <div style="font-size: 10px; text-align: center;">
        <b>Note:</b> Please make cheques in favour of "SKM AND COMPANY"
    </div>

</body>

</html>
