<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delivery Challan - {{ $deliveryChallan->dc_no }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            font-size: 14px;
            line-height: 1.4;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #000;
            padding-bottom: 20px;
            margin-bottom: 20px;
        }
        .company-name {
            font-size: 24px;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 5px;
        }
        .company-details {
            font-size: 12px;
            color: #666;
            line-height: 1.3;
        }
        .dc-title {
            font-size: 20px;
            font-weight: bold;
            margin: 20px 0;
            text-align: center;
            text-decoration: underline;
        }
        .dc-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .dc-info div {
            width: 48%;
        }
        .info-box {
            border: 1px solid #ddd;
            padding: 15px;
            margin-bottom: 20px;
        }
        .info-title {
            font-weight: bold;
            font-size: 16px;
            margin-bottom: 10px;
            color: #2c3e50;
        }
        .customer-details {
            margin-bottom: 15px;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        .items-table th,
        .items-table td {
            border: 1px solid #ddd;
            padding: 12px 8px;
            text-align: left;
            vertical-align: top;
        }
        .items-table th {
            background-color: #f8f9fa;
            font-weight: bold;
            font-size: 13px;
        }
        .items-table td {
            font-size: 12px;
            min-height: 20px;
        }
        .items-table .text-right {
            text-align: right;
        }
        .items-table .text-center {
            text-align: center;
        }
        .footer {
            margin-top: 40px;
            display: flex;
            justify-content: space-between;
        }
        .signature-box {
            width: 200px;
            text-align: center;
        }
        .signature-line {
            border-top: 1px solid #000;
            margin-top: 50px;
            padding-top: 5px;
        }
        .notes {
            margin-top: 20px;
            font-size: 12px;
        }
        .notes-title {
            font-weight: bold;
            margin-bottom: 10px;
        }
        .total-box {
            background-color: #f8f9fa;
            padding: 10px;
            border: 2px solid #dee2e6;
            margin-top: 10px;
        }
        .dc-details {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .dc-details div {
            flex: 1;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <div class="company-name">{{ config('company.name') }}</div>
        <div class="company-details">
            {{ config('company.address') }}<br>
            Phone: {{ config('company.phone') }}
            @if(config('company.phone_2'))
                , {{ config('company.phone_2') }}
            @endif
            <br>
            Email: {{ config('company.email') }}<br>
            @if(config('company.gst'))
                GST No: {{ config('company.gst') }}
            @endif
            @if(config('company.udyam_number'))
                | UDYAM: {{ config('company.udyam_number') }}
            @endif
        </div>
    </div>

    <!-- DC Title -->
    <div class="dc-title">
        DELIVERY CHALLAN
        <div style="float: right; margin-top: -10px;">
            @if(isset($qrCode))
                <div style="text-align: center;">
                    <img src="{{ $qrCode }}" alt="QR Code" style="width: 60px; height: 60px; border: 1px solid #ddd;" />
                    <div style="font-size: 8px; margin-top: 2px;">Scan for Invoice</div>
                </div>
            @endif
        </div>
        <div style="clear: both;"></div>
    </div>

    <!-- DC Details -->
    <div class="dc-details">
        <div>
            <strong>DC No:</strong> {{ $deliveryChallan->dc_no }}<br>
            <strong>DC Date:</strong> {{ \Carbon\Carbon::parse($deliveryChallan->delivery_date)->format('d-m-Y') }}<br>
            <strong>Status:</strong> {{ ucfirst($deliveryChallan->status) }}
        </div>
        <div style="text-align: right;">
            <strong>Invoice No:</strong> {{ $invoice->invoice_no }}<br>
            <strong>Invoice Date:</strong> {{ \Carbon\Carbon::parse($invoice->created_at)->format('d-m-Y') }}<br>
            <strong>Generated:</strong> {{ \Carbon\Carbon::parse($deliveryChallan->generated_at)->format('d-m-Y H:i') }}
        </div>
    </div>

    <!-- Customer Information -->
    <div class="info-box">
        <div class="info-title">DELIVERED TO:</div>
        <div class="customer-details">
            <strong>{{ $invoice->customer->company_name ?? $invoice->customer->name }}</strong><br>
            {{ $invoice->customer->address }}<br>
            @if($invoice->customer->city)
                {{ $invoice->customer->city }}
                @if($invoice->customer->postal_code)
                    - {{ $invoice->customer->postal_code }}
                @endif
                <br>
            @endif
            @if($invoice->customer->phone)
                Phone: {{ $invoice->customer->phone }}<br>
            @endif
            @if($invoice->customer->email)
                Email: {{ $invoice->customer->email }}<br>
            @endif
            @if($invoice->customer->gst)
                GST: {{ $invoice->customer->gst }}
            @endif
        </div>
    </div>

    <!-- Items Table -->
    <table class="items-table">
        <thead>
            <tr>
                <th class="text-center" style="width: 5%;">S.No</th>
                <th style="width: 35%;">Product</th>
                <th style="width: 20%;">Description</th>
                <th class="text-center" style="width: 10%;">HSN</th>
                <th class="text-center" style="width: 8%;">Qty</th>
                <th class="text-center" style="width: 8%;">Unit</th>
                <th class="text-right" style="width: 14%;">Rate (₹)</th>
            </tr>
        </thead>
        <tbody>
            @php
                // Only get items that are products (type = 'product' and have product_id and product relationship)
                $allItems = $invoice->items;
                $productItems = $allItems->filter(function($item) {
                    return $item->type === 'product' && 
                           !is_null($item->product_id) && 
                           !is_null($item->product);
                });
                $itemCount = $productItems->count();
            @endphp
            
            @if($itemCount > 0)
                @foreach($productItems as $item)
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td>{{ $item->product->name ?? 'N/A' }}</td>
                    <td>{{ $item->product->description ?? '-' }}</td>
                    <td class="text-center">{{ $item->product->hsn_code ?? '-' }}</td>
                    <td class="text-center">{{ $item->quantity ?? 0 }}</td>
                    <td class="text-center">{{ $item->unit_type ?? $item->product->unit ?? 'Nos' }}</td>
                    <td class="text-right">₹{{ number_format($item->unit_price ?? 0, 2) }}</td>
                </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="7" class="text-center" style="padding: 20px; color: #666;">
                        No products available for delivery
                    </td>
                </tr>
            @endif
        </tbody>
    </table>

    <!-- Total Box -->
    <div class="total-box">
        <div style="text-align: right;">
            @php
                $productTotal = $productItems->sum(function($item) {
                    return $item->quantity * $item->unit_price;
                });
            @endphp
            <strong>Total Product Amount: ₹{{ number_format($productTotal, 2) }}</strong><br>
            <small style="color: #666;">(Original Invoice Total: ₹{{ number_format($invoice->total_amount, 2) }})</small>
        </div>
    </div>

    <!-- Notes -->
    <div class="notes">
        <div class="notes-title">TERMS & CONDITIONS:</div>
        <ul style="margin: 5px 0; padding-left: 20px;">
            <li><strong>This delivery challan covers only physical products, not services</strong></li>
            <li>Goods once delivered will not be taken back</li>
            <li>This is a computer generated delivery challan</li>
            <li>All disputes are subject to {{ config('company.city', 'Salem') }} jurisdiction only</li>
            <li>Payment to be made within 30 days from the date of delivery</li>
        </ul>
    </div>

    <!-- Footer with Signatures -->
    <div class="footer">
        <div class="signature-box">
            <div class="signature-line">
                Prepared By
            </div>
        </div>
        
        <div class="signature-box">
            <div class="signature-line">
                Delivered By
            </div>
        </div>
        
        <div class="signature-box">
            <div class="signature-line">
                Received By
            </div>
        </div>
    </div>

    <!-- Company Signature -->
    <div style="text-align: right; margin-top: 60px;">
        <div style="width: 200px; margin-left: auto;">
            <div class="signature-line">
                For {{ config('company.name') }}
            </div>
        </div>
    </div>
</body>
</html>
