<x-app-layout>
    <x-slot name="title">
        Add Payment Item - {{ config('app.name', 'ATMS') }}
    </x-slot>

    <div class="py-6 mt-20 ml-4 sm:ml-64">
        <div class="w-full mx-auto max-w-7xl sm:px-6 lg:px-8">
            <x-bread-crumb-navigation />

            
            <div class="bg-gray-800 text-gray-300 p-6 rounded-lg shadow-xl">
                
                <h2 class="text-3xl font-bold text-gray-200 mb-6">Add Payment Item</h2>
                <table class="w-56 text-gray-300">
                    <tr>
                        <td class="py-2">Total</td>
                        <td class="py-2">{{ number_format($payment->total_amount, 2) }}</td>
                    </tr>
                    <tr>
                        <td class="py-2">Paid</td>
                        <td class="py-2">{{ number_format($payment->paid_amount, 2) }}</td>
                    </tr>
                    <tr>
                        <td class="py-2">Pending</td>
                        <td class="py-2">{{ number_format($payment->pending_amount, 2) }}</td>
                    </tr>
                </table>

                <hr class="my-6 border-gray-600">

                <form action="{{ route('payments.store', $payment->id) }}" method="POST">
                    @csrf

                    <div class="mb-4">
                        <label for="amount" class="block text-sm font-semibold text-gray-100">Amount</label>
                        <input type="number" name="amount" id="amount"
                            class="mt-1 w-full p-2 bg-gray-700 border-gray-600 text-gray-300 rounded-md" required>
                    </div>

                    <div class="mb-4">
                        <label for="payment_date" class="block text-sm font-semibold text-gray-100">Payment Date</label>
                        <input type="date" name="payment_date" id="payment_date"
                            class="mt-1 w-full p-2 bg-gray-700 border-gray-600 text-gray-300 rounded-md" required>
                    </div>

                    <div class="mb-4">
                        <label for="payment_method" class="block text-sm font-semibold text-gray-100">Payment Method</label>
                        <select name="payment_method" id="payment_method"
                            class="mt-1 w-full p-2 bg-gray-700 border-gray-600 text-gray-300 rounded-md" required>
                            <option value="cash">Cash</option>
                            <option value="cheque">Cheque</option>
                            <option value="upi">UPI</option>
                            <option value="bank_transfer">Bank Transfer</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label for="reference_number" class="block text-sm font-semibold text-gray-100">Reference Number</label>
                        <input type="text" name="reference_number" id="reference_number" value="Cash"
                            class="mt-1 w-full p-2 bg-gray-700 border-gray-600 text-gray-300 rounded-md">
                    </div>

                    <div class="mt-6 flex justify-end">
                        <a href="{{ route('payments.index') }}"
                            class="px-6 py-2 bg-gray-500 hover:bg-gray-600 text-white font-semibold rounded-md mr-4">
                            Back
                        </a>

                        <button type="submit"
                            class="px-6 py-2 bg-blue-500 hover:bg-blue-600 text-white font-semibold rounded-md">
                            Save Payment
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
