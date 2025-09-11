<x-app-layout>
    <x-slot name="title">
        {{ __('Reports Dashboard') }} - {{ config('app.name', 'IMS') }}
    </x-slot>

    <div class="bg-white min-h-screen" x-data="reportsManager()" x-init="init()">
        <!-- Breadcrumbs -->
        <div class="px-6 py-3 bg-gray-50 border-b border-gray-200">
            <nav class="flex" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="{{ route('dashboard') }}"
                            class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600">
                            <i class="fas fa-home mr-2"></i>
                            Dashboard
                        </a>
                    </li>
                    <li aria-current="page">
                        <div class="flex items-center">
                            <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                            <span class="text-sm font-medium text-gray-500">Reports</span>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>

        <!-- Header -->
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Reports Dashboard</h1>
                    <p class="text-sm text-gray-600 mt-1">Generate and export comprehensive business reports</p>
                </div>
                <div class="flex items-center space-x-3">
                    <!-- Help Button -->
                    <a href="{{ route('reports.help') }}"
                        class="inline-flex items-center px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition-colors">
                        <i class="fas fa-question-circle w-4 h-4 mr-2"></i>
                        Help
                    </a>
                    <!-- Refresh Button -->
                    <button @click="window.location.reload()"
                        class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                        <i class="fas fa-sync-alt w-4 h-4 mr-2"></i>
                        Refresh
                    </button>
                </div>
            </div>
        </div>

        <!-- Content -->
        <div class="p-6">

            <!-- Report Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Customer Reports -->
                <div class="bg-white border border-gray-200 rounded-lg p-6 shadow-sm hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <i class="fas fa-users text-blue-600 text-xl"></i>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900 ml-3">Customer Reports</h3>
                        </div>
                        <span class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800">
                            Active
                        </span>
                    </div>
                    <p class="text-gray-600 text-sm mb-4">Generate comprehensive customer reports with contact details, addresses, and business statistics.</p>
                    <div class="flex items-center justify-between">
                        <a href="{{ route('reports.customers') }}" 
                            class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                            <i class="fas fa-chart-bar w-4 h-4 mr-2"></i>
                            Generate Report
                        </a>
                        <span class="text-xs text-gray-500">{{ $totalCustomers ?? 0 }} records</span>
                    </div>
                </div>

                <!-- Supplier Reports -->
                <div class="bg-white border border-gray-200 rounded-lg p-6 shadow-sm hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <i class="fas fa-truck text-green-600 text-xl"></i>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900 ml-3">Supplier Reports</h3>
                        </div>
                        <span class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">
                            Active
                        </span>
                    </div>
                    <p class="text-gray-600 text-sm mb-4">View detailed supplier information including contact details, addresses, and business relationships.</p>
                    <div class="flex items-center justify-between">
                        <a href="{{ route('reports.suppliers') }}" 
                            class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-colors">
                            <i class="fas fa-chart-bar w-4 h-4 mr-2"></i>
                            Generate Report
                        </a>
                        <span class="text-xs text-gray-500">{{ $totalSuppliers ?? 0 }} records</span>
                    </div>
                </div>

                <!-- Invoice Reports -->
                <div class="bg-white border border-gray-200 rounded-lg p-6 shadow-sm hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <i class="fas fa-file-invoice text-purple-600 text-xl"></i>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900 ml-3">Invoice Reports</h3>
                        </div>
                        <span class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-full bg-purple-100 text-purple-800">
                            Active
                        </span>
                    </div>
                    <p class="text-gray-600 text-sm mb-4">Generate detailed invoice reports with sales data, customer information, and financial summaries.</p>
                    <div class="flex items-center justify-between">
                        <a href="{{ route('reports.invoices') }}" 
                            class="inline-flex items-center px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white text-sm font-medium rounded-lg transition-colors">
                            <i class="fas fa-chart-bar w-4 h-4 mr-2"></i>
                            Generate Report
                        </a>
                        <span class="text-xs text-gray-500">{{ $totalInvoices ?? 0 }} records</span>
                    </div>
                </div>

                <!-- Quotation Reports -->
                <div class="bg-white border border-gray-200 rounded-lg p-6 shadow-sm hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <i class="fas fa-quote-left text-orange-600 text-xl"></i>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900 ml-3">Quotation Reports</h3>
                        </div>
                        <span class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-full bg-orange-100 text-orange-800">
                            Active
                        </span>
                    </div>
                    <p class="text-gray-600 text-sm mb-4">Access comprehensive quotation reports with customer details and proposal statistics.</p>
                    <div class="flex items-center justify-between">
                        <a href="{{ route('reports.quotations') }}" 
                            class="inline-flex items-center px-4 py-2 bg-orange-600 hover:bg-orange-700 text-white text-sm font-medium rounded-lg transition-colors">
                            <i class="fas fa-chart-bar w-4 h-4 mr-2"></i>
                            Generate Report
                        </a>
                        <span class="text-xs text-gray-500">{{ $totalQuotations ?? 0 }} records</span>
                    </div>
                </div>

                <!-- Purchase Reports -->
                <div class="bg-white border border-gray-200 rounded-lg p-6 shadow-sm hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <i class="fas fa-shopping-cart text-indigo-600 text-xl"></i>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900 ml-3">Purchase Reports</h3>
                        </div>
                        <span class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-full bg-indigo-100 text-indigo-800">
                            Active
                        </span>
                    </div>
                    <p class="text-gray-600 text-sm mb-4">Generate detailed purchase reports including supplier transactions and expense analysis.</p>
                    <div class="flex items-center justify-between">
                        <a href="{{ route('reports.purchases') }}" 
                            class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition-colors">
                            <i class="fas fa-chart-bar w-4 h-4 mr-2"></i>
                            Generate Report
                        </a>
                        <span class="text-xs text-gray-500">{{ $totalPurchases ?? 0 }} records</span>
                    </div>
                </div>

                <!-- Stock Reports -->
                <div class="bg-white border border-gray-200 rounded-lg p-6 shadow-sm hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <i class="fas fa-boxes text-teal-600 text-xl"></i>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900 ml-3">Stock Reports</h3>
                        </div>
                        <span class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-full bg-teal-100 text-teal-800">
                            Active
                        </span>
                    </div>
                    <p class="text-gray-600 text-sm mb-4">Track inventory levels, stock movements, and generate comprehensive stock analysis reports.</p>
                    <div class="flex items-center justify-between">
                        <a href="{{ route('reports.stocks') }}" 
                            class="inline-flex items-center px-4 py-2 bg-teal-600 hover:bg-teal-700 text-white text-sm font-medium rounded-lg transition-colors">
                            <i class="fas fa-chart-bar w-4 h-4 mr-2"></i>
                            Generate Report
                        </a>
                        <span class="text-xs text-gray-500">{{ $totalStocks ?? 0 }} records</span>
                    </div>
                </div>

                <!-- Payment Reports -->
                <div class="bg-white border border-gray-200 rounded-lg p-6 shadow-sm hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <i class="fas fa-credit-card text-red-600 text-xl"></i>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900 ml-3">Payment Reports</h3>
                        </div>
                        <span class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-full bg-red-100 text-red-800">
                            Active
                        </span>
                    </div>
                    <p class="text-gray-600 text-sm mb-4">Analyze payment transactions, outstanding amounts, and financial performance metrics.</p>
                    <div class="flex items-center justify-between">
                        <a href="{{ route('reports.payments') }}" 
                            class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-colors">
                            <i class="fas fa-chart-bar w-4 h-4 mr-2"></i>
                            Generate Report
                        </a>
                        <span class="text-xs text-gray-500">{{ $totalPayments ?? 0 }} records</span>
                    </div>
                </div>

                <!-- Hotkeys Card -->
                <div class="bg-white border border-gray-200 rounded-lg p-6 shadow-sm hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <i class="fas fa-keyboard text-gray-600 text-xl"></i>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900 ml-3">Keyboard Shortcuts</h3>
                        </div>
                        <span class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-800">
                            Help
                        </span>
                    </div>
                    <p class="text-gray-600 text-sm mb-4">Access quick keyboard shortcuts and navigation help for faster report generation.</p>
                    <div class="flex items-center justify-between">
                        <button @click="showHotkeysModal = true" 
                            class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-lg transition-colors">
                            <i class="fas fa-keyboard w-4 h-4 mr-2"></i>
                            View Shortcuts
                        </button>
                        <span class="text-xs text-gray-500">Press H for help</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Hotkeys Modal -->
        <div x-show="showHotkeysModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50"
            x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
            <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                <div class="mt-3">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-medium text-gray-900">Keyboard Shortcuts</h3>
                        <button @click="showHotkeysModal = false" class="text-gray-400 hover:text-gray-600">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <div class="space-y-3">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-700">Show Help</span>
                            <kbd class="px-2 py-1 bg-gray-100 border border-gray-300 rounded text-sm">H</kbd>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-700">Refresh Page</span>
                            <kbd class="px-2 py-1 bg-gray-100 border border-gray-300 rounded text-sm">R</kbd>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-700">Back to Dashboard</span>
                            <kbd class="px-2 py-1 bg-gray-100 border border-gray-300 rounded text-sm">Esc</kbd>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-700">Customer Reports</span>
                            <kbd class="px-2 py-1 bg-gray-100 border border-gray-300 rounded text-sm">1</kbd>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-700">Supplier Reports</span>
                            <kbd class="px-2 py-1 bg-gray-100 border border-gray-300 rounded text-sm">2</kbd>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-700">Invoice Reports</span>
                            <kbd class="px-2 py-1 bg-gray-100 border border-gray-300 rounded text-sm">3</kbd>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function reportsManager() {
            return {
                showHotkeysModal: false,
                
                init() {
                    this.bindKeyboardEvents();
                },

                bindKeyboardEvents() {
                    document.addEventListener('keydown', (e) => {
                        // Don't trigger shortcuts when typing in inputs
                        if (e.target.tagName === 'INPUT' || e.target.tagName === 'TEXTAREA' || e.target.tagName === 'SELECT') {
                            return;
                        }

                        // Show help - H key
                        if (e.key.toLowerCase() === 'h' && !e.ctrlKey && !e.altKey) {
                            e.preventDefault();
                            this.showHotkeysModal = true;
                        }

                        // Refresh page - R key
                        if (e.key.toLowerCase() === 'r' && !e.ctrlKey && !e.altKey) {
                            e.preventDefault();
                            window.location.reload();
                        }

                        // Back to dashboard - Escape key
                        if (e.key === 'Escape') {
                            e.preventDefault();
                            window.location.href = '{{ route('dashboard') }}';
                        }

                        // Navigation shortcuts
                        if (!e.ctrlKey && !e.altKey && e.key >= '1' && e.key <= '7') {
                            e.preventDefault();
                            const routes = [
                                '{{ route('reports.customers') }}',
                                '{{ route('reports.suppliers') }}',
                                '{{ route('reports.invoices') }}',
                                '{{ route('reports.quotations') }}',
                                '{{ route('reports.purchases') }}',
                                '{{ route('reports.stocks') }}',
                                '{{ route('reports.payments') }}'
                            ];
                            const routeIndex = parseInt(e.key) - 1;
                            if (routes[routeIndex]) {
                                window.location.href = routes[routeIndex];
                            }
                        }
                    });
                }
            }
        }
    </script>
    @endpush
</x-app-layout>
