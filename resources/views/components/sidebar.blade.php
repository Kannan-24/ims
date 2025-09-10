<!-- Mobile Sidebar Backdrop -->
<div x-show="sidebarOpen" 
     x-transition:enter="transition-opacity ease-linear duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition-opacity ease-linear duration-300"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     class="fixed inset-0 z-30 bg-black bg-opacity-50 lg:hidden" 
     @click="sidebarOpen = false"></div>

<!-- Sidebar -->
<aside class="fixed top-0 left-0 z-40 h-screen bg-white border-r border-gray-200 transition-all duration-300 ease-in-out"
       :class="{
           'w-64': !sidebarCollapsed,
           'w-20': sidebarCollapsed,
           'translate-x-0': sidebarOpen,
           '-translate-x-full lg:translate-x-0': !sidebarOpen
       }"
       x-data="{ 
           selected: localStorage.getItem('selectedMenu') || 'Dashboard',
           hovering: false 
       }"
       x-init="$watch('selected', value => localStorage.setItem('selectedMenu', value))"
       @mouseenter="hovering = true"
       @mouseleave="hovering = false">

    <!-- Sidebar Header -->
    <div class="flex items-center justify-center h-16 border-b border-gray-200 bg-gray-50">
        <div class="flex items-center space-x-2" :class="sidebarCollapsed ? 'lg:justify-center' : ''">
            <div class="w-9 h-9  flex items-center justify-center">
                <img src="{{ asset('assets/logo.png') }}" alt="Logo" class="w-9 h-9 object-contain" />
            </div>
            <span class="text-xl font-semibold text-gray-900" 
                  :class="sidebarCollapsed ? 'lg:hidden' : 'block'">
                SKM & Co.
            </span>
        </div>
    </div>

    <!-- Sidebar Content -->
    <div class="flex flex-col h-full overflow-hidden">
        <!-- Menu Header -->
        <div class="px-4 py-3">
            <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider"
                :class="sidebarCollapsed ? 'lg:hidden' : 'block'">
                Menu
            </h3>
        </div>

        <!-- Navigation Menu -->
        <nav class="flex-1 px-3 space-y-1 overflow-y-auto scrollbar-hide">

            @php
                $menuItems = [
                    ['name' => 'Dashboard', 'route' => 'dashboard', 'icon' => 'fas fa-th-large'],
                    ['name' => 'Calendar', 'route' => 'calendar.index', 'icon' => 'fas fa-calendar-alt'],
                    ['name' => 'Hotkeys', 'route' => 'hotkeys.index', 'icon' => 'fas fa-keyboard'],
                    ['name' => 'Users', 'route' => 'users.index', 'icon' => 'fas fa-users'],
                    ['name' => 'Customers', 'route' => 'customers.index', 'icon' => 'fas fa-user-friends'],
                    ['name' => 'Suppliers', 'route' => 'suppliers.index', 'icon' => 'fas fa-truck'],
                    ['name' => 'Products', 'route' => 'products.index', 'icon' => 'fas fa-box'],
                    ['name' => 'Services', 'route' => 'services.index', 'icon' => 'fas fa-cogs'],
                    ['name' => 'Purchases', 'route' => 'purchases.index', 'icon' => 'fas fa-shopping-cart'],
                    ['name' => 'Stocks', 'route' => 'stocks.index', 'icon' => 'fas fa-warehouse'],
                    ['name' => 'Quotations', 'route' => 'quotations.index', 'icon' => 'fas fa-file-alt'],
                    ['name' => 'Invoices', 'route' => 'invoices.index', 'icon' => 'fas fa-receipt'],
                    ['name' => 'Delivery Challans', 'route' => 'delivery-challans.index', 'icon' => 'fas fa-shipping-fast'],
                    ['name' => 'Payments', 'route' => 'payments.index', 'icon' => 'fas fa-credit-card'],
                    ['name' => 'Emails', 'route' => 'emails.index', 'icon' => 'fas fa-envelope'],
                    ['name' => 'AI Assistant', 'route' => 'ai.copilot', 'icon' => 'fas fa-robot'],
                    ['name' => 'Reports', 'route' => 'reports.index', 'icon' => 'fas fa-chart-bar'],
                    ['name' => 'Activity Logs', 'route' => 'activity-logs.index', 'icon' => 'fas fa-history'],
                ];
            @endphp

            @foreach ($menuItems as $item)
                <a href="{{ route($item['route']) }}"
                   @click="selected = '{{ $item['name'] }}'"
                   class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-colors duration-300 group
                          {{ request()->routeIs($item['route'] . '*') 
                             ? 'bg-blue-600 text-white shadow-md' 
                             : 'text-gray-700 hover:bg-blue-50 hover:text-blue-600' }}">
                    <i class="{{ $item['icon'] }} w-5 h-5 
                              {{ request()->routeIs($item['route'] . '*') 
                                 ? 'text-white' 
                                 : 'text-gray-400 group-hover:text-blue-600' }}"></i>
                    <span class="ml-3" :class="sidebarCollapsed ? 'lg:hidden' : 'block'">
                        {{ $item['name'] }}
                    </span>
                </a>
            @endforeach
        </nav>
    </div>
</aside>

<!-- Hide Scrollbar Styling -->
<style>
    /* Hide scrollbar but keep scrolling */
    .scrollbar-hide::-webkit-scrollbar {
        display: none;
    }
    .scrollbar-hide {
        -ms-overflow-style: none;  /* IE and Edge */
        scrollbar-width: none;     /* Firefox */
    }
</style>
