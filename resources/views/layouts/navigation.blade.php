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
                                src="{{ asset('storage/' . Auth::user()->profile_photo) }}"
                                alt="User photo" />
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
                <div class="relative ">
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

                    <!-- Notification Dropdown -->
                    <div x-show="showNotifications" @click.away="showNotifications = false"
                        x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100"
                        class="absolute right-0 w-64 mt-5 bg-gray-800 border border-gray-700 shadow-lg">

                        <div class="flex justify-between px-4 py-3 text-white bg-gray-900 rounded-t-lg">
                            <h3 class="text-lg font-semibold">Notifications</h3>
                            <button @click="showNotifications = false" class="text-gray-400 hover:text-white">
                                ✖
                            </button>
                        </div>

                        <div class="p-2 overflow-y-auto max-h-60">
                            <template x-for="(notification, index) in notifications" :key="notification.id">
                                <div :class="{ 'bg-gray-700': !notification.read, 'bg-gray-800': notification.read }"
                                    class="flex items-center justify-between px-4 py-2 border-b border-gray-700">
                                    <span x-text="notification.message" class="text-sm text-gray-300"></span>
                                    <button @click="notifications[index].read = true"
                                        class="text-xs text-blue-400 hover:underline">
                                        <svg @click="notifications[index].read = true"
                                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor"
                                            class="w-5 h-5 text-green-500 transition-transform transform cursor-pointer hover:text-green-700 hover:scale-125">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 13l4 4L19 7" />
                                        </svg>
                                    </button>
                                </div>
                            </template>
                        </div>

                        <div class="flex justify-between px-4 py-2 bg-gray-900 rounded-b-lg">
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
                    class="flex items-center p-3 rounded-lg transition duration-300 ease-in-out 
                    {{ request()->routeIs('dashboard') ? 'bg-gradient-to-r from-blue-500 to-blue-700 text-white shadow-md' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                    <span class="ml-3">Dashboard</span>
                </a>
            </li>

            <!-- Users Link -->
            <li>
                <a href="{{ route('users.index') }}"
                    class="flex items-center p-3 rounded-lg transition duration-300 ease-in-out
                    {{ request()->routeIs('users.*') ? 'bg-gradient-to-r from-blue-500 to-blue-700 text-white shadow-md' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                    <span class="ml-3">Users</span>
                </a>

            <!-- Customers Link -->
            <li>
                <a href="{{ route('customers.index') }}"
                    class="flex items-center p-3 rounded-lg transition duration-300 ease-in-out
                    {{ request()->routeIs('customers.*') ? 'bg-gradient-to-r from-blue-500 to-blue-700 text-white shadow-md' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                    <span class="ml-3">Customers</span>
                </a>
            </li>

            <!-- Suppliers Link -->
            <li>
                <a href="{{ route('suppliers.index') }}"
                    class="flex items-center p-3 rounded-lg transition duration-300 ease-in-out
                    {{ request()->routeIs('suppliers.*') ? 'bg-gradient-to-r from-blue-500 to-blue-700 text-white shadow-md' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                    <span class="ml-3">Suppliers</span>
                </a>

            <!-- Products Link -->
            <li>
                <a href="{{ route('products.index') }}"
                    class="flex items-center p-3 rounded-lg transition duration-300 ease-in-out
                    {{ request()->routeIs('products.*') ? 'bg-gradient-to-r from-blue-500 to-blue-700 text-white shadow-md' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                    <span class="ml-3">Products</span>
                </a>
            </li>

            <!-- Invoices Link -->
            <li>
                <a href="{{ route('invoices.index') }}"
                    class="flex items-center p-3 rounded-lg transition duration-300 ease-in-out
                    {{ request()->routeIs('invoices.*') ? 'bg-gradient-to-r from-blue-500 to-blue-700 text-white shadow-md' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                    <span class="ml-3">Invoices</span>
                </a>
            </li>

            <!-- Quotations Link -->
            <li>
                <a href="{{ route('quotations.index') }}"
                    class="flex items-center p-3 rounded-lg transition duration-300 ease-in-out
                    {{ request()->routeIs('quotations.*') ? 'bg-gradient-to-r from-blue-500 to-blue-700 text-white shadow-md' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                    <span class="ml-3">Quotations</span>
                </a>
            </li>

            <!-- Gate Passes Link -->
            <li>
                <a href="{{ route('gate-passes.index') }}"
                    class="flex items-center p-3 rounded-lg transition duration-300 ease-in-out
                    {{ request()->routeIs('gate-passes.*') ? 'bg-gradient-to-r from-blue-500 to-blue-700 text-white shadow-md' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                    <span class="ml-3">Gate Passes</span>
                </a>
            </li>

            <!-- Delivery Challans Link -->
            <li>
                <a href="{{ route('delivery-challans.index') }}"
                    class="flex items-center p-3 rounded-lg transition duration-300 ease-in-out
                    {{ request()->routeIs('delivery-challans.*') ? 'bg-gradient-to-r from-blue-500 to-blue-700 text-white shadow-md' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                    <span class="ml-3">Delivery Challans</span>
                </a>
            </li>

            <!-- Payments Link -->
            <li>
                <a href="{{ route('payments.index') }}"
                    class="flex items-center p-3 rounded-lg transition duration-300 ease-in-out
                    {{ request()->routeIs('payments.*') ? 'bg-gradient-to-r from-blue-500 to-blue-700 text-white shadow-md' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                    <span class="ml-3">Payments</span>
                </a>
            </li>

            <!-- Stocks Link -->
            <li>
                <a href="{{ route('stocks.index') }}"
                    class="flex items-center p-3 rounded-lg transition duration-300 ease-in-out
                    {{ request()->routeIs('stocks.*') ? 'bg-gradient-to-r from-blue-500 to-blue-700 text-white shadow-md' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                    <span class="ml-3">Stocks</span>
                </a>
            </li>

            <!-- Batch Stocks Link -->
            <li>
                <a href="{{ route('batch-stocks.index') }}"
                    class="flex items-center p-3 rounded-lg transition duration-300 ease-in-out
                    {{ request()->routeIs('batch-stocks.*') ? 'bg-gradient-to-r from-blue-500 to-blue-700 text-white shadow-md' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                    <span class="ml-3">Batch Stocks</span>
                </a>
            </li>
        </ul>
    </div>
</aside>


</div>
