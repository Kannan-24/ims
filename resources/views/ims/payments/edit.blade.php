<x-app-layout>
    <div class="bg-white min-h-screen" x-data="paymentEditManager()" x-init="init()">
        <!-- Breadcrumbs -->
        <div class="px-6 py-3 bg-gray-50 border-b border-gray-200">
            <nav class="flex" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="{{ route('dashboard') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600">
                            <i class="fas fa-home mr-2"></i>
                            Dashboard
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                            <a href="{{ route('payments.index') }}" class="text-sm font-medium text-gray-700 hover:text-blue-600">
                                Payments
                            </a>
                        </div>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                            <a href="{{ route('payments.show', $payment) }}" class="text-sm font-medium text-gray-700 hover:text-blue-600">
                                Payment #{{ $payment->id }}
                            </a>
                        </div>
                    </li>
                    <li aria-current="page">
                        <div class="flex items-center">
                            <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                            <span class="text-sm font-medium text-gray-500">Edit</span>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>

        <!-- Header -->
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Edit Payment Item</h1>
                    <p class="text-sm text-gray-600 mt-1">Update payment amount, date and reference</p>
                </div>
                <div class="flex items-center space-x-3">
                    <button @click="showHelpModal = true" type="button"
                        class="inline-flex items-center px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition-colors">
                        <i class="fas fa-question-circle w-4 h-4 mr-2"></i>
                        Help
                    </button>

                    <a href="{{ route('payments.show', $payment) }}"
                        class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition-colors">
                        <i class="fas fa-arrow-left w-4 h-4 mr-2"></i>
                        Back to Payment
                    </a>
                </div>
            </div>
        </div>

        <!-- Content -->
        <div class="p-6">
            <form id="paymentForm" action="{{ route('payments.update', $item->id) }}" method="POST" @submit.prevent="submitForm">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Left: summary -->
                    <div class="lg:col-span-1 bg-gray-800 text-gray-300 p-6 rounded-lg">
                        <h3 class="text-lg font-semibold text-gray-100 mb-4">Payment Summary</h3>
                        <table class="w-full text-gray-300 text-sm">
                            <tr>
                                <td class="py-2">Total</td>
                                <td class="py-2 text-right">{{ number_format($payment->total_amount, 2) }}</td>
                            </tr>
                            <tr>
                                <td class="py-2">Paid</td>
                                <td class="py-2 text-right">{{ number_format($payment->paid_amount, 2) }}</td>
                            </tr>
                            <tr>
                                <td class="py-2">Pending</td>
                                <td class="py-2 text-right">{{ number_format($payment->pending_amount, 2) }}</td>
                            </tr>
                        </table>

                        <hr class="my-4 border-gray-700">

                        <div class="text-sm text-gray-400">
                            @if($payment->invoice)
                                <p class="mb-2"><strong>Invoice:</strong>
                                    <a href="{{ route('invoices.show', $payment->invoice) }}" class="text-blue-400 hover:underline">
                                        {{ $payment->invoice->invoice_no }}
                                    </a>
                                </p>
                            @else
                                <p class="mb-2"><strong>Invoice:</strong> —</p>
                            @endif
                            <p class="mb-2"><strong>Customer:</strong> {{ optional(optional($payment->invoice)->customer)->company_name ?? '—' }}</p>
                            <p class="mb-2"><strong>Created:</strong> {{ $payment->created_at ? $payment->created_at->format('Y-m-d') : '—' }}</p>
                        </div>
                    </div>

                    <!-- Right: form -->
                    <div class="lg:col-span-2 bg-white border border-gray-200 rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Payment Details</h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="amount" class="block text-sm font-medium text-gray-700 mb-2">Amount <span class="text-red-500">*</span></label>
                                <input type="number" name="amount" id="amount" step="0.01"
                                    value="{{ old('amount', $item->amount) }}"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('amount') border-red-500 @enderror">
                                @error('amount') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label for="payment_date" class="block text-sm font-medium text-gray-700 mb-2">Payment Date <span class="text-red-500">*</span></label>
                                <input type="date" name="payment_date" id="payment_date"
                                    value="{{ old('payment_date', $item->payment_date) }}"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('payment_date') border-red-500 @enderror">
                                @error('payment_date') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label for="payment_method" class="block text-sm font-medium text-gray-700 mb-2">Payment Method <span class="text-red-500">*</span></label>
                                <select name="payment_method" id="payment_method"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('payment_method') border-red-500 @enderror">
                                    <option value="cash" {{ old('payment_method', $item->payment_method) == 'cash' ? 'selected' : '' }}>Cash</option>
                                    <option value="cheque" {{ old('payment_method', $item->payment_method) == 'cheque' ? 'selected' : '' }}>Cheque</option>
                                    <option value="upi" {{ old('payment_method', $item->payment_method) == 'upi' ? 'selected' : '' }}>UPI</option>
                                    <option value="bank_transfer" {{ old('payment_method', $item->payment_method) == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                                </select>
                                @error('payment_method') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label for="reference_number" class="block text-sm font-medium text-gray-700 mb-2">Reference Number</label>
                                <input type="text" name="reference_number" id="reference_number"
                                    value="{{ old('reference_number', $item->reference_number) }}"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('reference_number') border-red-500 @enderror">
                                @error('reference_number') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <div class="md:col-span-2">
                                <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
                                <textarea name="notes" id="notes" rows="3"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('notes') border-red-500 @enderror">{{ old('notes', $item->notes) }}</textarea>
                                @error('notes') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div class="mt-6 flex justify-end">
                            <a href="{{ route('payments.show', $payment->id) }}"
                                class="px-6 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-lg mr-4">
                                Cancel
                            </a>

                            <button type="submit"
                                class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg">
                                <i class="fas fa-save mr-2"></i>
                                Save Changes
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- Help Modal -->
        <div x-show="showHelpModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50"
            x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
            <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                <div class="mt-3">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-medium text-gray-900">Help & Instructions</h3>
                        <button @click="showHelpModal = false" class="text-gray-400 hover:text-gray-600">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <div class="mt-4 text-sm text-gray-600">
                        <ul class="space-y-2">
                            <li><strong>Amount:</strong> Enter the paid amount (required).</li>
                            <li><strong>Payment Date:</strong> Date when payment was made (required).</li>
                            <li><strong>Method & Reference:</strong> Provide method and any reference number.</li>
                            <li><strong>Shortcuts:</strong> Ctrl+S to save, Esc to cancel, Ctrl+B back to list.</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function paymentEditManager() {
            return {
                showHelpModal: false,

                init() {
                    this.bindKeyboardEvents();
                },

                bindKeyboardEvents() {
                    document.addEventListener('keydown', (e) => {
                        // allow Ctrl+S in inputs
                        if (e.target.tagName === 'INPUT' || e.target.tagName === 'TEXTAREA' || e.target.tagName === 'SELECT') {
                            if (e.ctrlKey && (e.key === 's' || e.key === 'S')) {
                                e.preventDefault();
                                this.submitForm();
                            }
                            return;
                        }

                        // Save - Ctrl+S
                        if (e.ctrlKey && (e.key === 's' || e.key === 'S')) {
                            e.preventDefault();
                            this.submitForm();
                        }

                        // Help - H
                        if (e.key.toLowerCase() === 'h' && !e.ctrlKey && !e.altKey) {
                            e.preventDefault();
                            this.showHelpModal = true;
                        }

                        // Cancel - Escape
                        if (e.key === 'Escape') {
                            e.preventDefault();
                            if (confirm('Are you sure you want to cancel? All changes will be lost.')) {
                                window.location.href = '{{ route('payments.show', $payment) }}';
                            }
                        }

                        // Back to list - Ctrl+B
                        if (e.ctrlKey && (e.key === 'b' || e.key === 'B')) {
                            e.preventDefault();
                            window.location.href = '{{ route('payments.index') }}';
                        }

                        // View payment - Ctrl+V
                        if (e.ctrlKey && (e.key === 'v' || e.key === 'V')) {
                            e.preventDefault();
                            window.location.href = '{{ route('payments.show', $payment) }}';
                        }
                    });
                },

                submitForm() {
                    const form = document.getElementById('paymentForm');

                    const required = ['amount', 'payment_date', 'payment_method'];
                    let isValid = true;

                    required.forEach(id => {
                        const el = document.getElementById(id);
                        if (!el || !el.value || !String(el.value).trim()) {
                            el && el.classList.add('border-red-500');
                            isValid = false;
                        } else {
                            el && el.classList.remove('border-red-500');
                        }
                    });

                    if (!isValid) {
                        alert('Please fill in all required fields.');
                        return;
                    }

                    form.submit();
                }
            }
        }
    </script>
    @endpush
</x-app-layout>
