<x-app-layout>
    <x-slot name="title">
        {{ __('Reports Help') }} - {{ config('app.name', 'IMS') }}
    </x-slot>

    <div class="bg-white min-h-screen">
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
                    <li>
                        <div class="flex items-center">
                            <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                            <a href="{{ route('reports.index') }}"
                                class="text-sm font-medium text-gray-700 hover:text-blue-600">
                                Reports
                            </a>
                        </div>
                    </li>
                    <li aria-current="page">
                        <div class="flex items-center">
                            <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                            <span class="text-sm font-medium text-gray-500">Help</span>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>

        <!-- Header -->
        <div class="px-6 py-6 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Reports Help Center</h1>
                    <p class="text-lg text-gray-600 mt-2">Complete guide to using the reports system effectively</p>
                </div>
                <div class="flex items-center space-x-3">
                    <!-- Back Button -->
                    <a href="{{ route('reports.index') }}"
                        class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                        <i class="fas fa-arrow-left w-4 h-4 mr-2"></i>
                        Back to Reports
                    </a>
                </div>
            </div>
        </div>

        <!-- Content -->
        <div class="p-6">
            <!-- Quick Navigation -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-8">
                <h2 class="text-xl font-semibold text-blue-900 mb-4">
                    <i class="fas fa-compass text-blue-600 mr-2"></i>
                    Quick Navigation
                </h2>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <a href="#overview"
                        class="flex items-center p-3 bg-white rounded-lg border border-blue-200 hover:bg-blue-100 transition-colors">
                        <i class="fas fa-chart-bar text-blue-600 mr-3"></i>
                        <span class="text-sm font-medium text-blue-900">Overview</span>
                    </a>
                    <a href="#reports"
                        class="flex items-center p-3 bg-white rounded-lg border border-blue-200 hover:bg-blue-100 transition-colors">
                        <i class="fas fa-file-alt text-blue-600 mr-3"></i>
                        <span class="text-sm font-medium text-blue-900">Report Types</span>
                    </a>
                    <a href="#shortcuts"
                        class="flex items-center p-3 bg-white rounded-lg border border-blue-200 hover:bg-blue-100 transition-colors">
                        <i class="fas fa-keyboard text-blue-600 mr-3"></i>
                        <span class="text-sm font-medium text-blue-900">Shortcuts</span>
                    </a>
                    <a href="#tips"
                        class="flex items-center p-3 bg-white rounded-lg border border-blue-200 hover:bg-blue-100 transition-colors">
                        <i class="fas fa-lightbulb text-blue-600 mr-3"></i>
                        <span class="text-sm font-medium text-blue-900">Tips & Tricks</span>
                    </a>
                </div>
            </div>

            <!-- Overview Section -->
            <section id="overview" class="mb-12">
                <div class="bg-white border border-gray-200 rounded-lg p-6 shadow-sm">
                    <h2 class="text-2xl font-semibold text-gray-900 mb-6">
                        <i class="fas fa-chart-bar text-blue-600 mr-3"></i>
                        Reports Overview
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-3">What are Reports?</h3>
                            <p class="text-gray-600 mb-4">
                                Reports provide comprehensive insights into your business data. They help you track
                                performance,
                                analyze trends, and make informed decisions based on real-time information.
                            </p>
                            <ul class="space-y-2 text-gray-600">
                                <li class="flex items-start">
                                    <i class="fas fa-check text-green-600 mr-2 mt-1"></i>
                                    <span>Real-time data visualization</span>
                                </li>
                                <li class="flex items-start">
                                    <i class="fas fa-check text-green-600 mr-2 mt-1"></i>
                                    <span>Export capabilities (PDF, Excel)</span>
                                </li>
                                <li class="flex items-start">
                                    <i class="fas fa-check text-green-600 mr-2 mt-1"></i>
                                    <span>Advanced filtering and search</span>
                                </li>
                                <li class="flex items-start">
                                    <i class="fas fa-check text-green-600 mr-2 mt-1"></i>
                                    <span>Interactive data tables</span>
                                </li>
                            </ul>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-3">Key Features</h3>
                            <div class="space-y-3">
                                <div class="p-3 bg-gray-50 rounded-lg">
                                    <h4 class="font-medium text-gray-900">Statistics Dashboard</h4>
                                    <p class="text-sm text-gray-600">Visual summary cards showing key metrics and totals
                                    </p>
                                </div>
                                <div class="p-3 bg-gray-50 rounded-lg">
                                    <h4 class="font-medium text-gray-900">Advanced Search</h4>
                                    <p class="text-sm text-gray-600">Multi-field search with real-time filtering</p>
                                </div>
                                <div class="p-3 bg-gray-50 rounded-lg">
                                    <h4 class="font-medium text-gray-900">Data Export</h4>
                                    <p class="text-sm text-gray-600">Download reports in PDF or Excel format</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Report Types Section -->
            <section id="reports" class="mb-12">
                <div class="bg-white border border-gray-200 rounded-lg p-6 shadow-sm">
                    <h2 class="text-2xl font-semibold text-gray-900 mb-6">
                        <i class="fas fa-file-alt text-blue-600 mr-3"></i>
                        Available Report Types
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <!-- Customer Reports -->
                        <div class="p-4 border border-gray-200 rounded-lg hover:border-blue-300 transition-colors">
                            <div class="flex items-center mb-3">
                                <i class="fas fa-users text-blue-600 text-xl mr-3"></i>
                                <h3 class="text-lg font-semibold text-gray-900">Customer Reports</h3>
                            </div>
                            <p class="text-gray-600 mb-3">Comprehensive customer directory with contact information, GST
                                details, and statistics.</p>
                            <ul class="text-sm text-gray-600 space-y-1">
                                <li>• Contact information management</li>
                                <li>• GST number tracking</li>
                                <li>• Email and phone statistics</li>
                            </ul>
                        </div>

                        <!-- Supplier Reports -->
                        <div class="p-4 border border-gray-200 rounded-lg hover:border-blue-300 transition-colors">
                            <div class="flex items-center mb-3">
                                <i class="fas fa-truck text-green-600 text-xl mr-3"></i>
                                <h3 class="text-lg font-semibold text-gray-900">Supplier Reports</h3>
                            </div>
                            <p class="text-gray-600 mb-3">Detailed supplier information with contact persons, locations,
                                and business details.</p>
                            <ul class="text-sm text-gray-600 space-y-1">
                                <li>• Supplier contact management</li>
                                <li>• Location tracking</li>
                                <li>• Contact person details</li>
                            </ul>
                        </div>

                        <!-- Payment Reports -->
                        <div class="p-4 border border-gray-200 rounded-lg hover:border-blue-300 transition-colors">
                            <div class="flex items-center mb-3">
                                <i class="fas fa-receipt text-purple-600 text-xl mr-3"></i>
                                <h3 class="text-lg font-semibold text-gray-900">Payment Reports</h3>
                            </div>
                            <p class="text-gray-600 mb-3">Track payment status, amounts, and pending balances with
                                detailed analytics.</p>
                            <ul class="text-sm text-gray-600 space-y-1">
                                <li>• Payment status tracking</li>
                                <li>• Amount breakdowns</li>
                                <li>• Pending balance analysis</li>
                            </ul>
                        </div>

                        <!-- Purchase Reports -->
                        <div class="p-4 border border-gray-200 rounded-lg hover:border-blue-300 transition-colors">
                            <div class="flex items-center mb-3">
                                <i class="fas fa-shopping-cart text-orange-600 text-xl mr-3"></i>
                                <h3 class="text-lg font-semibold text-gray-900">Purchase Reports</h3>
                            </div>
                            <p class="text-gray-600 mb-3">Monitor purchase orders, supplier transactions, and total
                                spending analysis.</p>
                            <ul class="text-sm text-gray-600 space-y-1">
                                <li>• Purchase order tracking</li>
                                <li>• Supplier transaction history</li>
                                <li>• Spending analytics</li>
                            </ul>
                        </div>

                        <!-- Quotation Reports -->
                        <div class="p-4 border border-gray-200 rounded-lg hover:border-blue-300 transition-colors">
                            <div class="flex items-center mb-3">
                                <i class="fas fa-file-invoice text-indigo-600 text-xl mr-3"></i>
                                <h3 class="text-lg font-semibold text-gray-900">Quotation Reports</h3>
                            </div>
                            <p class="text-gray-600 mb-3">Analyze quotations, customer proposals, and conversion rates.
                            </p>
                            <ul class="text-sm text-gray-600 space-y-1">
                                <li>• Quotation tracking</li>
                                <li>• Customer proposal analysis</li>
                                <li>• Conversion rate monitoring</li>
                            </ul>
                        </div>

                        <!-- Stock Reports -->
                        <div class="p-4 border border-gray-200 rounded-lg hover:border-blue-300 transition-colors">
                            <div class="flex items-center mb-3">
                                <i class="fas fa-boxes text-red-600 text-xl mr-3"></i>
                                <h3 class="text-lg font-semibold text-gray-900">Stock Reports</h3>
                            </div>
                            <p class="text-gray-600 mb-3">Monitor inventory levels, stock movements, and availability
                                status.</p>
                            <ul class="text-sm text-gray-600 space-y-1">
                                <li>• Inventory level tracking</li>
                                <li>• Stock movement analysis</li>
                                <li>• Availability status monitoring</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Keyboard Shortcuts Section -->
            <section id="shortcuts" class="mb-12">
                <div class="bg-white border border-gray-200 rounded-lg p-6 shadow-sm">
                    <h2 class="text-2xl font-semibold text-gray-900 mb-6">
                        <i class="fas fa-keyboard text-blue-600 mr-3"></i>
                        Keyboard Shortcuts
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- Global Shortcuts -->
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Global Shortcuts</h3>
                            <div class="space-y-3">
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                    <span class="text-gray-700">Show Help</span>
                                    <kbd
                                        class="px-3 py-1 bg-gray-200 border border-gray-300 rounded text-sm font-mono">H</kbd>
                                </div>
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                    <span class="text-gray-700">Back to Reports</span>
                                    <kbd
                                        class="px-3 py-1 bg-gray-200 border border-gray-300 rounded text-sm font-mono">Esc</kbd>
                                </div>
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                    <span class="text-gray-700">Focus Search</span>
                                    <kbd
                                        class="px-3 py-1 bg-gray-200 border border-gray-300 rounded text-sm font-mono">S</kbd>
                                </div>
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                    <span class="text-gray-700">Print Report</span>
                                    <kbd
                                        class="px-3 py-1 bg-gray-200 border border-gray-300 rounded text-sm font-mono">P</kbd>
                                </div>
                            </div>
                        </div>

                        <!-- Report Specific Shortcuts -->
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Report Navigation</h3>
                            <div class="space-y-3">
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                    <span class="text-gray-700">Customer Reports</span>
                                    <kbd
                                        class="px-3 py-1 bg-gray-200 border border-gray-300 rounded text-sm font-mono">1</kbd>
                                </div>
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                    <span class="text-gray-700">Supplier Reports</span>
                                    <kbd
                                        class="px-3 py-1 bg-gray-200 border border-gray-300 rounded text-sm font-mono">2</kbd>
                                </div>
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                    <span class="text-gray-700">Payment Reports</span>
                                    <kbd
                                        class="px-3 py-1 bg-gray-200 border border-gray-300 rounded text-sm font-mono">3</kbd>
                                </div>
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                    <span class="text-gray-700">Export/Download</span>
                                    <kbd
                                        class="px-3 py-1 bg-gray-200 border border-gray-300 rounded text-sm font-mono">D</kbd>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Tips & Tricks Section -->
            <section id="tips" class="mb-12">
                <div class="bg-white border border-gray-200 rounded-lg p-6 shadow-sm">
                    <h2 class="text-2xl font-semibold text-gray-900 mb-6">
                        <i class="fas fa-lightbulb text-blue-600 mr-3"></i>
                        Tips & Best Practices
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Efficiency Tips</h3>
                            <div class="space-y-4">
                                <div class="flex items-start">
                                    <i class="fas fa-search text-blue-600 mr-3 mt-1"></i>
                                    <div>
                                        <h4 class="font-medium text-gray-900">Smart Search</h4>
                                        <p class="text-sm text-gray-600">Use specific keywords for faster results.
                                            Search works across multiple fields simultaneously.</p>
                                    </div>
                                </div>
                                <div class="flex items-start">
                                    <i class="fas fa-sort text-green-600 mr-3 mt-1"></i>
                                    <div>
                                        <h4 class="font-medium text-gray-900">Table Sorting</h4>
                                        <p class="text-sm text-gray-600">Click any column header to sort data. Click
                                            again to reverse the order.</p>
                                    </div>
                                </div>
                                <div class="flex items-start">
                                    <i class="fas fa-calendar text-purple-600 mr-3 mt-1"></i>
                                    <div>
                                        <h4 class="font-medium text-gray-900">Date Filtering</h4>
                                        <p class="text-sm text-gray-600">Use date range filters when exporting for more
                                            targeted reports.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Export Guidelines</h3>
                            <div class="space-y-4">
                                <div class="flex items-start">
                                    <i class="fas fa-file-pdf text-red-600 mr-3 mt-1"></i>
                                    <div>
                                        <h4 class="font-medium text-gray-900">PDF Reports</h4>
                                        <p class="text-sm text-gray-600">Best for printing and formal documentation.
                                            Includes formatting and logos.</p>
                                    </div>
                                </div>
                                <div class="flex items-start">
                                    <i class="fas fa-file-excel text-green-600 mr-3 mt-1"></i>
                                    <div>
                                        <h4 class="font-medium text-gray-900">Excel Export</h4>
                                        <p class="text-sm text-gray-600">Perfect for data analysis and further
                                            processing. Maintains data structure.</p>
                                    </div>
                                </div>
                                <div class="flex items-start">
                                    <i class="fas fa-clock text-orange-600 mr-3 mt-1"></i>
                                    <div>
                                        <h4 class="font-medium text-gray-900">Regular Exports</h4>
                                        <p class="text-sm text-gray-600">Schedule regular exports for backup and
                                            historical analysis purposes.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Contact Section -->
            <section class="bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-lg p-6">
                <div class="text-center">
                    <h2 class="text-2xl font-semibold text-gray-900 mb-4">
                        <i class="fas fa-question-circle text-blue-600 mr-3"></i>
                        Need More Help?
                    </h2>
                    <p class="text-gray-600 mb-6">
                        If you need additional assistance or have questions about specific features,
                        don't hesitate to reach out to our support team.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4 justify-center">
                        <a href="{{ route('reports.index') }}"
                            class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                            <i class="fas fa-chart-bar mr-2"></i>
                            Return to Reports
                        </a>
                        <a href="{{ route('dashboard') }}"
                            class="inline-flex items-center px-6 py-3 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-lg transition-colors">
                            <i class="fas fa-home mr-2"></i>
                            Go to Dashboard
                        </a>
                    </div>
                </div>
            </section>
        </div>
    </div>
</x-app-layout>
