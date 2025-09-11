<x-app-layout>
    <x-slot name="title">
        {{ __('Supplier Help') }} - {{ config('app.name', 'IMS') }}
    </x-slot>

    <div class="bg-white min-h-screen" x-data="supplierHelpManager()" x-init="init()">
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
                            <a href="{{ route('suppliers.index') }}"
                                class="text-sm font-medium text-gray-700 hover:text-blue-600">
                                Suppliers
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
                    <h1 class="text-2xl font-bold text-gray-900">Supplier Management Help</h1>
                    <p class="text-sm text-gray-600 mt-1">Complete guide for managing suppliers and their information</p>
                </div>
                <div class="flex items-center space-x-3">
                    <!-- Print Button -->
                    <button @click="printHelp()"
                        class="inline-flex items-center px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition-colors">
                        <i class="fas fa-print w-4 h-4 mr-2"></i>
                        Print
                    </button>
                    <a href="{{ route('suppliers.index') }}"
                        class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                        <i class="fas fa-arrow-left w-4 h-4 mr-2"></i>
                        Back to Suppliers
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
                        Supplier Management
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
                        <!-- What is Supplier Management -->
                        <div class="bg-white border border-gray-200 rounded-lg p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">
                                <i class="fas fa-question-circle text-blue-600 mr-2"></i>
                                What is Supplier Management?
                            </h3>
                            <div class="space-y-3 text-gray-700">
                                <p>Supplier Management in IMS allows you to:</p>
                                <ul class="list-disc ml-5 space-y-1">
                                    <li>Store and manage supplier contact information</li>
                                    <li>Track supplier addresses and shipping details</li>
                                    <li>Manage multiple contact persons per supplier</li>
                                    <li>Assign products to specific suppliers</li>
                                    <li>Track purchase history and transactions</li>
                                    <li>Generate supplier reports and analytics</li>
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
                                    <li>Click "New Supplier" to add your first supplier</li>
                                    <li>Fill in the supplier information in the three tabs</li>
                                    <li>Add contact persons for different departments</li>
                                    <li>Assign products to the supplier</li>
                                    <li>Start creating purchase orders</li>
                                </ol>
                                <div class="mt-4">
                                    <a href="{{ route('suppliers.create') }}"
                                        class="inline-flex items-center px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded">
                                        <i class="fas fa-plus mr-2"></i>
                                        Create First Supplier
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Management Tab -->
                <div x-show="activeTab === 'management'" x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                    <div class="space-y-6">
                        <!-- Creating Suppliers -->
                        <div class="bg-white border border-gray-200 rounded-lg p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">
                                <i class="fas fa-plus-circle text-green-600 mr-2"></i>
                                Creating a New Supplier
                            </h3>
                            <div class="space-y-4">
                                <div class="border-l-4 border-blue-500 pl-4">
                                    <h4 class="font-medium text-gray-900">Step 1: Basic Information</h4>
                                    <p class="text-gray-600">Enter supplier company name, main contact person, email, and phone number. All fields marked with (*) are required.</p>
                                </div>
                                <div class="border-l-4 border-blue-500 pl-4">
                                    <h4 class="font-medium text-gray-900">Step 2: Address Details</h4>
                                    <p class="text-gray-600">Provide complete address information including street address, city, state, postal code, and country for shipping and billing.</p>
                                </div>
                                <div class="border-l-4 border-blue-500 pl-4">
                                    <h4 class="font-medium text-gray-900">Step 3: Contact Persons</h4>
                                    <p class="text-gray-600">Add multiple contact persons for different departments (sales, support, billing, etc.) with their direct contact information.</p>
                                </div>
                            </div>
                        </div>

                        <!-- Managing Suppliers -->
                        <div class="bg-white border border-gray-200 rounded-lg p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">
                                <i class="fas fa-edit text-blue-600 mr-2"></i>
                                Managing Existing Suppliers
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <h4 class="font-medium text-gray-900 mb-2">Viewing Suppliers</h4>
                                    <ul class="text-gray-600 space-y-1">
                                        <li>• Click on any supplier to view detailed information</li>
                                        <li>• View tabbed sections: Overview, Products, Transactions, Contacts</li>
                                        <li>• See quick stats and performance metrics</li>
                                        <li>• Access contact information quickly</li>
                                    </ul>
                                </div>
                                <div>
                                    <h4 class="font-medium text-gray-900 mb-2">Editing Suppliers</h4>
                                    <ul class="text-gray-600 space-y-1">
                                        <li>• Click "Edit" button to modify supplier information</li>
                                        <li>• Update basic information, address, and contacts</li>
                                        <li>• Add or remove contact persons</li>
                                        <li>• Changes are saved immediately</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- Search and Filter -->
                        <div class="bg-white border border-gray-200 rounded-lg p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">
                                <i class="fas fa-search text-purple-600 mr-2"></i>
                                Search and Filter
                            </h3>
                            <div class="space-y-3">
                                <p class="text-gray-600">Find suppliers quickly using various search methods:</p>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <div class="border border-gray-200 rounded p-3">
                                        <h5 class="font-medium text-gray-900">By Company Name</h5>
                                        <p class="text-sm text-gray-600">Search by supplier company name</p>
                                    </div>
                                    <div class="border border-gray-200 rounded p-3">
                                        <h5 class="font-medium text-gray-900">By Contact Person</h5>
                                        <p class="text-sm text-gray-600">Find by contact person name</p>
                                    </div>
                                    <div class="border border-gray-200 rounded p-3">
                                        <h5 class="font-medium text-gray-900">By Supplier ID</h5>
                                        <p class="text-sm text-gray-600">Search using unique supplier ID</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Features Tab -->
                <div x-show="activeTab === 'features'" x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Contact Management -->
                        <div class="bg-white border border-gray-200 rounded-lg p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">
                                <i class="fas fa-users text-blue-600 mr-2"></i>
                                Contact Management
                            </h3>
                            <ul class="space-y-2 text-gray-700">
                                <li class="flex items-start">
                                    <i class="fas fa-check-circle text-green-500 mr-2 mt-1"></i>
                                    <span>Multiple contact persons per supplier</span>
                                </li>
                                <li class="flex items-start">
                                    <i class="fas fa-check-circle text-green-500 mr-2 mt-1"></i>
                                    <span>Department-wise contact organization</span>
                                </li>
                                <li class="flex items-start">
                                    <i class="fas fa-check-circle text-green-500 mr-2 mt-1"></i>
                                    <span>Direct phone and email links</span>
                                </li>
                                <li class="flex items-start">
                                    <i class="fas fa-check-circle text-green-500 mr-2 mt-1"></i>
                                    <span>Position and role tracking</span>
                                </li>
                            </ul>
                        </div>

                        <!-- Product Assignment -->
                        <div class="bg-white border border-gray-200 rounded-lg p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">
                                <i class="fas fa-box text-green-600 mr-2"></i>
                                Product Assignment
                            </h3>
                            <ul class="space-y-2 text-gray-700">
                                <li class="flex items-start">
                                    <i class="fas fa-check-circle text-green-500 mr-2 mt-1"></i>
                                    <span>Assign products to specific suppliers</span>
                                </li>
                                <li class="flex items-start">
                                    <i class="fas fa-check-circle text-green-500 mr-2 mt-1"></i>
                                    <span>Track supplier-product relationships</span>
                                </li>
                                <li class="flex items-start">
                                    <i class="fas fa-check-circle text-green-500 mr-2 mt-1"></i>
                                    <span>Quick product availability checking</span>
                                </li>
                                <li class="flex items-start">
                                    <i class="fas fa-check-circle text-green-500 mr-2 mt-1"></i>
                                    <span>Inventory management integration</span>
                                </li>
                            </ul>
                        </div>

                        <!-- Data Export -->
                        <div class="bg-white border border-gray-200 rounded-lg p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">
                                <i class="fas fa-download text-purple-600 mr-2"></i>
                                Data Export & Reporting
                            </h3>
                            <ul class="space-y-2 text-gray-700">
                                <li class="flex items-start">
                                    <i class="fas fa-check-circle text-green-500 mr-2 mt-1"></i>
                                    <span>Export supplier data to PDF</span>
                                </li>
                                <li class="flex items-start">
                                    <i class="fas fa-check-circle text-green-500 mr-2 mt-1"></i>
                                    <span>Excel export for data analysis</span>
                                </li>
                                <li class="flex items-start">
                                    <i class="fas fa-check-circle text-green-500 mr-2 mt-1"></i>
                                    <span>Print-friendly supplier profiles</span>
                                </li>
                                <li class="flex items-start">
                                    <i class="fas fa-check-circle text-green-500 mr-2 mt-1"></i>
                                    <span>Custom report generation</span>
                                </li>
                            </ul>
                        </div>

                        <!-- Security & Backup -->
                        <div class="bg-white border border-gray-200 rounded-lg p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">
                                <i class="fas fa-shield-alt text-red-600 mr-2"></i>
                                Security & Data Protection
                            </h3>
                            <ul class="space-y-2 text-gray-700">
                                <li class="flex items-start">
                                    <i class="fas fa-check-circle text-green-500 mr-2 mt-1"></i>
                                    <span>Role-based access control</span>
                                </li>
                                <li class="flex items-start">
                                    <i class="fas fa-check-circle text-green-500 mr-2 mt-1"></i>
                                    <span>Activity logging and tracking</span>
                                </li>
                                <li class="flex items-start">
                                    <i class="fas fa-check-circle text-green-500 mr-2 mt-1"></i>
                                    <span>Automatic data backup</span>
                                </li>
                                <li class="flex items-start">
                                    <i class="fas fa-check-circle text-green-500 mr-2 mt-1"></i>
                                    <span>Secure data encryption</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Hotkeys Tab -->
                <div x-show="activeTab === 'hotkeys'" x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                    <div class="space-y-6">
                        <!-- Supplier Index Page Navigation -->
                        <div class="bg-white border border-gray-200 rounded-lg p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">
                                <i class="fas fa-list text-blue-600 mr-2"></i>
                                Supplier List Page Navigation
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="space-y-3">
                                    <div class="flex justify-between items-center">
                                        <span class="text-gray-700">Create New Supplier</span>
                                        <kbd class="px-2 py-1 bg-gray-100 border border-gray-300 rounded text-sm">N</kbd>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="text-gray-700">Search Suppliers</span>
                                        <kbd class="px-2 py-1 bg-gray-100 border border-gray-300 rounded text-sm">S</kbd>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="text-gray-700">Focus Search</span>
                                        <kbd class="px-2 py-1 bg-gray-100 border border-gray-300 rounded text-sm">Ctrl + F</kbd>
                                    </div>
                                </div>
                                <div class="space-y-3">
                                    <div class="flex justify-between items-center">
                                        <span class="text-gray-700">Refresh Page</span>
                                        <kbd class="px-2 py-1 bg-gray-100 border border-gray-300 rounded text-sm">F5</kbd>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="text-gray-700">Show Help</span>
                                        <kbd class="px-2 py-1 bg-gray-100 border border-gray-300 rounded text-sm">H</kbd>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="text-gray-700">Back to Dashboard</span>
                                        <kbd class="px-2 py-1 bg-gray-100 border border-gray-300 rounded text-sm">Esc</kbd>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Create/Edit Form Navigation -->
                        <div class="bg-white border border-gray-200 rounded-lg p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">
                                <i class="fas fa-edit text-green-600 mr-2"></i>
                                Create/Edit Form Navigation
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="space-y-3">
                                    <div class="flex justify-between items-center">
                                        <span class="text-gray-700">Save Supplier</span>
                                        <kbd class="px-2 py-1 bg-gray-100 border border-gray-300 rounded text-sm">Ctrl + S</kbd>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="text-gray-700">Next Tab</span>
                                        <kbd class="px-2 py-1 bg-gray-100 border border-gray-300 rounded text-sm">Ctrl + →</kbd>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="text-gray-700">Previous Tab</span>
                                        <kbd class="px-2 py-1 bg-gray-100 border border-gray-300 rounded text-sm">Ctrl + ←</kbd>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="text-gray-700">Add Contact Person</span>
                                        <kbd class="px-2 py-1 bg-gray-100 border border-gray-300 rounded text-sm">+ (Plus)</kbd>
                                    </div>
                                </div>
                                <div class="space-y-3">
                                    <div class="flex justify-between items-center">
                                        <span class="text-gray-700">Cancel & Exit</span>
                                        <kbd class="px-2 py-1 bg-gray-100 border border-gray-300 rounded text-sm">Esc</kbd>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="text-gray-700">Show Help</span>
                                        <kbd class="px-2 py-1 bg-gray-100 border border-gray-300 rounded text-sm">H</kbd>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="text-gray-700">Back to List</span>
                                        <kbd class="px-2 py-1 bg-gray-100 border border-gray-300 rounded text-sm">Ctrl + B</kbd>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="text-gray-700">View Supplier (Edit only)</span>
                                        <kbd class="px-2 py-1 bg-gray-100 border border-gray-300 rounded text-sm">Ctrl + V</kbd>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- View/Show Page Navigation -->
                        <div class="bg-white border border-gray-200 rounded-lg p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">
                                <i class="fas fa-eye text-purple-600 mr-2"></i>
                                View Supplier Page Navigation
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="space-y-3">
                                    <div class="flex justify-between items-center">
                                        <span class="text-gray-700">Edit Supplier</span>
                                        <kbd class="px-2 py-1 bg-gray-100 border border-gray-300 rounded text-sm">E</kbd>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="text-gray-700">Delete Supplier</span>
                                        <kbd class="px-2 py-1 bg-gray-100 border border-gray-300 rounded text-sm">Del</kbd>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="text-gray-700">Back to List</span>
                                        <kbd class="px-2 py-1 bg-gray-100 border border-gray-300 rounded text-sm">Esc</kbd>
                                    </div>
                                </div>
                                <div class="space-y-3">
                                    <div class="flex justify-between items-center">
                                        <span class="text-gray-700">Show Help</span>
                                        <kbd class="px-2 py-1 bg-gray-100 border border-gray-300 rounded text-sm">H</kbd>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="text-gray-700">Overview Tab</span>
                                        <kbd class="px-2 py-1 bg-gray-100 border border-gray-300 rounded text-sm">1</kbd>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="text-gray-700">Products Tab</span>
                                        <kbd class="px-2 py-1 bg-gray-100 border border-gray-300 rounded text-sm">2</kbd>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="text-gray-700">Transactions Tab</span>
                                        <kbd class="px-2 py-1 bg-gray-100 border border-gray-300 rounded text-sm">3</kbd>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Required Fields -->
                        <div class="bg-amber-50 border border-amber-200 rounded-lg p-6">
                            <h3 class="text-lg font-semibold text-amber-900 mb-4">
                                <i class="fas fa-exclamation-circle text-amber-600 mr-2"></i>
                                Required Fields for Supplier Creation
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <h4 class="font-semibold text-amber-900 mb-2">Supplier Information Tab:</h4>
                                    <ul class="space-y-1 text-amber-800">
                                        <li>• Company Name</li>
                                        <li>• Contact Person</li>
                                        <li>• Email Address</li>
                                        <li>• Phone Number</li>
                                    </ul>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-amber-900 mb-2">Address Details Tab:</h4>
                                    <ul class="space-y-1 text-amber-800">
                                        <li>• Street Address</li>
                                        <li>• City</li>
                                        <li>• State/Province</li>
                                        <li>• Country</li>
                                    </ul>
                                </div>
                            </div>
                            <div class="mt-4 p-3 bg-amber-100 rounded-lg">
                                <p class="text-sm text-amber-800">
                                    <strong>Note:</strong> Fields marked with a red asterisk (*) are mandatory. 
                                    GST Number and Website are optional fields. Contact Persons tab is optional but recommended.
                                </p>
                            </div>
                        </div>

                        <!-- Tips -->
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
                            <h3 class="text-lg font-semibold text-blue-900 mb-4">
                                <i class="fas fa-lightbulb text-yellow-500 mr-2"></i>
                                Pro Tips
                            </h3>
                            <div class="space-y-2 text-blue-800">
                                <p>• Use keyboard shortcuts to navigate faster without using the mouse</p>
                                <p>• Supplier ID is auto-generated when you save - no need to enter manually</p>
                                <p>• Use Ctrl+S to save forms even while typing in input fields</p>
                                <p>• Press H from any supplier page to access this help</p>
                                <p>• Tab navigation works with Ctrl+Arrow keys in multi-step forms</p>
                                <p>• All shortcuts work only when not typing in input fields (except Ctrl+S)</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function supplierHelpManager() {
            return {
                activeTab: 'overview',
                
                init() {
                    // Initialize any required data
                },
                
                printHelp() {
                    window.print();
                }
            }
        }
    </script>
    @endpush
</x-app-layout>
