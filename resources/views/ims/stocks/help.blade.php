<x-app-layout>
    <x-slot name="title">
        {{ __('Stock Help') }} - {{ config('app.name', 'IMS') }}
    </x-slot>

    <div class="bg-white min-h-screen" x-data="stockHelpManager()" x-init="init()">
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
                            <a href="{{ route('stocks.index') }}"
                                class="text-sm font-medium text-gray-700 hover:text-blue-600">
                                Stocks
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
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Stock Management Help</h1>
                    <p class="text-sm text-gray-600 mt-1">Complete guide for managing stock entries and inventory</p>
                </div>
                <div class="flex items-center space-x-3">
                    <!-- Print Button -->
                    <button @click="printHelp()"
                        class="inline-flex items-center px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition-colors">
                        <i class="fas fa-print w-4 h-4 mr-2"></i>
                        Print
                    </button>
                    <a href="{{ route('stocks.index') }}"
                        class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                        <i class="fas fa-arrow-left w-4 h-4 mr-2"></i>
                        Back to Stocks
                    </a>
                </div>
            </div>
        </div>

        <!-- Content -->
        <div class="p-6">
            <!-- Tab Navigation -->
            <div class="border-b border-gray-200 mb-6">
                <nav class="-mb-px flex space-x-8">
                    <button type="button" @click="activeTab = 'overview'"
                        :class="activeTab === 'overview' ? 'border-blue-500 text-blue-600' :
                            'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                        class="py-2 px-1 border-b-2 font-medium text-sm">
                        <i class="fas fa-info-circle mr-2"></i>
                        Overview
                    </button>
                    <button type="button" @click="activeTab = 'management'"
                        :class="activeTab === 'management' ? 'border-blue-500 text-blue-600' :
                            'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                        class="py-2 px-1 border-b-2 font-medium text-sm">
                        <i class="fas fa-tasks mr-2"></i>
                        Stock Management
                    </button>
                    <button type="button" @click="activeTab = 'features'"
                        :class="activeTab === 'features' ? 'border-blue-500 text-blue-600' :
                            'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                        class="py-2 px-1 border-b-2 font-medium text-sm">
                        <i class="fas fa-star mr-2"></i>
                        Features
                    </button>
                    <button type="button" @click="activeTab = 'hotkeys'"
                        :class="activeTab === 'hotkeys' ? 'border-blue-500 text-blue-600' :
                            'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                        class="py-2 px-1 border-b-2 font-medium text-sm">
                        <i class="fas fa-keyboard mr-2"></i>
                        Keyboard Shortcuts
                    </button>
                </nav>
            </div>

            <!-- Tab Content -->
            <div class="space-y-6">
                <!-- Overview Tab -->
                <div x-show="activeTab === 'overview'" x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <!-- What is Stock Management -->
                        <div class="bg-white border border-gray-200 rounded-lg p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">
                                <i class="fas fa-question-circle text-blue-600 mr-2"></i>
                                What is Stock Management?
                            </h3>
                            <div class="space-y-3 text-gray-700">
                                <p>Stock Management in IMS allows you to:</p>
                                <ul class="list-disc ml-5 space-y-1">
                                    <li>Track inventory levels for all products</li>
                                    <li>Manage stock entries from purchases and manual entries</li>
                                    <li>Monitor batch codes and expiry dates</li>
                                    <li>Track sold quantities and remaining stock</li>
                                    <li>Generate stock reports and analytics</li>
                                    <li>Set stock alerts and notifications</li>
                                </ul>
                            </div>
                        </div>

                        <!-- Getting Started -->
                        <div class="bg-white border border-gray-200 rounded-lg p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">
                                <i class="fas fa-play-circle text-green-600 mr-2"></i>
                                Getting Started
                            </h3>
                            <div class="space-y-3 text-gray-700">
                                <p>Follow these steps to get started:</p>
                                <ol class="list-decimal ml-5 space-y-1">
                                    <li>Click "Add Stock Entry" to create your first stock entry</li>
                                    <li>Select the product and supplier</li>
                                    <li>Enter unit type, quantity, and price</li>
                                    <li>System generates unique batch code automatically</li>
                                    <li>Stock is ready for sales tracking</li>
                                </ol>
                                <div class="mt-4">
                                    <a href="{{ route('stocks.create') }}"
                                        class="inline-flex items-center px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded">
                                        <i class="fas fa-plus mr-2"></i>
                                        Create First Stock Entry
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Management Tab -->
                <div x-show="activeTab === 'management'" x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                    
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <!-- Stock Entry Types -->
                        <div class="bg-white border border-gray-200 rounded-lg p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">
                                <i class="fas fa-layer-group text-purple-600 mr-2"></i>
                                Stock Entry Types
                            </h3>
                            <div class="space-y-4">
                                <div class="border-l-4 border-blue-500 pl-4">
                                    <h4 class="font-medium text-gray-900">Manual Entry</h4>
                                    <p class="text-sm text-gray-600">Manually created stock entries for inventory adjustments or initial stock setup.</p>
                                </div>
                                <div class="border-l-4 border-green-500 pl-4">
                                    <h4 class="font-medium text-gray-900">Purchase Entry</h4>
                                    <p class="text-sm text-gray-600">Stock entries automatically created from purchase orders.</p>
                                </div>
                            </div>
                        </div>

                        <!-- Stock Editing Rules -->
                        <div class="bg-white border border-gray-200 rounded-lg p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">
                                <i class="fas fa-edit text-orange-600 mr-2"></i>
                                Editing Rules
                            </h3>
                            <div class="space-y-4">
                                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                                    <h4 class="font-medium text-yellow-800 mb-2">Purchase Entries</h4>
                                    <p class="text-sm text-yellow-700">Stock entries from purchases have limited editing. Product and supplier cannot be changed.</p>
                                </div>
                                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                    <h4 class="font-medium text-blue-800 mb-2">Manual Entries</h4>
                                    <p class="text-sm text-blue-700">Full editing capability for quantity and price. Batch code is locked after creation.</p>
                                </div>
                            </div>
                        </div>

                        <!-- Batch Code System -->
                        <div class="bg-white border border-gray-200 rounded-lg p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">
                                <i class="fas fa-barcode text-indigo-600 mr-2"></i>
                                Batch Code System
                            </h3>
                            <div class="space-y-3 text-gray-700">
                                <p>Every stock entry gets a unique batch code:</p>
                                <ul class="list-disc ml-5 space-y-1">
                                    <li>Format: YYYYMMDD-XXXX (Date + Counter)</li>
                                    <li>Automatically generated on creation</li>
                                    <li>Cannot be modified after creation</li>
                                    <li>Used for inventory tracking and FIFO</li>
                                </ul>
                            </div>
                        </div>

                        <!-- Stock Tracking -->
                        <div class="bg-white border border-gray-200 rounded-lg p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">
                                <i class="fas fa-chart-line text-teal-600 mr-2"></i>
                                Stock Tracking
                            </h3>
                            <div class="space-y-3 text-gray-700">
                                <p>Track your inventory effectively:</p>
                                <ul class="list-disc ml-5 space-y-1">
                                    <li>Monitor current stock levels</li>
                                    <li>Track sales and remaining quantities</li>
                                    <li>View stock history and movements</li>
                                    <li>Generate inventory reports</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Features Tab -->
                <div x-show="activeTab === 'features'" x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Core Features -->
                        <div class="bg-white border border-gray-200 rounded-lg p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">
                                <i class="fas fa-cogs text-blue-600 mr-2"></i>
                                Core Features
                            </h3>
                            <div class="space-y-3">
                                <div class="flex items-start space-x-3">
                                    <i class="fas fa-check-circle text-green-500 mt-1"></i>
                                    <div>
                                        <h4 class="font-medium text-gray-900">Inventory Management</h4>
                                        <p class="text-sm text-gray-600">Complete stock tracking with real-time updates</p>
                                    </div>
                                </div>
                                <div class="flex items-start space-x-3">
                                    <i class="fas fa-check-circle text-green-500 mt-1"></i>
                                    <div>
                                        <h4 class="font-medium text-gray-900">Batch Tracking</h4>
                                        <p class="text-sm text-gray-600">Unique batch codes for every stock entry</p>
                                    </div>
                                </div>
                                <div class="flex items-start space-x-3">
                                    <i class="fas fa-check-circle text-green-500 mt-1"></i>
                                    <div>
                                        <h4 class="font-medium text-gray-900">Purchase Integration</h4>
                                        <p class="text-sm text-gray-600">Automatic stock creation from purchase orders</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Advanced Features -->
                        <div class="bg-white border border-gray-200 rounded-lg p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">
                                <i class="fas fa-star text-purple-600 mr-2"></i>
                                Advanced Features
                            </h3>
                            <div class="space-y-3">
                                <div class="flex items-start space-x-3">
                                    <i class="fas fa-check-circle text-green-500 mt-1"></i>
                                    <div>
                                        <h4 class="font-medium text-gray-900">Sales Tracking</h4>
                                        <p class="text-sm text-gray-600">Track quantities sold from each batch</p>
                                    </div>
                                </div>
                                <div class="flex items-start space-x-3">
                                    <i class="fas fa-check-circle text-green-500 mt-1"></i>
                                    <div>
                                        <h4 class="font-medium text-gray-900">Stock Reports</h4>
                                        <p class="text-sm text-gray-600">Generate detailed inventory reports</p>
                                    </div>
                                </div>
                                <div class="flex items-start space-x-3">
                                    <i class="fas fa-check-circle text-green-500 mt-1"></i>
                                    <div>
                                        <h4 class="font-medium text-gray-900">Multi-Unit Support</h4>
                                        <p class="text-sm text-gray-600">Support for different unit types (pieces, kg, etc.)</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Hotkeys Tab -->
                <div x-show="activeTab === 'hotkeys'" x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                    
                    <div class="bg-white border border-gray-200 rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-6">
                            <i class="fas fa-keyboard text-indigo-600 mr-2"></i>
                            Keyboard Shortcuts
                        </h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Navigation Shortcuts -->
                            <div>
                                <h4 class="text-md font-medium text-gray-900 mb-4">Navigation</h4>
                                <div class="space-y-2">
                                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                                        <span class="text-gray-700">Add New Stock</span>
                                        <kbd class="px-2 py-1 bg-gray-100 text-gray-600 text-xs rounded">Ctrl + N</kbd>
                                    </div>
                                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                                        <span class="text-gray-700">Search Stocks</span>
                                        <kbd class="px-2 py-1 bg-gray-100 text-gray-600 text-xs rounded">Ctrl + F</kbd>
                                    </div>
                                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                                        <span class="text-gray-700">Refresh List</span>
                                        <kbd class="px-2 py-1 bg-gray-100 text-gray-600 text-xs rounded">F5</kbd>
                                    </div>
                                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                                        <span class="text-gray-700">Help Page</span>
                                        <kbd class="px-2 py-1 bg-gray-100 text-gray-600 text-xs rounded">F1</kbd>
                                    </div>
                                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                                        <span class="text-gray-700">Go to Dashboard</span>
                                        <kbd class="px-2 py-1 bg-gray-100 text-gray-600 text-xs rounded">Ctrl + Home</kbd>
                                    </div>
                                </div>
                            </div>

                            <!-- Form Shortcuts -->
                            <div>
                                <h4 class="text-md font-medium text-gray-900 mb-4">Form Actions</h4>
                                <div class="space-y-2">
                                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                                        <span class="text-gray-700">Save Entry</span>
                                        <kbd class="px-2 py-1 bg-gray-100 text-gray-600 text-xs rounded">Ctrl + S</kbd>
                                    </div>
                                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                                        <span class="text-gray-700">Cancel/Go Back</span>
                                        <kbd class="px-2 py-1 bg-gray-100 text-gray-600 text-xs rounded">Esc</kbd>
                                    </div>
                                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                                        <span class="text-gray-700">Print Current Page</span>
                                        <kbd class="px-2 py-1 bg-gray-100 text-gray-600 text-xs rounded">Ctrl + P</kbd>
                                    </div>
                                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                                        <span class="text-gray-700">Select All</span>
                                        <kbd class="px-2 py-1 bg-gray-100 text-gray-600 text-xs rounded">Ctrl + A</kbd>
                                    </div>
                                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                                        <span class="text-gray-700">Find in Page</span>
                                        <kbd class="px-2 py-1 bg-gray-100 text-gray-600 text-xs rounded">Ctrl + F</kbd>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Additional Shortcuts Section -->
                        <div class="mt-6">
                            <h4 class="text-md font-medium text-gray-900 mb-4">Stock Management Shortcuts</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-2">
                                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                                        <span class="text-gray-700">View Stock Details</span>
                                        <kbd class="px-2 py-1 bg-gray-100 text-gray-600 text-xs rounded">Click Eye Icon</kbd>
                                    </div>
                                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                                        <span class="text-gray-700">Edit Stock (Manual Only)</span>
                                        <kbd class="px-2 py-1 bg-gray-100 text-gray-600 text-xs rounded">Click Edit Icon</kbd>
                                    </div>
                                </div>
                                <div class="space-y-2">
                                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                                        <span class="text-gray-700">Delete Stock</span>
                                        <kbd class="px-2 py-1 bg-gray-100 text-gray-600 text-xs rounded">Click Delete Icon</kbd>
                                    </div>
                                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                                        <span class="text-gray-700">Export to Excel</span>
                                        <kbd class="px-2 py-1 bg-gray-100 text-gray-600 text-xs rounded">Export Button</kbd>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function stockHelpManager() {
            return {
                activeTab: 'overview',

                init() {
                    // Set default tab
                    this.activeTab = 'overview';
                },

                printHelp() {
                    window.print();
                }
            }
        }
    </script>
</x-app-layout>
