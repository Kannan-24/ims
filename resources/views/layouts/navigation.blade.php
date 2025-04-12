<div x-data="{
    sidebarOpen: false,
    userDropdown: false,
    showNotifications: false,
    notifications: [
        { id: 1, message: 'New invoice created!', read: false },
        { id: 2, message: 'Stock updated successfully.', read: false },
        { id: 3, message: 'New customer added.', read: false },
    ]
}">

    <!-- Top Navigation Bar -->
    <nav class="fixed top-0 z-50 w-full bg-gray-900 border-b border-gray-700 shadow-lg">
        <div class="flex items-center justify-between px-4 py-3 lg:px-6">

            <!-- Logo & Sidebar Toggle -->
            <div class="flex items-center">
                <button @click="sidebarOpen = !sidebarOpen" type="button"
                    class="p-2 text-gray-300 rounded-lg sm:hidden hover:bg-gray-700 hover:text-white focus:ring-2 focus:ring-gray-500">
                    <span class="sr-only">Open sidebar</span>
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                        <path clip-rule="evenodd" fill-rule="evenodd"
                            d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 10.5a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5a.75.75 0 01-.75-.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10z">
                        </path>
                    </svg>
                </button>
                <a href="{{ route('dashboard') }}" class="flex items-center ml-3 space-x-2 lg:ml-5">
                    <x-application-logo class="w-auto h-8" />
                    <span class="hidden ml-2 text-2xl font-semibold text-white sm:inline">SKM&Co.</span>
                </a>
            </div>

            <!-- Right: Notifications & User Profile -->
            <div class="flex items-center space-x-4">


                <!-- User Dropdown -->
                <div class="relative">
                    <button @click="userDropdown = !userDropdown" type="button"
                        class="flex items-center px-4 py-2 space-x-3 text-white bg-gray-800 rounded-full shadow-md focus:ring-4 focus:ring-gray-500">
                        @if (Auth::user()->profile_photo)
                            <img class="w-10 h-10 rounded-full"
                                src="{{ asset('storage/' . Auth::user()->profile_photo) }}" alt="User photo" />
                        @else
                            <div
                                class="flex items-center justify-center w-10 h-10 text-lg font-bold text-white uppercase bg-gray-600 rounded-full">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>
                        @endif
                        <div class="text-left">
                            <p class="text-sm font-medium">{{ Auth::user()->name }}</p>
                            <p class="text-xs text-gray-400">{{ Auth::user()->role }}</p>
                        </div>
                        <svg class="w-4 h-4 text-gray-400 transition-transform transform"
                            :class="userDropdown ? 'rotate-180' : 'rotate-0'" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                            </path>
                        </svg>
                    </button>

                    <div x-show="userDropdown" @click.away="userDropdown = false"
                        class="absolute right-0 z-50 w-48 mt-2 overflow-hidden bg-gray-800 rounded-lg shadow-lg">
                        <a href="{{ route('profile.show') }}"
                            class="block px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 hover:text-white">
                            Profile
                        </a>
                        <a href="{{ route('account.settings') }}"
                            class="block px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 hover:text-white">
                            Account Settings
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                class="block w-full px-4 py-2 text-sm text-left text-gray-300 hover:bg-gray-700 hover:text-white">
                                Log Out
                            </button>
                        </form>
                    </div>
                </div>
                <!-- Notification Bell -->
                <div class="relative">
                    <!-- Bell Icon -->
                    <button @click="showNotifications = !showNotifications"
                        class="relative p-2 text-gray-300 rounded-full hover:bg-gray-700 hover:text-white focus:outline-none">
                        <span class="sr-only">View notifications</span>
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14V9a6 6 0 00-12 0v5c0 .386-.146.774-.405 1.095L4 17h5m6 0a3 3 0 11-6 0">
                            </path>
                        </svg>
                        <span x-show="notifications.some(n => !n.read)"
                            class="absolute top-0 right-0 w-4 h-4 text-xs text-white bg-red-500 rounded-full">
                            <span x-text="notifications.filter(n => !n.read).length"></span>
                        </span>
                    </button>

                    <!-- Right-Side Notification Panel -->
                    <div x-show="showNotifications" @click.away="showNotifications = false"
                        x-transition:enter="transition ease-in-out duration-300 transform"
                        x-transition:enter-start="translate-x-full opacity-0"
                        x-transition:enter-end="translate-x-0 opacity-100"
                        x-transition:leave="transition ease-in-out duration-300 transform"
                        x-transition:leave-start="translate-x-0 opacity-100"
                        x-transition:leave-end="translate-x-full opacity-0"
                        class="fixed top-0 right-0 z-50 w-80 h-full bg-gray-900 border-l border-gray-800 shadow-xl">

                        <!-- Header -->
                        <div class="flex justify-between px-4 py-3 text-white bg-gray-900 border-b border-gray-700">
                            <h3 class="text-lg font-semibold">Notifications</h3>
                            <button @click="showNotifications = false"
                                class="text-gray-400 hover:text-white text-lg">✖</button>
                        </div>

                        <!-- Notification List -->
                        <div class="p-2 overflow-y-auto h-[calc(100%-100px)]">
                            <template x-for="(notification, index) in notifications" :key="notification.id">
                                <div :class="{ 'bg-gray-700': !notification.read, 'bg-gray-800': notification.read }"
                                    class="flex items-center justify-between px-4 py-3 border-b border-gray-700 transition-all hover:bg-gray-600 cursor-pointer"
                                    @click="notifications[index].read = true">
                                    <span x-text="notification.message" class="text-sm text-gray-300"></span>
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor"
                                        class="w-5 h-5 text-green-500 transition-transform transform hover:text-green-700 hover:scale-125">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                </div>
                            </template>
                        </div>

                        <!-- Footer Actions -->
                        <div class="flex justify-between px-4 py-3 bg-gray-900 border-t border-gray-700">
                            <button @click="notifications.forEach(n => n.read = true)"
                                class="text-sm text-gray-400 hover:text-white">
                                Mark all as read
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>
    <aside id="logo-sidebar" :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
        class="fixed top-0 left-0 z-40 w-64 h-screen pt-20 transition-transform bg-gray-900 border-r border-gray-800 shadow-xl sm:translate-x-0">
        <div class="h-full px-4 py-4 overflow-y-auto">
            <ul class="space-y-2 font-medium">
                <!-- Dashboard Link -->
                <li>
                    <a href="{{ route('dashboard') }}"
                        class="flex items-center p-2 rounded-lg hover:bg-gray-700 {{ request()->routeIs('dashboard') ? 'bg-gray-700' : '' }}">
                        <span class="ms-3">Dashboard</span>
                    </a>
                </li>

                <!-- Users Link -->
                <li>
                    <a href="{{ route('users.index') }}"
                        class="flex items-center p-2 rounded-lg hover:bg-gray-700 {{ request()->routeIs('users.*') ? 'bg-gray-700' : '' }}">
                        <span class="ms-3">Users</span>
                    </a>
                </li>

                <!-- Customers Link -->
                <li>
                    <a href="{{ route('customers.index') }}"
                        class="flex items-center p-2 rounded-lg hover:bg-gray-700 {{ request()->routeIs('customers.*') ? 'bg-gray-700' : '' }}">
                        <span class="ms-3">Customers</span>
                    </a>
                </li>

                <!-- Suppliers Link -->
                <li>
                    <a href="{{ route('suppliers.index') }}"
                        class="flex items-center p-2 rounded-lg hover:bg-gray-700 {{ request()->routeIs('suppliers.*') ? 'bg-gray-700' : '' }}">
                        <span class="ms-3">Suppliers</span>
                    </a>
                </li>

                <!-- Products Link -->
                <li>
                    <a href="{{ route('products.index') }}"
                        class="flex items-center p-2 rounded-lg hover:bg-gray-700 {{ request()->routeIs('products.*') ? 'bg-gray-700' : '' }}">
                        <span class="ms-3">Products</span>
                    </a>
                </li>

                <!-- Services Link -->
                <li>
                    <a href="{{ route('services.index') }}"
                        class="flex items-center p-2 rounded-lg hover:bg-gray-700 {{ request()->routeIs('services.*') ? 'bg-gray-700' : '' }}">
                        <span class="ms-3">Services</span>
                    </a>
                </li>

                <!-- Purchases Link -->
                <li>
                    <a href="{{ route('purchases.index') }}"
                        class="flex items-center p-2 rounded-lg hover:bg-gray-700 {{ request()->routeIs('purchases.*') ? 'bg-gray-700' : '' }}">
                        <span class="ms-3">Purchases</span>
                    </a>
                </li>

                <!-- Stocks Link -->
                <li>
                    <a href="{{ route('stocks.index') }}"
                        class="flex items-center p-2 rounded-lg hover:bg-gray-700 {{ request()->routeIs('stocks.*') ? 'bg-gray-700' : '' }}">
                        <span class="ms-3">Stocks</span>
                    </a>
                </li>

                <!-- Quotations Link -->
                <li>
                    <a href="{{ route('quotations.index') }}"
                        class="flex items-center p-2 rounded-lg hover:bg-gray-700 {{ request()->routeIs('quotations.*') ? 'bg-gray-700' : '' }}">
                        <span class="ms-3">Quotations</span>
                    </a>
                </li>

                <!-- Invoices Link -->
                <li>
                    <a href="{{ route('invoices.index') }}"
                        class="flex items-center p-2 rounded-lg hover:bg-gray-700 {{ request()->routeIs('invoices.*') ? 'bg-gray-700' : '' }}">
                        <span class="ms-3">Invoices</span>
                    </a>
                </li>

                <!-- Payments Link -->
                <li>
                    <a href="{{ route('payments.index') }}"
                        class="flex items-center p-2 rounded-lg hover:bg-gray-700 {{ request()->routeIs('payments.*') ? 'bg-gray-700' : '' }}">
                        <span class="ms-3">Payments</span>
                    </a>
                </li>
            </ul>
        </div>
    </aside>
</div>
