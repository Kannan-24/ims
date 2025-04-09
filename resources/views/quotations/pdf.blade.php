<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quotation PDF</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid black; padding: 8px; text-align: left; }
        .header { text-align: center; font-size: 18px; font-weight: bold; }
        .company-info { text-align: center; font-size: 14px; }
        .bold { font-weight: bold; }
        .amount-box { border-top: 2px solid black; padding: 10px; }
    </style>
</head>
<body>

    <div class="header">SKM AND COMPANY</div>
    <div class="company-info">
        32/1, Adhi Selvan Street, Ammapet, <b>Salem - 636 003</b><br>
        E-Mail: skmandcompany@yahoo.in | Mobile: 99650 66729, 88708 20449
    </div>
    <hr>

    <table>
        <tr>
            <td><b>Bill To:</b> {{ $quotation->customer->name }}</td>
            <td><b>Place of Supply:</b> {{ $quotation->customer->location }}</td>
        </tr>
        <tr>
            <td><b>GSTIN No:</b> {{ $quotation->customer->gstin }}</td>
            <td><b>Date:</b> {{ $quotation->quotation_date }}</td>
        </tr>
        <tr>
            <td><b>Invoice No:</b> {{ $quotation->quotation_code }}</td>
            <td><b>Order Date:</b> {{ $quotation->created_at->format('d-m-Y') }}</td>
        </tr>
        <tr>
            <td colspan="2"><b>Order No:</b> {{ $quotation->id }}</td>
        </tr>
    </table>

    <br>

    <table>
        <tr class="bold">
            <th>S.No</th>
            <th>Description of Goods</th>
            <th>HSN Code</th>
            <th>QTY</th>
            <th>Units</th>
            <th>Rate</th>
            <th>Amount</th>
        </tr>
        @foreach($quotation->items as $index => $item)
        <tr>
            <td>{{ $index + 1 }}</td>
            <td>{{ $item->product->name }}</td>
            <td>{{ $item->product->hsn_code }}</td>
            <td>{{ $item->quantity }}</td>
            <td>{{ $quotation->unit_type }}</td>
            <td>{{ number_format($item->unit_price, 2) }}</td>
            <td>{{ number_format($item->total, 2) }}</td>
        </tr>
        @endforeach
    </table>

    <br>

    <table>
        <tr>
            <td class="bold">Total</td>
            <td>{{ number_format($quotation->sub_total, 2) }}</td>
        </tr>
        <tr>
            <td>Less Discount</td>
            <td>-</td>
        </tr>
        <tr>
            <td class="bold">Taxable Value</td>
            <td>{{ number_format($quotation->sub_total, 2) }}</td>
        </tr>
        <tr>
            <td>ADD CGST</td>
            <td>{{ number_format($quotation->cgst, 2) }}</td>
        </tr>
        <tr>
            <td>ADD SGST</td>
            <td>{{ number_format($quotation->sgst, 2) }}</td>
        </tr>
        <tr>
            <td>ADD IGST</td>
            <td>{{ number_format($quotation->igst, 2) }}</td>
        </tr>
        <tr>
            <td>Freight</td>
            <td>-</td>
        </tr>
        <tr>
            <td class="bold">Total</td>
            <td class="bold">{{ number_format($quotation->total, 2) }}</td>
        </tr>
    </table>

    <br>

    <div class="amount-box">
        {{-- <b>Amount Chargeable (in words):</b> {{ ucwords(\App\Helpers\NumberToWords::convert($quotation->total)) }} Only --}}
    </div>

    <br>

    <div style="text-align: right;">
        <b>For SKM AND COMPANY</b><br><br>
        _______________________<br>
        Authorised Signature
    </div>

    <br>
    <div style="font-size: 10px;">
        <b>Note:</b> Please make cheques in favour of "SKM AND COMPANY"
    </div>

</body>
</html>
