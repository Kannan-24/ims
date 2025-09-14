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
           'w-64': !sidebarCollapsed || (sidebarCollapsed && sidebarHovering),
           'w-16': sidebarCollapsed && !sidebarHovering,
           'translate-x-0': sidebarOpen,
           '-translate-x-full lg:translate-x-0': !sidebarOpen
       }"
       x-data="sidebarManager()"
       x-init="init()"
       @mouseenter="sidebarHovering = true"
       @mouseleave="sidebarHovering = false"
       @toggle-mobile-sidebar.window="sidebarOpen = !sidebarOpen"
       @toggle-sidebar-collapse.window="sidebarCollapsed = !sidebarCollapsed">

    <!-- Sidebar Header -->
    <div class="flex items-center h-16 border-b border-gray-200 bg-gradient-to-r from-blue-600 to-blue-700"
         :class="(sidebarCollapsed && !sidebarHovering) ? 'justify-center px-2' : 'justify-start px-4'">
        <div class="flex items-center" 
             :class="(sidebarCollapsed && !sidebarHovering) ? 'space-x-0' : 'space-x-3'">
            <div class="w-10 h-10 flex items-center justify-center flex-shrink-0 bg-white rounded-lg shadow-sm">
                <img src="{{ asset('assets/logo.png') }}" alt="Logo" class="w-8 h-8 object-contain" />
            </div>
            <div class="transition-all duration-300" 
                 x-show="!sidebarCollapsed || (sidebarCollapsed && sidebarHovering)">
                <h1 class="text-xl font-bold text-white">SKM & Co.</h1>
                <p class="text-xs text-blue-100">Inventory Management</p>
            </div>
        </div>
    </div>

    <!-- Sidebar Content -->
    <div class="flex flex-col" style="height: calc(100vh - 4rem);">
               <!-- Navigation Menu -->
        <nav class="flex-1 px-3 py-4 space-y-2 overflow-y-auto scrollbar-hide">
            
            <!-- Dashboard - Single Item -->
            <a href="{{ route('dashboard') }}"
               @click="setActive('dashboard')"
               class="nav-item flex items-center rounded-lg transition-all duration-200
                      {{ request()->routeIs('dashboard') ? 'bg-blue-600 text-white shadow-md' : 'text-gray-700 hover:bg-gray-100' }}"
               :class="(sidebarCollapsed && !sidebarHovering) ? 'justify-center p-3' : 'px-3 py-2.5'">
                <i class="fas fa-th-large nav-icon {{ request()->routeIs('dashboard') ? 'text-white' : 'text-gray-500' }}"></i>
                <span class="nav-text" x-show="!sidebarCollapsed || (sidebarCollapsed && sidebarHovering)">Dashboard</span>
            </a>

            <!-- Tools & Utilities Group -->
            <div class="nav-group">
                <div class="nav-group-header" 
                     x-show="!sidebarCollapsed || (sidebarCollapsed && sidebarHovering)"
                     @click="toggleGroup('tools')"
                     :class="isGroupOpen('tools') ? 'text-blue-600' : 'text-gray-600'">
                    <div class="flex items-center justify-between cursor-pointer">
                        <div class="flex items-center space-x-2">
                            <i class="fas fa-tools text-sm"></i>
                            <span class="text-xs font-semibold uppercase tracking-wider">Tools & Utilities</span>
                        </div>
                        <i class="fas fa-chevron-down text-xs transition-transform duration-200"
                           :class="isGroupOpen('tools') ? 'rotate-180' : ''"></i>
                    </div>
                </div>
                
                <div class="nav-group-items" x-show="isGroupOpen('tools')" x-collapse>
                    <a href="{{ route('calendar.index') }}" class="nav-subitem {{ request()->routeIs('calendar.*') ? 'active' : '' }}">
                        <i class="fas fa-calendar-alt nav-icon"></i>
                        <span class="nav-text" x-show="!sidebarCollapsed || (sidebarCollapsed && sidebarHovering)">Calendar</span>
                    </a>
                    <a href="{{ route('chat.index') }}" class="nav-subitem {{ request()->routeIs('chat.*') ? 'active' : '' }}">
                        <i class="fas fa-comments nav-icon"></i>
                        <span class="nav-text" x-show="!sidebarCollapsed || (sidebarCollapsed && sidebarHovering)">Chat</span>
                    </a>
                    <a href="{{ route('hotkeys.index') }}" class="nav-subitem {{ request()->routeIs('hotkeys.*') ? 'active' : '' }}">
                        <i class="fas fa-keyboard nav-icon"></i>
                        <span class="nav-text" x-show="!sidebarCollapsed || (sidebarCollapsed && sidebarHovering)">Hotkeys</span>
                    </a>
                    <a href="{{ route('notes.index') }}" class="nav-subitem {{ request()->routeIs('notes.*') ? 'active' : '' }}">
                        <i class="fas fa-sticky-note nav-icon"></i>
                        <span class="nav-text" x-show="!sidebarCollapsed || (sidebarCollapsed && sidebarHovering)">Notes</span>
                    </a>
                    <a href="{{ route('contact-book.index') }}" class="nav-subitem {{ request()->routeIs('contact-book.*') ? 'active' : '' }}">
                        <i class="fas fa-address-book nav-icon"></i>
                        <span class="nav-text" x-show="!sidebarCollapsed || (sidebarCollapsed && sidebarHovering)">Contact Book</span>
                    </a>
                </div>
            </div>

            <!-- User Management Group -->
            <div class="nav-group">
                <div class="nav-group-header" 
                     x-show="!sidebarCollapsed || (sidebarCollapsed && sidebarHovering)"
                     @click="toggleGroup('users')"
                     :class="isGroupOpen('users') ? 'text-blue-600' : 'text-gray-600'">
                    <div class="flex items-center justify-between cursor-pointer">
                        <div class="flex items-center space-x-2">
                            <i class="fas fa-users text-sm"></i>
                            <span class="text-xs font-semibold uppercase tracking-wider">User Management</span>
                        </div>
                        <i class="fas fa-chevron-down text-xs transition-transform duration-200"
                           :class="isGroupOpen('users') ? 'rotate-180' : ''"></i>
                    </div>
                </div>
                
                <div class="nav-group-items" x-show="isGroupOpen('users')" x-collapse>
                    <a href="{{ route('users.index') }}" class="nav-subitem {{ request()->routeIs('users.*') ? 'active' : '' }}">
                        <i class="fas fa-user-cog nav-icon"></i>
                        <span class="nav-text" x-show="!sidebarCollapsed || (sidebarCollapsed && sidebarHovering)">Users</span>
                    </a>
                    <a href="{{ route('customers.index') }}" class="nav-subitem {{ request()->routeIs('customers.*') ? 'active' : '' }}">
                        <i class="fas fa-user-friends nav-icon"></i>
                        <span class="nav-text" x-show="!sidebarCollapsed || (sidebarCollapsed && sidebarHovering)">Customers</span>
                    </a>
                    <a href="{{ route('suppliers.index') }}" class="nav-subitem {{ request()->routeIs('suppliers.*') ? 'active' : '' }}">
                        <i class="fas fa-truck nav-icon"></i>
                        <span class="nav-text" x-show="!sidebarCollapsed || (sidebarCollapsed && sidebarHovering)">Suppliers</span>
                    </a>
                </div>
            </div>

            <!-- Inventory Management Group -->
            <div class="nav-group">
                <div class="nav-group-header" 
                     x-show="!sidebarCollapsed || (sidebarCollapsed && sidebarHovering)"
                     @click="toggleGroup('inventory')"
                     :class="isGroupOpen('inventory') ? 'text-blue-600' : 'text-gray-600'">
                    <div class="flex items-center justify-between cursor-pointer">
                        <div class="flex items-center space-x-2">
                            <i class="fas fa-warehouse text-sm"></i>
                            <span class="text-xs font-semibold uppercase tracking-wider">Inventory</span>
                        </div>
                        <i class="fas fa-chevron-down text-xs transition-transform duration-200"
                           :class="isGroupOpen('inventory') ? 'rotate-180' : ''"></i>
                    </div>
                </div>
                
                <div class="nav-group-items" x-show="isGroupOpen('inventory')" x-collapse>
                    <a href="{{ route('products.index') }}" class="nav-subitem {{ request()->routeIs('products.*') ? 'active' : '' }}">
                        <i class="fas fa-box nav-icon"></i>
                        <span class="nav-text" x-show="!sidebarCollapsed || (sidebarCollapsed && sidebarHovering)">Products</span>
                    </a>
                    <a href="{{ route('services.index') }}" class="nav-subitem {{ request()->routeIs('services.*') ? 'active' : '' }}">
                        <i class="fas fa-cogs nav-icon"></i>
                        <span class="nav-text" x-show="!sidebarCollapsed || (sidebarCollapsed && sidebarHovering)">Services</span>
                    </a>
                    <a href="{{ route('stocks.index') }}" class="nav-subitem {{ request()->routeIs('stocks.*') ? 'active' : '' }}">
                        <i class="fas fa-layer-group nav-icon"></i>
                        <span class="nav-text" x-show="!sidebarCollapsed || (sidebarCollapsed && sidebarHovering)">Stock Management</span>
                    </a>
                    <a href="{{ route('purchases.index') }}" class="nav-subitem {{ request()->routeIs('purchases.*') ? 'active' : '' }}">
                        <i class="fas fa-shopping-cart nav-icon"></i>
                        <span class="nav-text" x-show="!sidebarCollapsed || (sidebarCollapsed && sidebarHovering)">Purchases</span>
                    </a>
                </div>
            </div>

            <!-- Sales & Orders Group -->
            <div class="nav-group">
                <div class="nav-group-header" 
                     x-show="!sidebarCollapsed || (sidebarCollapsed && sidebarHovering)"
                     @click="toggleGroup('sales')"
                     :class="isGroupOpen('sales') ? 'text-blue-600' : 'text-gray-600'">
                    <div class="flex items-center justify-between cursor-pointer">
                        <div class="flex items-center space-x-2">
                            <i class="fas fa-chart-line text-sm"></i>
                            <span class="text-xs font-semibold uppercase tracking-wider">Sales & Orders</span>
                        </div>
                        <i class="fas fa-chevron-down text-xs transition-transform duration-200"
                           :class="isGroupOpen('sales') ? 'rotate-180' : ''"></i>
                    </div>
                </div>
                
                <div class="nav-group-items" x-show="isGroupOpen('sales')" x-collapse>
                    <a href="{{ route('quotations.index') }}" class="nav-subitem {{ request()->routeIs('quotations.*') ? 'active' : '' }}">
                        <i class="fas fa-file-alt nav-icon"></i>
                        <span class="nav-text" x-show="!sidebarCollapsed || (sidebarCollapsed && sidebarHovering)">Quotations</span>
                    </a>
                    <a href="{{ route('invoices.index') }}" class="nav-subitem {{ request()->routeIs('invoices.*') ? 'active' : '' }}">
                        <i class="fas fa-receipt nav-icon"></i>
                        <span class="nav-text" x-show="!sidebarCollapsed || (sidebarCollapsed && sidebarHovering)">Invoices</span>
                    </a>
                    <a href="{{ route('delivery-challans.index') }}" class="nav-subitem {{ request()->routeIs('delivery-challans.*') ? 'active' : '' }}">
                        <i class="fas fa-shipping-fast nav-icon"></i>
                        <span class="nav-text" x-show="!sidebarCollapsed || (sidebarCollapsed && sidebarHovering)">Delivery Challans</span>
                    </a>
                    <a href="{{ route('payments.index') }}" class="nav-subitem {{ request()->routeIs('payments.*') ? 'active' : '' }}">
                        <i class="fas fa-credit-card nav-icon"></i>
                        <span class="nav-text" x-show="!sidebarCollapsed || (sidebarCollapsed && sidebarHovering)">Payments</span>
                    </a>
                </div>
            </div>

            <!-- Communication Group -->
            <div class="nav-group">
                <div class="nav-group-header" 
                     x-show="!sidebarCollapsed || (sidebarCollapsed && sidebarHovering)"
                     @click="toggleGroup('communication')"
                     :class="isGroupOpen('communication') ? 'text-blue-600' : 'text-gray-600'">
                    <div class="flex items-center justify-between cursor-pointer">
                        <div class="flex items-center space-x-2">
                            <i class="fas fa-comments text-sm"></i>
                            <span class="text-xs font-semibold uppercase tracking-wider">Communication</span>
                        </div>
                        <i class="fas fa-chevron-down text-xs transition-transform duration-200"
                           :class="isGroupOpen('communication') ? 'rotate-180' : ''"></i>
                    </div>
                </div>
                
                <div class="nav-group-items" x-show="isGroupOpen('communication')" x-collapse>
                    <a href="{{ route('emails.index') }}" class="nav-subitem {{ request()->routeIs('emails.*') ? 'active' : '' }}">
                        <i class="fas fa-envelope nav-icon"></i>
                        <span class="nav-text" x-show="!sidebarCollapsed || (sidebarCollapsed && sidebarHovering)">Emails</span>
                    </a>
                </div>
            </div>

            <!-- Analytics & Reports Group -->
            <div class="nav-group">
                <div class="nav-group-header" 
                     x-show="!sidebarCollapsed || (sidebarCollapsed && sidebarHovering)"
                     @click="toggleGroup('analytics')"
                     :class="isGroupOpen('analytics') ? 'text-blue-600' : 'text-gray-600'">
                    <div class="flex items-center justify-between cursor-pointer">
                        <div class="flex items-center space-x-2">
                            <i class="fas fa-chart-bar text-sm"></i>
                            <span class="text-xs font-semibold uppercase tracking-wider">Analytics</span>
                        </div>
                        <i class="fas fa-chevron-down text-xs transition-transform duration-200"
                           :class="isGroupOpen('analytics') ? 'rotate-180' : ''"></i>
                    </div>
                </div>
                
                <div class="nav-group-items" x-show="isGroupOpen('analytics')" x-collapse>
                    <a href="{{ route('reports.index') }}" class="nav-subitem {{ request()->routeIs('reports.*') ? 'active' : '' }}">
                        <i class="fas fa-chart-pie nav-icon"></i>
                        <span class="nav-text" x-show="!sidebarCollapsed || (sidebarCollapsed && sidebarHovering)">Reports</span>
                    </a>
                    <a href="{{ route('activity-logs.index') }}" class="nav-subitem {{ request()->routeIs('activity-logs.*') ? 'active' : '' }}">
                        <i class="fas fa-history nav-icon"></i>
                        <span class="nav-text" x-show="!sidebarCollapsed || (sidebarCollapsed && sidebarHovering)">Activity Logs</span>
                    </a>
                </div>
            </div>

        </nav>

        <!-- Sidebar Footer -->
        <div class="p-2 border-t border-gray-200 bg-red-500">
            <form method="POST" action="{{ route('logout') }}" class="w-full">
                @csrf
                <div class="flex items-center justify-between"
                     :class="(sidebarCollapsed && !sidebarHovering) ? 'justify-center' : ''">
                    <button type="submit"
                            @click="sidebarOpen = false"
                            class="flex items-center w-full rounded-lg p-2 text-gray-100 transition-colors"
                            title="Logout">
                        <i class="fas fa-sign-out-alt nav-icon text-gray-100"></i>
                        <span class="ml-2 text-sm" x-show="!sidebarCollapsed || (sidebarCollapsed && sidebarHovering)">Logout</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</aside>

<!-- JavaScript for Sidebar Management -->
<script>
function sidebarManager() {
    return {
        openGroups: JSON.parse(localStorage.getItem('sidebarOpenGroups')) || ['tools', 'users', 'inventory', 'sales'],
        activeItem: localStorage.getItem('sidebarActiveItem') || 'dashboard',

        init() {
            this.autoOpenCurrentGroup();
        },

        toggleGroup(groupName) {
            if (this.isGroupOpen(groupName)) {
                this.openGroups = this.openGroups.filter(group => group !== groupName);
            } else {
                this.openGroups.push(groupName);
            }
            localStorage.setItem('sidebarOpenGroups', JSON.stringify(this.openGroups));
        },

        isGroupOpen(groupName) {
            return this.openGroups.includes(groupName);
        },

        setActive(itemName) {
            this.activeItem = itemName;
            localStorage.setItem('sidebarActiveItem', itemName);
        },

        autoOpenCurrentGroup() {
            const currentRoute = window.location.pathname;
            
            if (currentRoute.includes('users') || currentRoute.includes('customers') || currentRoute.includes('suppliers')) {
                if (!this.isGroupOpen('users')) this.toggleGroup('users');
            } else if (currentRoute.includes('products') || currentRoute.includes('services') || currentRoute.includes('stocks') || currentRoute.includes('purchases')) {
                if (!this.isGroupOpen('inventory')) this.toggleGroup('inventory');
            } else if (currentRoute.includes('quotations') || currentRoute.includes('invoices') || currentRoute.includes('delivery-challans') || currentRoute.includes('payments')) {
                if (!this.isGroupOpen('sales')) this.toggleGroup('sales');
            } else if (currentRoute.includes('emails')) {
                if (!this.isGroupOpen('communication')) this.toggleGroup('communication');
            } else if (currentRoute.includes('reports') || currentRoute.includes('activity-logs')) {
                if (!this.isGroupOpen('analytics')) this.toggleGroup('analytics');
            } else if (currentRoute.includes('calendar') || currentRoute.includes('hotkeys') || currentRoute.includes('notes') || currentRoute.includes('contact-book')) {
                if (!this.isGroupOpen('tools')) this.toggleGroup('tools');
            }
        }
    }
}
</script>

<!-- Enhanced Sidebar Styling -->
<style>
    /* Hide scrollbar but keep scrolling functionality */
    .scrollbar-hide::-webkit-scrollbar {
        display: none;
    }
    .scrollbar-hide {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }
    
    /* Sidebar shadow */
    aside {
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    }
    
    /* Navigation item styles */
    .nav-item {
        font-weight: 500;
        font-size: 0.875rem;
        min-height: 2.75rem;
    }
    
    .nav-item:hover:not(.bg-blue-600) {
        background-color: #f3f4f6;
        transform: translateX(2px);
    }
    
    /* Navigation group styles */
    .nav-group {
        margin-bottom: 0.5rem;
    }
    
    .nav-group-header {
        padding: 0.5rem 0.75rem;
        margin-bottom: 0.25rem;
        border-radius: 0.5rem;
        transition: all 0.2s;
    }
    
    .nav-group-header:hover {
        background-color: #f9fafb;
    }
    
    .nav-group-items {
        margin-left: 0.5rem;
        border-left: 2px solid #e5e7eb;
        padding-left: 0.5rem;
    }
    
    .nav-subitem {
        display: flex;
        align-items: center;
        padding: 0.625rem 0.75rem;
        margin-bottom: 0.125rem;
        border-radius: 0.5rem;
        font-weight: 500;
        font-size: 0.875rem;
        color: #6b7280;
        text-decoration: none;
        transition: all 0.2s;
        position: relative;
    }
    
    .nav-subitem:hover {
        background-color: #f3f4f6;
        color: #374151;
        transform: translateX(2px);
    }
    
    .nav-subitem.active {
        background-color: #dbeafe;
        color: #1d4ed8;
        border-left: 3px solid #2563eb;
    }
    
    .nav-subitem.active::before {
        content: '';
        position: absolute;
        left: -0.5rem;
        top: 50%;
        transform: translateY(-50%);
        width: 4px;
        height: 1.5rem;
        background-color: #2563eb;
        border-radius: 0 2px 2px 0;
    }
    
    /* Icon styles */
    .nav-icon {
        width: 1.25rem;
        height: 1.25rem;
        text-align: center;
        flex-shrink: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
    }
    
    .nav-text {
        margin-left: 0.75rem;
        white-space: nowrap;
        transition: all 300ms cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    /* Group animation */
    .nav-group-items {
        overflow: hidden;
    }
    
    /* Smooth transitions */
    .transition-all {
        transition-property: all;
        transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
        transition-duration: 300ms;
    }
    
    /* Gradient header */
    .bg-gradient-to-r {
        background: linear-gradient(90deg, #2563eb 0%, #1d4ed8 100%);
    }
    
    /* Active state improvements */
    .nav-subitem.active .nav-icon {
        color: #1d4ed8 !important;
    }
    
    /* Responsive improvements */
    @media (max-width: 1024px) {
        aside {
            transform: translateX(-100%);
        }
        
        aside.translate-x-0 {
            transform: translateX(0);
        }
    }
    
    /* Animation for chevron rotation */
    .rotate-180 {
        transform: rotate(180deg);
    }
    
    /* Status indicator animation */
    .animate-pulse {
        animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
    }
    
    @keyframes pulse {
        0%, 100% {
            opacity: 1;
        }
        50% {
            opacity: .5;
        }
    }

    /* Mobile specific styles */
    @media (max-width: 640px) {
        .nav-text {
            font-size: 0.8rem;
        }
        
        .nav-icon {
            width: 1rem;
            height: 1rem;
            font-size: 0.875rem;
        }
        
        .nav-group-header span {
            font-size: 0.65rem;
        }
    }
</style>