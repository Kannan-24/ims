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
           'w-56': !sidebarCollapsed || (sidebarCollapsed && sidebarHovering),
           'w-16': sidebarCollapsed && !sidebarHovering,
           'translate-x-0': sidebarOpen,
           '-translate-x-full lg:translate-x-0': !sidebarOpen
       }"
       x-data="{ 
           selected: localStorage.getItem('selectedMenu') || 'Dashboard'
       }"
       x-init="$watch('selected', value => localStorage.setItem('selectedMenu', value))"
       @mouseenter="sidebarHovering = true"
       @mouseleave="sidebarHovering = false">

    <!-- Sidebar Header -->
    <div class="flex items-center h-16 border-b border-gray-200 bg-gray-50"
         :class="(sidebarCollapsed && !sidebarHovering) ? 'justify-center px-2' : 'justify-start px-4'">
        <div class="flex items-center" 
             :class="(sidebarCollapsed && !sidebarHovering) ? 'space-x-0' : 'space-x-3'">
            <div class="w-9 h-9 flex items-center justify-center flex-shrink-0">
                <img src="{{ asset('assets/logo.png') }}" alt="Logo" class="w-9 h-9 object-contain" />
            </div>
            <span class="text-xl font-semibold text-gray-900 whitespace-nowrap transition-all duration-300" 
                  x-show="!sidebarCollapsed || (sidebarCollapsed && sidebarHovering)">
                SKM & Co.
            </span>
        </div>
    </div>

    <!-- Sidebar Content -->
    <div class="flex flex-col" style="height: calc(100vh - 4rem);">
        <!-- Menu Header -->


        <!-- Navigation Menu -->
        <nav class="flex-1 px-2 space-y-1 overflow-y-auto scrollbar-hide">

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
                    ['name' => 'Notes', 'route' => 'notes.index', 'icon' => 'fas fa-sticky-note'],
                    ['name' => 'Contact Book', 'route' => 'contact-book.index', 'icon' => 'fas fa-address-book'],
                    ['name' => 'Reports', 'route' => 'reports.index', 'icon' => 'fas fa-chart-bar'],
                    ['name' => 'Activity Logs', 'route' => 'activity-logs.index', 'icon' => 'fas fa-history'],
                ];
            @endphp

            @foreach ($menuItems as $item)
                <a href="{{ route($item['route']) }}"
                   @click="selected = '{{ $item['name'] }}'"
                   class="flex items-center text-sm font-medium rounded-lg transition-all duration-200 relative group
                          {{ request()->routeIs($item['route'] . '*') 
                             ? 'bg-blue-600 text-white shadow-md' 
                             : 'text-gray-700' }}"
                   :class="(sidebarCollapsed && !sidebarHovering) ? 'justify-center p-3 mx-1' : 'justify-start px-3 py-2.5'">
                    
                    <!-- Icon -->
                    <i class="{{ $item['icon'] }} flex-shrink-0 w-5 h-5 text-center
                              {{ request()->routeIs($item['route'] . '*') 
                                 ? 'text-white' 
                                 : 'text-gray-500' }}"></i>
                    
                    <!-- Text Label -->
                    <span class="whitespace-nowrap transition-all duration-300" 
                          :class="(sidebarCollapsed && !sidebarHovering) ? 'hidden' : 'ml-3 block'">
                        {{ $item['name'] }}
                    </span>
                </a>
            @endforeach
        </nav>
    </div>
</aside>

<!-- Sidebar Styling -->
<style>
    /* Hide scrollbar but keep scrolling functionality */
    .scrollbar-hide::-webkit-scrollbar {
        display: none;
    }
    .scrollbar-hide {
        -ms-overflow-style: none;  /* IE and Edge */
        scrollbar-width: none;     /* Firefox */
    }
    
    /* Ensure smooth transitions */
    aside {
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }
    
    /* Icon alignment and sizing */
    .flex-shrink-0 {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 1.25rem;
        height: 1.25rem;
        font-size: 1rem;
    }
    
    /* Prevent layout shifts */
    .transition-all {
        transition-property: all;
        transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
        transition-duration: 300ms;
    }
    
    /* Active state improvements */
    .bg-blue-600 {
        background-color: #2563eb !important;
    }
    
    /* Smooth hover expansion */
    aside:hover {
        transition-delay: 0ms;
    }
    
    /* Text transition smoothing */
    span.whitespace-nowrap {
        transition: all 300ms cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    /* Header text transition */
    .text-xl {
        transition: opacity 300ms ease-in-out;
    }
    
    /* Fix menu item spacing in collapsed mode */
    .group {
        min-height: 40px;
    }
</style>
