<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice PDF</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 20px;
            color: #333;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        .logo {
            width: 120px;
            height: auto;
        }

        .company-info {
            text-align: center;
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
            color: #7f8c8d;
        }

        .invoice-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
        }

        .invoice-info {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .customer-info {
            margin-bottom: 20px;
        }

        .info-title {
            font-weight: bold;
            font-size: 14px;
            color: #2c3e50;
            margin-bottom: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #3498db;
            color: white;
            font-weight: bold;
        }

        .text-right {
            text-align: right;
        }

        .totals {
            margin-top: 20px;
            width: 100%;
        }

        .totals table {
            width: 50%;
            margin-left: auto;
        }

        .total-row {
            background-color: #f8f9fa;
            font-weight: bold;
        }

        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #7f8c8d;
        }

        .terms {
            margin-top: 20px;
            font-size: 10px;
        }

        .signature {
            margin-top: 40px;
            text-align: right;
        }
    </style>
</head>

<body>
    <div class="header">
        <div class="company-info">
            <div class="company-name">SKM & COMPANY</div>
            <div class="company-details">
                123 Business Street, City, State - 123456<br>
                Phone: +91 9876543210 | Email: info@skmcompany.com<br>
                GST No: XXXXXXXXXXXXXXX
            </div>
        </div>
    </div>

    <div class="invoice-info">
        <div style="display: flex; justify-content: space-between;">
            <div>
                <div class="info-title">Invoice Details</div>
                <strong>Invoice No:</strong> {{ $invoice->invoice_no }}<br>
                <strong>Date:</strong> {{ $invoice->invoice_date }}<br>
                <strong>Due Date:</strong> {{ $invoice->due_date ?? 'N/A' }}
            </div>
            <div>
                <div class="info-title">Bill To</div>
                <strong>{{ $invoice->customer->company_name }}</strong><br>
                {{ $invoice->customer->address }}<br>
                {{ $invoice->customer->city }}, {{ $invoice->customer->state }} - {{ $invoice->customer->zip_code }}<br>
                {{ $invoice->customer->country }}<br>
                @if ($invoice->customer->gst_number)
                    <strong>GST No:</strong> {{ $invoice->customer->gst_number }}
                @endif
            </div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 5%;">#</th>
                <th style="width: 40%;">Description</th>
                <th style="width: 10%;">Qty</th>
                <th style="width: 15%;">Unit Price</th>
                <th style="width: 15%;">Tax Amount</th>
                <th style="width: 15%;">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($invoice->items as $item)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>
                        @if ($item->product_id)
                            {{ $item->product->name }}
                        @else
                            {{ $item->service->name }}
                        @endif
                        @if ($item->description)
                            <br><small style="color: #666;">{{ $item->description }}</small>
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

    <div class="totals">
        <table>
            <tr>
                <td><strong>Sub Total:</strong></td>
                <td class="text-right">₹{{ number_format($invoice->sub_total, 2) }}</td>
            </tr>
            <tr>
                <td><strong>Tax Amount:</strong></td>
                <td class="text-right">₹{{ number_format($invoice->tax_amount, 2) }}</td>
            </tr>
            @if ($invoice->discount > 0)
                <tr>
                    <td><strong>Discount:</strong></td>
                    <td class="text-right">₹{{ number_format($invoice->discount, 2) }}</td>
                </tr>
            @endif
            <tr class="total-row">
                <td><strong>Total Amount:</strong></td>
                <td class="text-right"><strong>₹{{ number_format($invoice->total, 2) }}</strong></td>
            </tr>
        </table>
    </div>

    <div class="terms">
        <div class="info-title">Terms & Conditions:</div>
        <ol>
            <li>Payment is due within 30 days from the invoice date.</li>
            <li>Late payments may incur additional charges.</li>
            <li>All prices are inclusive of applicable taxes unless stated otherwise.</li>
            <li>Please quote the invoice number when making payment.</li>
        </ol>
    </div>

    <div class="signature">
        <div style="margin-top: 50px;">
            <div>_________________________</div>
            <div><strong>Authorized Signature</strong></div>
            <div>SKM & COMPANY</div>
        </div>
    </div>

    <div class="footer">
        <p>Thank you for your business!</p>
        <p>This is a computer-generated invoice and does not require a signature.</p>
    </div>
</body>

</html>
