<x-app-layout>
    <x-slot name="title">
        {{ __('Reports Dashboard') }} - {{ config('app.name', 'IMS') }}
    </x-slot>

    <div class="py-6 mt-20 ml-4 sm:ml-64">
        <div class="w-full mx-auto max-w-7xl sm:px-6 lg:px-8">
            <!-- Breadcrumb -->
            <x-bread-crumb-navigation />

            <!-- Report Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mt-8">
                <!-- Customer -->
                <div class="bg-gray-800 p-6 rounded-xl shadow hover:bg-gray-700 transition duration-300">
                    <h3 class="text-xl text-white font-bold mb-3">Customer Reports</h3>
                    <p class="text-gray-300 text-sm mb-4">View and export customer report data.</p>
                    <a href="{{ route('reports.customers') }}" class="text-indigo-400 hover:text-indigo-600 font-medium">
                        Generate Report
                    </a>
                </div>

                <!-- Supplier -->
                <div class="bg-gray-800 p-6 rounded-xl shadow hover:bg-gray-700 transition duration-300">
                    <h3 class="text-xl text-white font-bold mb-3">Supplier Reports</h3>
                    <p class="text-gray-300 text-sm mb-4">View and export supplier report data.</p>
                    <a href="{{ route('reports.suppliers') }}" class="text-indigo-400 hover:text-indigo-600 font-medium">
                        Generate Report
                    </a>
                </div>

                <!-- Invoice -->
                <div class="bg-gray-800 p-6 rounded-xl shadow hover:bg-gray-700 transition duration-300">
                    <h3 class="text-xl text-white font-bold mb-3">Invoice Reports</h3>
                    <p class="text-gray-300 text-sm mb-4">View and export invoice report data.</p>
                    <a href="{{ route('reports.invoices') }}" class="text-indigo-400 hover:text-indigo-600 font-medium">
                        Generate Report
                    </a>
                </div>

                <!-- Quotation -->
                <div class="bg-gray-800 p-6 rounded-xl shadow hover:bg-gray-700 transition duration-300">
                    <h3 class="text-xl text-white font-bold mb-3">Quotation Reports</h3>
                    <p class="text-gray-300 text-sm mb-4">View and export quotation report data.</p>
                    <a href="{{ route('reports.quotations') }}" class="text-indigo-400 hover:text-indigo-600 font-medium">
                        Generate Report
                    </a>
                </div>

                <!-- Stock -->
                <div class="bg-gray-800 p-6 rounded-xl shadow hover:bg-gray-700 transition duration-300">
                    <h3 class="text-xl text-white font-bold mb-3">Stock Reports</h3>
                    <p class="text-gray-300 text-sm mb-4">View and export stock inventory data.</p>
                    <a href="{{ route('reports.stocks') }}" class="text-indigo-400 hover:text-indigo-600 font-medium">
                        Generate Report
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
