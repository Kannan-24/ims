<div x-data="{
    sidebarOpen: false,
    userDropdown: false,
    showNotifications: false,
    darkMode: localStorage.getItem('darkMode') === 'true' || true,
}" x-init="$watch('darkMode', value => localStorage.setItem('darkMode', value));
if (darkMode) document.documentElement.classList.add('dark');
else document.documentElement.classList.remove('dark');" :class="{ 'dark': darkMode }">

    <!-- Top Navigation Bar -->
    <nav
        class="fixed top-0 z-50 w-full bg-white dark:bg-gray-900 border-b border-gray-200 dark:border-gray-700 shadow-lg backdrop-blur-lg bg-opacity-95 dark:bg-opacity-95 transition-all duration-300">
        <div class="flex items-center justify-between px-3 py-2 sm:px-4 lg:px-6">

            <!-- Logo & Sidebar Toggle -->
            <div class="flex items-center space-x-2 lg:space-x-4">
                <!-- Mobile Sidebar Toggle -->
                <button @click="sidebarOpen = !sidebarOpen" type="button"
                    class="p-2 text-gray-600 dark:text-gray-300 rounded-xl lg:hidden hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-white focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition-all duration-200">
                    <span class="sr-only">Open sidebar</span>
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                        <path clip-rule="evenodd" fill-rule="evenodd"
                            d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 10.5a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5a.75.75 0 01-.75-.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10z">
                        </path>
                    </svg>
                </button>

                <!-- Logo & Brand -->
                <a href="{{ route('dashboard') }}" class="flex items-center space-x-2 group">
                    <div
                        class="flex items-center justify-center w-10 h-10 bg-gradient-to-br from-blue-600 to-purple-600 rounded-xl shadow-lg group-hover:shadow-xl transition-all duration-300 group-hover:scale-105">
                        <x-application-logo class="w-6 h-6 text-white" />
                    </div>
                    <div class="hidden sm:block">
                        <h1
                            class="text-xl lg:text-2xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">
                            SKM&Co.
                        </h1>
                        <p class="text-xs text-gray-500 dark:text-gray-400 font-medium">Inventory Management</p>
                    </div>
                </a>
            </div>

            <!-- Center Search (Hidden on mobile) -->
            <div class="hidden md:flex flex-1 max-w-lg mx-8">
                <div class="relative w-full">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="w-5 h-5 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <input type="search"
                        class="block w-full pl-10 pr-4 py-2 border border-gray-200 dark:border-gray-700 rounded-xl bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-transparent transition-all duration-200"
                        placeholder="Search invoices, customers, products...">
                </div>
            </div>

            <!-- Right: Actions & User Profile -->
            <div class="flex items-center space-x-2 lg:space-x-4">

                <!-- Dark Mode Toggle -->
                <button @click="darkMode = !darkMode"
                    class="hidden sm:flex items-center justify-center w-10 h-10 text-gray-600 dark:text-gray-300 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-800 focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition-all duration-200">
                    <svg x-show="!darkMode" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z">
                        </path>
                    </svg>
                    <svg x-show="darkMode" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z">
                        </path>
                    </svg>
                </button>

                <!-- Quick Actions Dropdown -->
                <div class="relative hidden lg:block" x-data="{ quickActions: false }">
                    <button @click="quickActions = !quickActions"
                        class="flex items-center justify-center w-10 h-10 text-gray-600 dark:text-gray-300 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-800 focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition-all duration-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                    </button>

                    <div x-show="quickActions" @click.away="quickActions = false"
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-150"
                        x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                        class="absolute right-0 mt-2 w-64 bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 py-2 z-50">

                        <div class="px-4 py-2 border-b border-gray-200 dark:border-gray-700">
                            <h3 class="text-sm font-semibold text-gray-900 dark:text-gray-100">Quick Actions</h3>
                        </div>

                        <a href="{{ route('invoices.create') }}"
                            class="flex items-center px-4 py-3 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                            <svg class="w-5 h-5 mr-3 text-blue-500" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                </path>
                            </svg>
                            New Invoice
                        </a>

                        <a href="{{ route('quotations.create') }}"
                            class="flex items-center px-4 py-3 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                            <svg class="w-5 h-5 mr-3 text-green-500" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                                </path>
                            </svg>
                            New Quotation
                        </a>

                        <a href="{{ route('customers.create') }}"
                            class="flex items-center px-4 py-3 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                            <svg class="w-5 h-5 mr-3 text-purple-500" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            New Customer
                        </a>
                    </div>
                </div>

                <!-- Notification Bell -->
                <div class="relative" x-data="{ showNotifications: false }">
                    <!-- Notification Button -->
                    <button @click="showNotifications = !showNotifications"
                        class="relative flex items-center justify-center w-10 h-10 text-gray-600 dark:text-gray-300 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition-all duration-200">
                        <span class="sr-only">View notifications</span>
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14V9a6 6 0 00-12 0v5c0 .386-.146.774-.405 1.095L4 17h5m6 0a3 3 0 11-6 0" />
                        </svg>

                        @php
                            $draftCount = \App\Models\ims\Email::where('status', 'draft')->count();
                            $recentCount =
                                \App\Models\ims\Invoice::where('created_at', '>=', now()->subDays(3))->count() +
                                \App\Models\ims\Quotation::where('created_at', '>=', now()->subDays(3))->count() +
                                \App\Models\ims\Customer::where('created_at', '>=', now()->subDays(7))->count();
                            $totalNotifications = $draftCount + $recentCount + 1;
                        @endphp

                        @if ($totalNotifications > 0)
                            <span
                                class="absolute -top-1 -right-1 w-5 h-5 text-xs text-white bg-gradient-to-r from-red-500 to-red-600 rounded-full flex items-center justify-center font-bold shadow-lg animate-pulse">
                                {{ $totalNotifications > 9 ? '9+' : $totalNotifications }}
                            </span>
                        @endif
                    </button>

                    <!-- Notification Panel -->
                    <div x-show="showNotifications" x-cloak @click.away="showNotifications = false"
                        x-transition:enter="transition ease-out duration-300 transform"
                        x-transition:enter-start="translate-x-full opacity-0 scale-95"
                        x-transition:enter-end="translate-x-0 opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-200 transform"
                        x-transition:leave-start="translate-x-0 opacity-100 scale-100"
                        x-transition:leave-end="translate-x-full opacity-0 scale-95"
                        class="fixed top-0 right-0 z-[99] w-full sm:w-96 h-screen bg-white dark:bg-gray-900 border-l border-gray-200 dark:border-gray-700 shadow-xl overflow-hidden flex flex-col"
                        style="max-width: 400px;">
                        <!-- Header -->
                        <div
                            class="flex justify-between items-center px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-blue-50 to-purple-50 dark:from-gray-800 dark:to-gray-800">
                            <div>
                                <h3 class="text-lg font-bold text-gray-900 dark:text-white">Notifications</h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400">{{ $totalNotifications }} new
                                    updates</p>
                            </div>
                            <button @click="showNotifications = false"
                                class="p-2 text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-xl transition-all duration-200">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>

                        <!-- Notification List -->
                        <div
                            class="p-4 overflow-y-auto h-[calc(100%-120px)] scrollbar-thin scrollbar-thumb-gray-300 dark:scrollbar-thumb-gray-600 scrollbar-track-transparent">
                            @php
                                $hasNotifications = false;
                            @endphp

                            @if ($draftCount > 0)
                                @php $hasNotifications = true; @endphp
                                <div class="mb-3 p-4 bg-gradient-to-r from-yellow-50 to-orange-50 dark:from-yellow-900/20 dark:to-orange-900/20 border border-yellow-200 dark:border-yellow-700 rounded-xl hover:shadow-md cursor-pointer transition-all duration-200 group"
                                    onclick="window.location.href='{{ route('emails.drafts') }}'">
                                    <div class="flex items-start justify-between">
                                        <div class="flex items-start space-x-3">
                                            <div
                                                class="flex items-center justify-center w-10 h-10 bg-yellow-500 rounded-full group-hover:scale-110 transition-transform duration-200">
                                                <i class="fas fa-envelope-open text-white text-sm"></i>
                                            </div>
                                            <div>
                                                <h4 class="text-sm font-semibold text-gray-900 dark:text-white">Email
                                                    Drafts</h4>
                                                <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">You have
                                                    {{ $draftCount }} unsent draft{{ $draftCount > 1 ? 's' : '' }}</p>
                                                <p class="text-xs text-yellow-600 dark:text-yellow-400 mt-1">Click to
                                                    review and send</p>
                                            </div>
                                        </div>
                                        <span
                                            class="bg-yellow-500 text-white text-xs px-2 py-1 rounded-full font-bold">{{ $draftCount }}</span>
                                    </div>
                                </div>
                            @endif

                            @php
                                $recentInvoices = \App\Models\ims\Invoice::orderBy('created_at', 'desc')
                                    ->limit(3)
                                    ->get();
                                $recentQuotations = \App\Models\ims\Quotation::orderBy('created_at', 'desc')
                                    ->limit(3)
                                    ->get();
                                $recentCustomers = \App\Models\ims\Customer::orderBy('created_at', 'desc')
                                    ->limit(2)
                                    ->get();
                            @endphp

                            @foreach ($recentInvoices as $invoice)
                                @php $hasNotifications = true; @endphp
                                <div class="mb-3 p-4 bg-gradient-to-r from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20 border border-green-200 dark:border-green-700 rounded-xl hover:shadow-md cursor-pointer transition-all duration-200 group"
                                    onclick="window.location.href='{{ route('invoices.show', $invoice->id) }}'">
                                    <div class="flex items-start justify-between">
                                        <div class="flex items-start space-x-3">
                                            <div
                                                class="flex items-center justify-center w-10 h-10 bg-green-500 rounded-full group-hover:scale-110 transition-transform duration-200">
                                                <i class="fas fa-file-invoice text-white text-sm"></i>
                                            </div>
                                            <div>
                                                <h4 class="text-sm font-semibold text-gray-900 dark:text-white">New
                                                    Invoice Created</h4>
                                                <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">Invoice
                                                    #{{ $invoice->invoice_no }}</p>
                                                <p class="text-xs text-gray-500 dark:text-gray-500">
                                                    {{ $invoice->customer->company_name }}</p>
                                            </div>
                                        </div>
                                        <span
                                            class="text-xs text-gray-400 dark:text-gray-500">{{ $invoice->created_at->diffForHumans() }}</span>
                                    </div>
                                </div>
                            @endforeach

                            @foreach ($recentQuotations as $quotation)
                                @php $hasNotifications = true; @endphp
                                <div class="mb-3 p-4 bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 border border-blue-200 dark:border-blue-700 rounded-xl hover:shadow-md cursor-pointer transition-all duration-200 group"
                                    onclick="window.location.href='{{ route('quotations.show', $quotation->id) }}'">
                                    <div class="flex items-start justify-between">
                                        <div class="flex items-start space-x-3">
                                            <div
                                                class="flex items-center justify-center w-10 h-10 bg-blue-500 rounded-full group-hover:scale-110 transition-transform duration-200">
                                                <i class="fas fa-quote-left text-white text-sm"></i>
                                            </div>
                                            <div>
                                                <h4 class="text-sm font-semibold text-gray-900 dark:text-white">New
                                                    Quotation Created</h4>
                                                <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">Quote
                                                    #{{ $quotation->quotation_code }}</p>
                                                <p class="text-xs text-gray-500 dark:text-gray-500">
                                                    {{ $quotation->customer->company_name }}</p>
                                            </div>
                                        </div>
                                        <span
                                            class="text-xs text-gray-400 dark:text-gray-500">{{ $quotation->created_at->diffForHumans() }}</span>
                                    </div>
                                </div>
                            @endforeach

                            @foreach ($recentCustomers as $customer)
                                @php $hasNotifications = true; @endphp
                                <div class="mb-3 p-4 bg-gradient-to-r from-purple-50 to-pink-50 dark:from-purple-900/20 dark:to-pink-900/20 border border-purple-200 dark:border-purple-700 rounded-xl hover:shadow-md cursor-pointer transition-all duration-200 group"
                                    onclick="window.location.href='{{ route('customers.show', $customer->id) }}'">
                                    <div class="flex items-start justify-between">
                                        <div class="flex items-start space-x-3">
                                            <div
                                                class="flex items-center justify-center w-10 h-10 bg-purple-500 rounded-full group-hover:scale-110 transition-transform duration-200">
                                                <i class="fas fa-user-plus text-white text-sm"></i>
                                            </div>
                                            <div>
                                                <h4 class="text-sm font-semibold text-gray-900 dark:text-white">New
                                                    Customer Added</h4>
                                                <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">
                                                    {{ $customer->company_name }}</p>
                                                <p class="text-xs text-gray-500 dark:text-gray-500">
                                                    {{ $customer->contact_person }}</p>
                                            </div>
                                        </div>
                                        <span
                                            class="text-xs text-gray-400 dark:text-gray-500">{{ $customer->created_at->diffForHumans() }}</span>
                                    </div>
                                </div>
                            @endforeach

                            <!-- System Status -->
                            <div
                                class="mb-3 p-4 bg-gradient-to-r from-indigo-50 to-blue-50 dark:from-indigo-900/20 dark:to-blue-900/20 border border-indigo-200 dark:border-indigo-700 rounded-xl">
                                <div class="flex items-start justify-between">
                                    <div class="flex items-start space-x-3">
                                        <div
                                            class="flex items-center justify-center w-10 h-10 bg-indigo-500 rounded-full">
                                            <i class="fas fa-cog text-white text-sm"></i>
                                        </div>
                                        <div>
                                            <h4 class="text-sm font-semibold text-gray-900 dark:text-white">System
                                                Status</h4>
                                            <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">Email system with
                                                PDF attachments is active</p>
                                        </div>
                                    </div>
                                    <span
                                        class="bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200 text-xs px-2 py-1 rounded-full font-medium">Online</span>
                                </div>
                            </div>

                            @if (!$hasNotifications && $draftCount == 0)
                                <div class="text-center py-12">
                                    <div
                                        class="w-16 h-16 mx-auto mb-4 bg-gray-100 dark:bg-gray-800 rounded-full flex items-center justify-center">
                                        <i class="fas fa-bell-slash text-2xl text-gray-400 dark:text-gray-600"></i>
                                    </div>
                                    <h3 class="text-sm font-medium text-gray-900 dark:text-white mb-1">No new
                                        notifications</h3>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">You're all caught up!</p>
                                </div>
                            @endif
                        </div>

                        <!-- Footer Actions -->
                        <div
                            class="flex justify-between items-center px-6 py-4 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800">
                            <a href="{{ route('emails.drafts') }}"
                                class="text-sm text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 font-medium transition-colors">
                                View all drafts
                            </a>
                            <button
                                class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 font-medium transition-colors">
                                Mark all as read
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Enhanced User Dropdown -->
                <div class="relative" x-data="{ userOpen: false }">
                    <button @click="userOpen = !userOpen" type="button"
                        class="flex items-center space-x-3 px-3 py-2 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl shadow-sm hover:shadow-md focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition-all duration-200 group">

                        @if (Auth::user()->profile_photo)
                            <img class="w-8 h-8 rounded-lg object-cover"
                                src="{{ asset('storage/' . Auth::user()->profile_photo) }}" alt="User photo" />
                        @else
                            <div
                                class="flex items-center justify-center w-8 h-8 text-sm font-bold text-white uppercase bg-gradient-to-br from-blue-500 to-purple-500 rounded-lg shadow-sm">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>
                        @endif

                        <div class="hidden sm:block text-left">
                            <p class="text-sm font-semibold text-gray-900 dark:text-white truncate max-w-32">
                                {{ Auth::user()->name }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 capitalize">
                                {{ Auth::user()->role ?? 'User' }}</p>
                        </div>

                        <svg class="w-4 h-4 text-gray-400 dark:text-gray-500 transition-transform duration-200 group-hover:text-gray-600 dark:group-hover:text-gray-300"
                            :class="userOpen ? 'rotate-180' : 'rotate-0'" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                            </path>
                        </svg>
                    </button>

                    <div x-show="userOpen" @click.away="userOpen = false"
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-150"
                        x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                        class="absolute right-0 mt-2 w-64 bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 py-2 z-50">

                        <!-- Profile Header -->
                        <div
                            class="px-4 py-3 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-blue-50 to-purple-50 dark:from-gray-800 dark:to-gray-800 rounded-t-xl">
                            <div class="flex items-center space-x-3">
                                @if (Auth::user()->profile_photo)
                                    <img class="w-12 h-12 rounded-xl object-cover"
                                        src="{{ asset('storage/' . Auth::user()->profile_photo) }}"
                                        alt="User photo" />
                                @else
                                    <div
                                        class="flex items-center justify-center w-12 h-12 text-lg font-bold text-white uppercase bg-gradient-to-br from-blue-500 to-purple-500 rounded-xl shadow-sm">
                                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                    </div>
                                @endif
                                <div>
                                    <div class="font-semibold text-gray-900 dark:text-white">{{ Auth::user()->name }}
                                    </div>
                                    <div class="text-sm text-gray-600 dark:text-gray-400">{{ Auth::user()->email }}
                                    </div>
                                    <div class="text-xs text-blue-600 dark:text-blue-400 capitalize font-medium">
                                        {{ Auth::user()->role ?? 'User' }}</div>
                                </div>
                            </div>
                        </div>

                        <!-- Menu Options -->
                        <div class="py-2">
                            <a href="{{ route('profile.show') }}"
                                class="flex items-center px-4 py-3 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors group">
                                <div
                                    class="flex items-center justify-center w-8 h-8 bg-blue-100 dark:bg-blue-900 text-blue-600 dark:text-blue-400 rounded-lg mr-3 group-hover:scale-110 transition-transform">
                                    <i class="fa-solid fa-user text-sm"></i>
                                </div>
                                <div>
                                    <div class="font-medium">Profile</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">View and edit profile</div>
                                </div>
                            </a>

                            <a href="{{ route('account.settings') }}"
                                class="flex items-center px-4 py-3 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors group">
                                <div
                                    class="flex items-center justify-center w-8 h-8 bg-purple-100 dark:bg-purple-900 text-purple-600 dark:text-purple-400 rounded-lg mr-3 group-hover:scale-110 transition-transform">
                                    <i class="fa-solid fa-gear text-sm"></i>
                                </div>
                                <div>
                                    <div class="font-medium">Account Settings</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">Manage your account</div>
                                </div>
                            </a>

                            <!-- Dark Mode Toggle in Dropdown -->
                            <div
                                class="flex items-center px-4 py-3 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                <div
                                    class="flex items-center justify-center w-8 h-8 bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400 rounded-lg mr-3">
                                    <i class="fa-solid fa-moon text-sm" x-show="!darkMode"></i>
                                    <i class="fa-solid fa-sun text-sm" x-show="darkMode"></i>
                                </div>
                                <div class="flex-1">
                                    <div class="font-medium">Dark Mode</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">Toggle appearance</div>
                                </div>
                                <button @click="darkMode = !darkMode"
                                    class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors"
                                    :class="darkMode ? 'bg-blue-600' : 'bg-gray-200'">
                                    <span
                                        class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform"
                                        :class="darkMode ? 'translate-x-6' : 'translate-x-1'"></span>
                                </button>
                            </div>
                        </div>

                        <!-- Divider -->
                        <div class="border-t border-gray-200 dark:border-gray-700 my-2"></div>

                        <!-- Sign Out -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                class="flex items-center w-full px-4 py-3 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors group">
                                <div
                                    class="flex items-center justify-center w-8 h-8 bg-red-100 dark:bg-red-900 text-red-600 dark:text-red-400 rounded-lg mr-3 group-hover:scale-110 transition-transform">
                                    <i class="fa-solid fa-arrow-right-from-bracket text-sm"></i>
                                </div>
                                <div>
                                    <div class="font-medium">Sign out</div>
                                    <div class="text-xs text-red-500 dark:text-red-400">Logout from account</div>
                                </div>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </nav>
    <!-- Enhanced Responsive Sidebar -->
    <aside id="logo-sidebar" :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
        class="fixed top-0 left-0 z-40 w-64 h-screen pt-16 transition-transform bg-white dark:bg-gray-900 border-r border-gray-200 dark:border-gray-700 shadow-xl lg:translate-x-0">

        <!-- Sidebar Header -->
        <div class="px-4 py-4 border-b border-gray-200 dark:border-gray-700 lg:hidden">
            <div class="flex items-center space-x-3">
                <div
                    class="flex items-center justify-center w-10 h-10 bg-gradient-to-br from-blue-600 to-purple-600 rounded-xl">
                    <x-application-logo class="w-6 h-6 text-white" />
                </div>
                <div>
                    <h2 class="text-lg font-bold text-gray-900 dark:text-white">SKM&Co.</h2>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Inventory Management</p>
                </div>
            </div>
        </div>

        <div
            class="h-full px-3 py-4 overflow-y-auto scrollbar-thin scrollbar-thumb-gray-300 dark:scrollbar-thumb-gray-600 scrollbar-track-transparent">

            <!-- Quick Stats Card (Mobile) -->
            <div
                class="mb-6 p-4 bg-gradient-to-r from-blue-50 to-purple-50 dark:from-gray-800 dark:to-gray-800 rounded-xl border border-blue-200 dark:border-gray-700 lg:hidden">
                <h3 class="text-sm font-semibold text-gray-900 dark:text-white mb-2">Quick Overview</h3>
                <div class="grid grid-cols-2 gap-2 text-xs">
                    @php
                        $quickStats = [
                            'invoices' => \App\Models\ims\Invoice::count(),
                            'customers' => \App\Models\ims\Customer::count(),
                        ];
                    @endphp
                    <div class="text-center p-2 bg-white dark:bg-gray-700 rounded-lg">
                        <div class="font-bold text-blue-600 dark:text-blue-400">{{ $quickStats['invoices'] }}</div>
                        <div class="text-gray-600 dark:text-gray-400">Invoices</div>
                    </div>
                    <div class="text-center p-2 bg-white dark:bg-gray-700 rounded-lg">
                        <div class="font-bold text-purple-600 dark:text-purple-400">{{ $quickStats['customers'] }}
                        </div>
                        <div class="text-gray-600 dark:text-gray-400">Customers</div>
                    </div>
                </div>
            </div>

            <nav class="space-y-1">
                <!-- Main Navigation Items -->
                <div class="mb-6">
                    <h3
                        class="px-3 mb-2 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                        Main</h3>

                    <!-- Dashboard Link -->
                    <a href="{{ route('dashboard') }}"
                        class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 {{ request()->routeIs('dashboard') ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-700 dark:text-blue-300 border border-blue-200 dark:border-blue-800' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-white' }}">
                        <div
                            class="flex items-center justify-center w-8 h-8 mr-3 rounded-lg {{ request()->routeIs('dashboard') ? 'bg-blue-100 dark:bg-blue-800 text-blue-600 dark:text-blue-300' : 'bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400 group-hover:bg-gray-200 dark:group-hover:bg-gray-600' }} transition-colors">
                            <i class="fas fa-chart-pie text-sm"></i>
                        </div>
                        <span>Dashboard</span>
                        @if (request()->routeIs('dashboard'))
                            <div class="ml-auto w-2 h-2 bg-blue-600 dark:bg-blue-400 rounded-full"></div>
                        @endif
                    </a>

                    <!-- Users Link -->
                    <a href="{{ route('users.index') }}"
                        class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 {{ request()->routeIs('users.*') ? 'bg-purple-50 dark:bg-purple-900/20 text-purple-700 dark:text-purple-300 border border-purple-200 dark:border-purple-800' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-white' }}">
                        <div
                            class="flex items-center justify-center w-8 h-8 mr-3 rounded-lg {{ request()->routeIs('users.*') ? 'bg-purple-100 dark:bg-purple-800 text-purple-600 dark:text-purple-300' : 'bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400 group-hover:bg-gray-200 dark:group-hover:bg-gray-600' }} transition-colors">
                            <i class="fas fa-users text-sm"></i>
                        </div>
                        <span>Users</span>
                        @if (request()->routeIs('users.*'))
                            <div class="ml-auto w-2 h-2 bg-purple-600 dark:bg-purple-400 rounded-full"></div>
                        @endif
                    </a>
                </div>

                <!-- Business Section -->
                <div class="mb-6">
                    <h3
                        class="px-3 mb-2 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                        Business</h3>

                    <!-- Customers Link -->
                    <a href="{{ route('customers.index') }}"
                        class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 {{ request()->routeIs('customers.*') ? 'bg-green-50 dark:bg-green-900/20 text-green-700 dark:text-green-300 border border-green-200 dark:border-green-800' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-white' }}">
                        <div
                            class="flex items-center justify-center w-8 h-8 mr-3 rounded-lg {{ request()->routeIs('customers.*') ? 'bg-green-100 dark:bg-green-800 text-green-600 dark:text-green-300' : 'bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400 group-hover:bg-gray-200 dark:group-hover:bg-gray-600' }} transition-colors">
                            <i class="fas fa-user-tie text-sm"></i>
                        </div>
                        <span>Customers</span>
                        @if (request()->routeIs('customers.*'))
                            <div class="ml-auto w-2 h-2 bg-green-600 dark:bg-green-400 rounded-full"></div>
                        @endif
                    </a>

                    <!-- Suppliers Link -->
                    <a href="{{ route('suppliers.index') }}"
                        class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 {{ request()->routeIs('suppliers.*') ? 'bg-orange-50 dark:bg-orange-900/20 text-orange-700 dark:text-orange-300 border border-orange-200 dark:border-orange-800' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-white' }}">
                        <div
                            class="flex items-center justify-center w-8 h-8 mr-3 rounded-lg {{ request()->routeIs('suppliers.*') ? 'bg-orange-100 dark:bg-orange-800 text-orange-600 dark:text-orange-300' : 'bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400 group-hover:bg-gray-200 dark:group-hover:bg-gray-600' }} transition-colors">
                            <i class="fas fa-truck text-sm"></i>
                        </div>
                        <span>Suppliers</span>
                        @if (request()->routeIs('suppliers.*'))
                            <div class="ml-auto w-2 h-2 bg-orange-600 dark:bg-orange-400 rounded-full"></div>
                        @endif
                    </a>
                </div>

                <!-- Inventory Section -->
                <div class="mb-6">
                    <h3
                        class="px-3 mb-2 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                        Inventory</h3>

                    <!-- Products Link -->
                    <a href="{{ route('products.index') }}"
                        class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 {{ request()->routeIs('products.*') ? 'bg-indigo-50 dark:bg-indigo-900/20 text-indigo-700 dark:text-indigo-300 border border-indigo-200 dark:border-indigo-800' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-white' }}">
                        <div
                            class="flex items-center justify-center w-8 h-8 mr-3 rounded-lg {{ request()->routeIs('products.*') ? 'bg-indigo-100 dark:bg-indigo-800 text-indigo-600 dark:text-indigo-300' : 'bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400 group-hover:bg-gray-200 dark:group-hover:bg-gray-600' }} transition-colors">
                            <i class="fas fa-box text-sm"></i>
                        </div>
                        <span>Products</span>
                        @if (request()->routeIs('products.*'))
                            <div class="ml-auto w-2 h-2 bg-indigo-600 dark:bg-indigo-400 rounded-full"></div>
                        @endif
                    </a>

                    <!-- Services Link -->
                    <a href="{{ route('services.index') }}"
                        class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 {{ request()->routeIs('services.*') ? 'bg-pink-50 dark:bg-pink-900/20 text-pink-700 dark:text-pink-300 border border-pink-200 dark:border-pink-800' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-white' }}">
                        <div
                            class="flex items-center justify-center w-8 h-8 mr-3 rounded-lg {{ request()->routeIs('services.*') ? 'bg-pink-100 dark:bg-pink-800 text-pink-600 dark:text-pink-300' : 'bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400 group-hover:bg-gray-200 dark:group-hover:bg-gray-600' }} transition-colors">
                            <i class="fas fa-concierge-bell text-sm"></i>
                        </div>
                        <span>Services</span>
                        @if (request()->routeIs('services.*'))
                            <div class="ml-auto w-2 h-2 bg-pink-600 dark:bg-pink-400 rounded-full"></div>
                        @endif
                    </a>

                    <!-- Purchases Link -->
                    <a href="{{ route('purchases.index') }}"
                        class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 {{ request()->routeIs('purchases.*') ? 'bg-cyan-50 dark:bg-cyan-900/20 text-cyan-700 dark:text-cyan-300 border border-cyan-200 dark:border-cyan-800' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-white' }}">
                        <div
                            class="flex items-center justify-center w-8 h-8 mr-3 rounded-lg {{ request()->routeIs('purchases.*') ? 'bg-cyan-100 dark:bg-cyan-800 text-cyan-600 dark:text-cyan-300' : 'bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400 group-hover:bg-gray-200 dark:group-hover:bg-gray-600' }} transition-colors">
                            <i class="fas fa-shopping-cart text-sm"></i>
                        </div>
                        <span>Purchases</span>
                        @if (request()->routeIs('purchases.*'))
                            <div class="ml-auto w-2 h-2 bg-cyan-600 dark:bg-cyan-400 rounded-full"></div>
                        @endif
                    </a>

                    <!-- Stocks Link -->
                    <a href="{{ route('stocks.index') }}"
                        class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 {{ request()->routeIs('stocks.*') ? 'bg-teal-50 dark:bg-teal-900/20 text-teal-700 dark:text-teal-300 border border-teal-200 dark:border-teal-800' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-white' }}">
                        <div
                            class="flex items-center justify-center w-8 h-8 mr-3 rounded-lg {{ request()->routeIs('stocks.*') ? 'bg-teal-100 dark:bg-teal-800 text-teal-600 dark:text-teal-300' : 'bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400 group-hover:bg-gray-200 dark:group-hover:bg-gray-600' }} transition-colors">
                            <i class="fas fa-warehouse text-sm"></i>
                        </div>
                        <span>Stocks</span>
                        @if (request()->routeIs('stocks.*'))
                            <div class="ml-auto w-2 h-2 bg-teal-600 dark:bg-teal-400 rounded-full"></div>
                        @endif
                    </a>
                </div>

                <!-- Sales Section -->
                <div class="mb-6">
                    <h3
                        class="px-3 mb-2 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                        Sales</h3>

                    <!-- Quotations Link -->
                    <a href="{{ route('quotations.index') }}"
                        class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 {{ request()->routeIs('quotations.*') ? 'bg-amber-50 dark:bg-amber-900/20 text-amber-700 dark:text-amber-300 border border-amber-200 dark:border-amber-800' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-white' }}">
                        <div
                            class="flex items-center justify-center w-8 h-8 mr-3 rounded-lg {{ request()->routeIs('quotations.*') ? 'bg-amber-100 dark:bg-amber-800 text-amber-600 dark:text-amber-300' : 'bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400 group-hover:bg-gray-200 dark:group-hover:bg-gray-600' }} transition-colors">
                            <i class="fas fa-quote-right text-sm"></i>
                        </div>
                        <span>Quotations</span>
                        @if (request()->routeIs('quotations.*'))
                            <div class="ml-auto w-2 h-2 bg-amber-600 dark:bg-amber-400 rounded-full"></div>
                        @endif
                    </a>

                    <!-- Invoices Link -->
                    <a href="{{ route('invoices.index') }}"
                        class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 {{ request()->routeIs('invoices.*') ? 'bg-emerald-50 dark:bg-emerald-900/20 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-white' }}">
                        <div
                            class="flex items-center justify-center w-8 h-8 mr-3 rounded-lg {{ request()->routeIs('invoices.*') ? 'bg-emerald-100 dark:bg-emerald-800 text-emerald-600 dark:text-emerald-300' : 'bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400 group-hover:bg-gray-200 dark:group-hover:bg-gray-600' }} transition-colors">
                            <i class="fas fa-file-invoice-dollar text-sm"></i>
                        </div>
                        <span>Invoices</span>
                        @if (request()->routeIs('invoices.*'))
                            <div class="ml-auto w-2 h-2 bg-emerald-600 dark:bg-emerald-400 rounded-full"></div>
                        @endif
                    </a>

                    <!-- Delivery Challans Link -->
                    <a href="{{ route('delivery-challans.index') }}"
                        class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 {{ request()->routeIs('delivery-challans.*') ? 'bg-orange-50 dark:bg-orange-900/20 text-orange-700 dark:text-orange-300 border border-orange-200 dark:border-orange-800' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-white' }}">
                        <div
                            class="flex items-center justify-center w-8 h-8 mr-3 rounded-lg {{ request()->routeIs('delivery-challans.*') ? 'bg-orange-100 dark:bg-orange-800 text-orange-600 dark:text-orange-300' : 'bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400 group-hover:bg-gray-200 dark:group-hover:bg-gray-600' }} transition-colors">
                            <i class="fas fa-truck text-sm"></i>
                        </div>
                        <span>Delivery Challans</span>
                        @if (request()->routeIs('delivery-challans.*'))
                            <div class="ml-auto w-2 h-2 bg-orange-600 dark:bg-orange-400 rounded-full"></div>
                        @endif
                    </a>

                    <!-- Payments Link -->
                    <a href="{{ route('payments.index') }}"
                        class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 {{ request()->routeIs('payments.*') ? 'bg-violet-50 dark:bg-violet-900/20 text-violet-700 dark:text-violet-300 border border-violet-200 dark:border-violet-800' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-white' }}">
                        <div
                            class="flex items-center justify-center w-8 h-8 mr-3 rounded-lg {{ request()->routeIs('payments.*') ? 'bg-violet-100 dark:bg-violet-800 text-violet-600 dark:text-violet-300' : 'bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400 group-hover:bg-gray-200 dark:group-hover:bg-gray-600' }} transition-colors">
                            <i class="fas fa-credit-card text-sm"></i>
                        </div>
                        <span>Payments</span>
                        @if (request()->routeIs('payments.*'))
                            <div class="ml-auto w-2 h-2 bg-violet-600 dark:bg-violet-400 rounded-full"></div>
                        @endif
                    </a>
                </div>

                <!-- Communication Section -->
                <div class="mb-6">
                    <h3
                        class="px-3 mb-2 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                        Communication</h3>

                    <!-- Mails Link -->
                    <a href="{{ route('emails.index') }}"
                        class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 {{ request()->routeIs('emails.*') ? 'bg-red-50 dark:bg-red-900/20 text-red-700 dark:text-red-300 border border-red-200 dark:border-red-800' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-white' }}">
                        <div
                            class="flex items-center justify-center w-8 h-8 mr-3 rounded-lg {{ request()->routeIs('emails.*') ? 'bg-red-100 dark:bg-red-800 text-red-600 dark:text-red-300' : 'bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400 group-hover:bg-gray-200 dark:group-hover:bg-gray-600' }} transition-colors">
                            <i class="fas fa-envelope text-sm"></i>
                        </div>
                        <span>Mails</span>
                    </a>

                    <!-- AI Copilot Link -->
                    <a href="{{ route('ai.copilot') }}"
                        class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 {{ request()->routeIs('ai.copilot') ? 'bg-gray-50 dark:bg-gray-900/20 text-gray-700 dark:text-gray-300 border border-gray-200 dark:border-gray-800' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-white' }}">
                        <div
                            class="flex items-center justify-center w-8 h-8 mr-3 rounded-lg {{ request()->routeIs('ai.copilot') ? 'bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-300' : 'bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400 group-hover:bg-gray-200 dark:group-hover:bg-gray-600' }} transition-colors">
                            <i class="fas fa-microchip text-sm"></i>
                        </div>
                        <span>AI Copilot</span>
                        @if (request()->routeIs('ai.copilot'))
                            <div class="ml-auto w-2 h-2 bg-gray-600 dark:bg-gray-400 rounded-full"></div>
                        @endif
                    </a>
                </div>

                <!-- Reports Section -->
                <div class="mb-6">
                    <h3
                        class="px-3 mb-2 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                        Analytics</h3>

                    <!-- Reports Link -->
                    <a href="{{ route('reports.index') }}"
                        class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 {{ request()->routeIs('reports.*') ? 'bg-slate-50 dark:bg-slate-900/20 text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-800' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-white' }}">
                        <div
                            class="flex items-center justify-center w-8 h-8 mr-3 rounded-lg {{ request()->routeIs('reports.*') ? 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300' : 'bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400 group-hover:bg-gray-200 dark:group-hover:bg-gray-600' }} transition-colors">
                            <i class="fas fa-chart-bar text-sm"></i>
                        </div>
                        <span>Reports</span>
                        @if (request()->routeIs('reports.*'))
                            <div class="ml-auto w-2 h-2 bg-slate-600 dark:bg-slate-400 rounded-full"></div>
                        @endif
                    </a>

                    <!-- Activity Logs Link -->
                    <a href="{{ route('activity-logs.index') }}"
                        class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 {{ request()->routeIs('activity-logs.*') ? 'bg-stone-50 dark:bg-stone-900/20 text-stone-700 dark:text-stone-300 border border-stone-200 dark:border-stone-800' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-white' }}">
                        <div
                            class="flex items-center justify-center w-8 h-8 mr-3 rounded-lg {{ request()->routeIs('activity-logs.*') ? 'bg-stone-100 dark:bg-stone-800 text-stone-600 dark:text-stone-300' : 'bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400 group-hover:bg-gray-200 dark:group-hover:bg-gray-600' }} transition-colors">
                            <i class="fas fa-history text-sm"></i>
                        </div>
                        <span>Activity Logs</span>
                        @if (request()->routeIs('activity-logs.*'))
                            <div class="ml-auto w-2 h-2 bg-stone-600 dark:bg-stone-400 rounded-full"></div>
                        @endif
                    </a>
                </div>
            </nav>
        </div>
    </aside>

    <!-- Toast Container -->
    <div id="toast-container" class="fixed top-4 right-4 z-50 space-y-2"></div>
</div>

<style>
    .scrollbar-thin {
        scrollbar-width: thin;
        scrollbar-color: #6b7280 transparent;
    }

    .scrollbar-thin::-webkit-scrollbar {
        width: 6px;
    }

    .scrollbar-thin::-webkit-scrollbar-track {
        background: transparent;
    }

    .scrollbar-thin::-webkit-scrollbar-thumb {
        background-color: #6b7280;
        border-radius: 3px;
    }

    .dark .scrollbar-thin {
        scrollbar-color: #4b5563 transparent;
    }

    .dark .scrollbar-thin::-webkit-scrollbar-thumb {
        background-color: #4b5563;
    }

    .notification-enter {
        opacity: 0;
        transform: translateY(-10px);
    }

    .notification-enter-active {
        opacity: 1;
        transform: translateY(0);
        transition: opacity 300ms ease-out, transform 300ms ease-out;
    }

    .notification-exit {
        opacity: 1;
        transform: translateY(0);
    }

    .notification-exit-active {
        opacity: 0;
        transform: translateY(-10px);
        transition: opacity 300ms ease-in, transform 300ms ease-in;
    }

    @media (max-width: 1024px) {
        .lg\:ml-64 {
            margin-left: 0 !important;
        }
    }
</style>

<script>
    // Enhanced toast notification system
    window.showToast = function(message, type = 'success', duration = 5000) {
        const toastContainer = document.getElementById('toast-container');
        if (!toastContainer) return;

        const toastId = 'toast-' + Date.now();
        const icons = {
            success: 'fas fa-check-circle',
            error: 'fas fa-exclamation-circle',
            warning: 'fas fa-exclamation-triangle',
            info: 'fas fa-info-circle'
        };

        const colors = {
            success: 'bg-green-500 border-green-600',
            error: 'bg-red-500 border-red-600',
            warning: 'bg-yellow-500 border-yellow-600',
            info: 'bg-blue-500 border-blue-600'
        };

        const toast = document.createElement('div');
        toast.id = toastId;
        toast.className =
            `fixed top-4 right-4 z-50 flex items-center p-4 mb-4 text-white rounded-lg shadow-lg border-l-4 ${colors[type]} transform transition-all duration-300 ease-out opacity-0 translate-x-full`;
        toast.innerHTML = `
        <div class="flex items-center">
            <i class="${icons[type]} mr-3 text-lg"></i>
            <div class="font-medium">${message}</div>
            <button onclick="removeToast('${toastId}')" class="ml-4 text-white hover:text-gray-200 transition-colors">
                <i class="fas fa-times"></i>
            </button>
        </div>
    `;

        toastContainer.appendChild(toast);

        // Animate in
        setTimeout(() => {
            toast.classList.remove('opacity-0', 'translate-x-full');
            toast.classList.add('opacity-100', 'translate-x-0');
        }, 100);

        // Auto remove
        setTimeout(() => {
            removeToast(toastId);
        }, duration);
    };

    window.removeToast = function(toastId) {
        const toast = document.getElementById(toastId);
        if (toast) {
            toast.classList.add('opacity-0', 'translate-x-full');
            setTimeout(() => {
                toast.remove();
            }, 300);
        }
    };

    // Initialize dark mode
    document.addEventListener('DOMContentLoaded', function() {
        // Apply saved theme on page load
        const savedTheme = localStorage.getItem('theme');
        if (savedTheme === 'dark' || (!savedTheme && window.matchMedia('(prefers-color-scheme: dark)')
                .matches)) {
            document.documentElement.classList.add('dark');
        }

        // Listen for system theme changes
        window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', e => {
            if (!localStorage.getItem('theme')) {
                if (e.matches) {
                    document.documentElement.classList.add('dark');
                } else {
                    document.documentElement.classList.remove('dark');
                }
            }
        });
    });
</script>
