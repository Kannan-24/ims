<x-app-layout>
    <x-slot name="title">
        {{ __('Payment Details') }} - {{ config('app.name', 'ATMS') }}
    </x-slot>

    <div class="py-6 mt-20 ml-4 sm:ml-64">
        <div class="w-full mx-auto max-w-7xl sm:px-6 lg:px-8">
            <x-bread-crumb-navigation />

            <div class="p-8 bg-gray-800 border border-gray-700 rounded-lg shadow-lg relative">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-3xl font-bold text-gray-200">Payment Details</h2>
                    <div class="flex gap-2">
                        <a href="{{ route('payments.edit', $payment->id) }}"
                            class="flex items-center px-4 py-2 text-white bg-green-500 rounded-lg hover:bg-green-600 transition">
                            Edit
                        </a>
                        <a href="{{ route('payments.index') }}"
                            class="flex items-center px-4 py-2 text-white bg-blue-600 hover:bg-blue-700 transition rounded-lg">
                            Back
                        </a>
                    </div>
                </div>

                <hr class="my-6 border-gray-600">

                <div class="space-y-4 text-gray-300">
                    <p><strong>Invoice Number:</strong>
                        <a href="{{ route('invoices.show', $payment->invoice->id) }}"
                            class="text-blue-400 hover:underline">
                            {{ $payment->invoice->invoice_no ?? 'N/A' }}
                        </a>
                    </p>
                    <p><strong>Invoice Date:</strong> {{ $payment->invoice->invoice_date ?? 'N/A' }}</p>
                    <p><strong>Customer Name:</strong> {{ $payment->invoice->customer->company_name ?? 'N/A' }}</p>
                    <p><strong>Customer GST Number:</strong> {{ $payment->invoice->customer->gst_number ?? 'N/A' }}</p>
                    <p><strong>Amount Paid:</strong> ₹{{ number_format($payment->amount, 2) }}</p>
                    <p><strong>Payment Method:</strong> {{ $payment->payment_method ?? '-' }}</p>
                    <p><strong>Payment Date:</strong> {{ $payment->payment_date ?? '-' }}</p>
                    <p><strong>Transaction Reference:</strong> {{ $payment->transaction_reference ?? '-' }}</p>
                    <p><strong>Note:</strong> {{ $payment->note ?? '-' }}</p>
                </div>


            </div>
        </div>
    </div>
</x-app-layout>
