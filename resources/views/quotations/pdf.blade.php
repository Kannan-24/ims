<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quotation PDF</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 0;
            color: #333;
        }

        img#quotation-template {
            width: 100%;
            height: 100%;
            position: absolute;
            object-fit: contain;
            top: 0;
            left: 0;
            z-index: -1;
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
            background-color: #f2f2f2;
        }

        .header {
            text-align: center;
            font-size: 15px;
        }

        .logo {
            width: 120px;
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
