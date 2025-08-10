<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice QR View - {{ $invoice->invoice_no }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @media print {
            .no-print {
                display: none !important;
            }
        }
    </style>
</head>

<body class="bg-gray-50">
    <div class="min-h-screen py-8">
        <div class="max-w-4xl mx-auto bg-white rounded-lg shadow-lg overflow-hidden">
            <!-- Header -->
            <div class="bg-gradient-to-r from-blue-600 to-blue-800 text-white p-6">
                <div class="flex justify-between items-center">
                    <div>
                        <h1 class="text-3xl font-bold">{{ config('company.name') }}</h1>
                        <p class="text-blue-100 mt-1">Invoice Verification System</p>
                    </div>
                    <div class="text-right">
                        <div class="bg-white/10 p-3 rounded-lg">
                            <i class="fas fa-qrcode text-2xl mb-2"></i>
                            <p class="text-sm">Scan verified invoice</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- QR Code Section -->
            <div class="p-8 text-center border-b">
                <h2 class="text-2xl font-semibold text-gray-800 mb-4">Invoice QR Code</h2>
                <div class="inline-block p-4 bg-white border-2 border-gray-200 rounded-lg shadow-sm">
                    {!! $qrCode !!}
                </div>
                <p class="text-gray-600 mt-4 max-w-md mx-auto">
                    Scan this QR code to view and verify invoice details. This code contains secure access to invoice
                    information.
                </p>
            </div>

            <!-- Invoice Information -->
            <div class="p-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Invoice Details -->
                    <div class="bg-gray-50 rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                            <i class="fas fa-file-invoice text-blue-600 mr-2"></i>
                            Invoice Details
                        </h3>
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Invoice Number:</span>
                                <span class="font-medium text-gray-900">{{ $invoice->invoice_no }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Date:</span>
                                <span
                                    class="font-medium text-gray-900">{{ \Carbon\Carbon::parse($invoice->created_at)->format('d M Y') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Due Date:</span>
                                <span
                                    class="font-medium text-gray-900">{{ \Carbon\Carbon::parse($invoice->due_date ?? $invoice->created_at)->format('d M Y') }}</span>
                            </div>
                            <div class="flex justify-between border-t pt-3">
                                <span class="text-gray-600 font-medium">Total Amount:</span>
                                <span
                                    class="font-bold text-xl text-green-600">₹{{ number_format($invoice->total, 2) }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Customer Details -->
                    <div class="bg-gray-50 rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                            <i class="fas fa-user-tie text-blue-600 mr-2"></i>
                            Customer Details
                        </h3>
                        <div class="space-y-3">
                            <div>
                                <span class="text-gray-600 block">Company Name:</span>
                                <span
                                    class="font-medium text-gray-900">{{ $invoice->customer->company_name ?? $invoice->customer->name }}</span>
                            </div>
                            @if ($invoice->customer->gst)
                                <div>
                                    <span class="text-gray-600 block">GST Number:</span>
                                    <span class="font-medium text-gray-900">{{ $invoice->customer->gst }}</span>
                                </div>
                            @endif
                            <div>
                                <span class="text-gray-600 block">Address:</span>
                                <span class="font-medium text-gray-900">{{ $invoice->customer->address }}</span>
                                @if ($invoice->customer->city)
                                    <br><span class="font-medium text-gray-900">{{ $invoice->customer->city }}
                                        @if ($invoice->customer->postal_code)
                                            - {{ $invoice->customer->postal_code }}
                                        @endif
                                    </span>
                                @endif
                            </div>
                            @if ($invoice->customer->phone)
                                <div>
                                    <span class="text-gray-600 block">Phone:</span>
                                    <span class="font-medium text-gray-900">{{ $invoice->customer->phone }}</span>
                                </div>
                            @endif
                            @if ($invoice->customer->email)
                                <div>
                                    <span class="text-gray-600 block">Email:</span>
                                    <span class="font-medium text-gray-900">{{ $invoice->customer->email }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Items Summary -->
                <div class="mt-8 bg-gray-50 rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-list text-blue-600 mr-2"></i>
                        Items Summary
                    </h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full">
                            <thead>
                                <tr class="border-b">
                                    <th class="text-left py-2">S.No</th>
                                    <th class="text-left py-2">Item</th>
                                    <th class="text-center py-2">Qty</th>
                                    <th class="text-right py-2">Rate</th>
                                    <th class="text-right py-2">Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    // Handle both relationship and JSON data
                                    $items = [];
                                    if ($invoice->items instanceof \Illuminate\Database\Eloquent\Collection) {
                                        $items = $invoice->items;
                                    } elseif (is_string($invoice->items)) {
                                        $items = json_decode($invoice->items, true) ?? [];
                                    }
                                @endphp

                                @if (count($items) > 0)
                                    @foreach ($items as $item)
                                    <tr class="border-b">
                                            <td class="py-2">{{ $loop->iteration }}</td>
                                            @if (is_array($item))
                                                <td class="py-2">
                                                    {{ $item['name'] ?? ($item['product_name'] ?? 'N/A') }}</td>
                                                <td class="py-2 text-center">{{ $item['quantity'] ?? 0 }}</td>
                                                <td class="py-2 text-right">
                                                    ₹{{ number_format($item['price'] ?? ($item['unit_price'] ?? 0), 2) }}
                                                </td>
                                                <td class="py-2 text-right">
                                                    ₹{{ number_format(($item['quantity'] ?? 0) * ($item['price'] ?? ($item['unit_price'] ?? 0)), 2) }}
                                                </td>
                                            @else
                                                <td class="py-2">
                                                @if ($item->product)
                                                    {{ $item->product->name }}
                                                @elseif($item->service)
                                                    {{ $item->service->name }}
                                                @else
                                                    {{ $item->product_name ?? ($item->name ?? 'N/A') }}
                                                @endif
                                                </td>
                                                <td class="py-2 text-center">{{ $item->quantity ?? 0 }}</td>
                                                <td class="py-2 text-right">
                                                    ₹{{ number_format($item->unit_price ?? ($item->price ?? 0), 2) }}
                                                </td>
                                                <td class="py-2 text-right">
                                                    ₹{{ number_format($item->total ?? ($item->quantity * $item->unit_price ?? 0), 2) }}
                                                </td>
                                            @endif
                                        </tr>
                                    @endforeach
                                @else
                                    <tr class="border-b">
                                        <td colspan="4" class="py-4 text-center text-gray-500">No items found</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Company Information -->
                <div class="mt-8 bg-blue-50 rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-building text-blue-600 mr-2"></i>
                        Company Information
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                        <div>
                            <p><strong>{{ config('company.name') }}</strong></p>
                            <p>{{ config('company.address') }}</p>
                            <p>Phone: {{ config('company.phone') }}
                                @if (config('company.phone_2'))
                                    , {{ config('company.phone_2') }}
                                @endif
                            </p>
                            <p>Email: {{ config('company.email') }}</p>
                        </div>
                        <div>
                            @if (config('company.gst'))
                                <p><strong>GST:</strong> {{ config('company.gst') }}</p>
                            @endif
                            @if (config('company.udyam_number'))
                                <p><strong>UDYAM:</strong> {{ config('company.udyam_number') }}</p>
                            @endif
                            <p><strong>Website:</strong>
                                {{ config('company.website', request()->getSchemeAndHttpHost()) }}</p>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="mt-8 flex flex-wrap gap-4 justify-center no-print">
                    <a href="{{ route('invoices.pdf', $invoice->id) }}"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg transition duration-200 flex items-center">
                        <i class="fas fa-eye mr-2"></i>
                        View Full Invoice
                    </a>
                    <a href="{{ route('invoices.pdf', $invoice->id) }}" target="_blank"
                        class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg transition duration-200 flex items-center">
                        <i class="fas fa-download mr-2"></i>
                        Download PDF
                    </a>
                    <button onclick="shareInvoice()"
                        class="bg-purple-600 hover:bg-purple-700 text-white px-6 py-3 rounded-lg transition duration-200 flex items-center">
                        <i class="fas fa-share mr-2"></i>
                        Share
                    </button>
                </div>

                <!-- Verification Footer -->
                <div class="mt-8 text-center text-gray-500 text-sm border-t pt-6">
                    <p class="flex items-center justify-center mb-2">
                        <i class="fas fa-shield-alt text-green-500 mr-2"></i>
                        This is a digitally verified invoice from {{ config('company.name') }}
                    </p>
                    <p>Generated on {{ now()->format('d M Y, H:i A') }} | Verification ID:
                        INV-{{ $invoice->id }}-{{ substr(md5($invoice->invoice_no . $invoice->created_at), 0, 8) }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <script>
        function shareInvoice() {
            if (navigator.share) {
                navigator.share({
                    title: 'Invoice {{ $invoice->invoice_no }}',
                    text: 'View invoice from {{ config('company.name') }}',
                    url: window.location.href
                });
            } else {
                // Fallback to copy to clipboard
                navigator.clipboard.writeText(window.location.href).then(function() {
                    alert('Invoice link copied to clipboard!');
                });
            }
        }
    </script>
</body>

</html>
