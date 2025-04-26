<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Customer Report</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; margin: 0; padding: 0; }
        .header {
            text-align: center;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }
        .header h1 {
            margin: 0;
            font-size: 20px;
            text-transform: uppercase;
            font-weight: bold;
        }
        .header p {
            margin: 2px 0;
            font-size: 12px;
        }
        .report-title {
            text-align: center;
            margin: 10px 0 20px;
            font-size: 16px;
            font-weight: bold;
            text-decoration: underline;
        }
        table { width: 100%; border-collapse: collapse; }
        th, td {
            border: 1px solid #333;
            padding: 6px;
            vertical-align: top;
        }
        th {
            background-color: #f0f0f0;
            font-weight: bold;
        }
        .footer {
            position: fixed;
            bottom: 15px;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 11px;
            color: #555;
        }
    </style>
</head>
<body>

    <!-- Header -->
    <div class="header">
        <h1>SKM AND COMPANY</h1>
        <p>32/1, Adhi Selvan Street, Ammapet, Salem - 636 003</p>
        {{-- <p><strong>GSTIN No:</strong> 33ABSFS3535K1ZQ &nbsp; | &nbsp; <strong>State Code:</strong> 33</p>
        <p><strong>Mobile:</strong> 99650 66729, 88708 20449</p>
        <p><strong>Email:</strong> skmandcompany@yahoo.in</p> --}}
    </div>

    <!-- Report Title -->
    <div class="report-title">
        Customer Report
    </div>

    <!-- Report Table -->
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Company Name</th>
                <th>Address</th>
                <th>City</th>
                <th>State</th>
                <th>Zip</th>
                <th>Country</th>
                <th>GST No</th>
                <th>Contact Persons</th>
            </tr>
        </thead>
        <tbody>
            @foreach($customers as $index => $customer)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $customer->company_name }}</td>
                    <td>{{ $customer->address }}</td>
                    <td>{{ $customer->city }}</td>
                    <td>{{ $customer->state }}</td>
                    <td>{{ $customer->zip_code }}</td>
                    <td>{{ $customer->country }}</td>
                    <td>{{ $customer->gst_number }}</td>
                    <td>
                        @foreach($customer->contactPersons as $person)
                            <div>
                                <strong>{{ $person->name }}</strong><br>
                                {{ $person->email }}<br>
                                {{ $person->phone_no }}<br><br>
                            </div>
                        @endforeach
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Footer with generated timestamp -->
    <div style="text-align: right; position: fixed; bottom: -30px; right: 0px;">
        Report Generated On: {{ \Carbon\Carbon::now()->format('d-m-Y h:i A') }}
    </div>

</body>
</html>
