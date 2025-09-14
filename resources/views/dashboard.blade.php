<x-app-layout>
    <x-slot name="title">
        {{ __('Dashboard') }} - {{ config('app.name', 'IMS') }}
    </x-slot>

    <div class="bg-white" x-data="dashboard()" x-init="init()">
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
                </ol>
            </nav>
        </div>

        <!-- Header -->
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Welcome Back, {{ Auth::user()->name ?? 'User' }} 👋</h1>
                    <p class="text-sm text-gray-600 mt-1">Here's what's happening with your business today</p>
                </div>
                <div class="flex items-center space-x-3">
                    <!-- Quick Actions Dropdown -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open"
                            class="inline-flex items-center px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition-colors">
                            <i class="fas fa-bolt w-4 h-4 mr-2"></i>
                            Quick Actions
                            <i class="fas fa-chevron-down w-3 h-3 ml-2"></i>
                        </button>
                        <div x-show="open" @click.outside="open = false"
                            x-transition:enter="transition ease-out duration-100"
                            x-transition:enter-start="transform opacity-0 scale-95"
                            x-transition:enter-end="transform opacity-100 scale-100"
                            class="absolute right-0 mt-2 w-56 bg-white rounded-lg shadow-lg border border-gray-200 z-10"
                            style="display: none;">
                            <div class="p-2">
                                <a href="{{ route('customers.create') }}"
                                    class="flex items-center px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded">
                                    <i class="fas fa-user-plus w-4 h-4 mr-3"></i>
                                    Add Customer
                                </a>
                                <a href="{{ route('hotkeys.index') }}"
                                    class="flex items-center px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded">
                                    <i class="fas fa-keyboard w-4 h-4 mr-3"></i>
                                    Manage Hotkeys
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="bg-gray-50 min-h-screen">
            <div class="p-6">

                <!-- Stats Cards with Modern Design -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 xl:grid-cols-4 gap-6 mb-8">
                    <!-- Total Customers -->
                    <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl shadow-lg border border-blue-200 p-6 hover:shadow-xl transition-all duration-300 hover:scale-105">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <div class="flex items-center justify-between mb-2">
                                    <p class="text-sm font-semibold text-blue-700 uppercase tracking-wide">Total<br>Customers</p>
                                    <div class="p-2.5 bg-blue-500 bg-opacity-20 rounded-lg">
                                        <i class="fas fa-users text-blue-600 text-lg"></i>
                                    </div>
                                </div>
                                <p class="text-3xl font-bold text-gray-900 mb-1">{{ number_format($totalCustomers) }}</p>
                                <p class="text-xs text-green-600 font-medium">+{{ $customerGrowth }}% from last period</p>
                            </div>
                        </div>
                    </div>

                    <!-- Total Products -->
                    <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl shadow-lg border border-purple-200 p-6 hover:shadow-xl transition-all duration-300 hover:scale-105">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <div class="flex items-center justify-between mb-2">
                                    <p class="text-sm font-semibold text-purple-700 uppercase tracking-wide">Total<br>Products</p>
                                    <div class="p-2.5 bg-purple-500 bg-opacity-20 rounded-lg">
                                        <i class="fas fa-boxes text-purple-600 text-lg"></i>
                                    </div>
                                </div>
                                <p class="text-3xl font-bold text-gray-900 mb-1">{{ number_format($totalProducts) }}</p>
                                <p class="text-xs text-purple-600 font-medium">In catalog</p>
                            </div>
                        </div>
                    </div>

                    <!-- Monthly Revenue -->
                    <div class="bg-gradient-to-br from-indigo-50 to-indigo-100 rounded-xl shadow-lg border border-indigo-200 p-6 hover:shadow-xl transition-all duration-300 hover:scale-105">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <div class="flex items-center justify-between mb-2">
                                    <p class="text-sm font-semibold text-indigo-700 uppercase tracking-wide">Monthly<br>Revenue</p>
                                    <div class="p-2.5 bg-indigo-500 bg-opacity-20 rounded-lg">
                                        <i class="fas fa-chart-line text-indigo-600 text-lg"></i>
                                    </div>
                                </div>
                                <p class="text-3xl font-bold text-gray-900 mb-1">₹{{ number_format($monthlyRevenue, 0) }}</p>
                                <p class="text-xs {{ $revenueGrowth >= 0 ? 'text-green-600' : 'text-red-600' }} font-medium">
                                    {{ $revenueGrowth >= 0 ? '+' : '' }}{{ number_format($revenueGrowth, 1) }}% from last month
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Today's Revenue -->
                    <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-xl shadow-lg border border-green-200 p-6 hover:shadow-xl transition-all duration-300 hover:scale-105">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <div class="flex items-center justify-between mb-2">
                                    <p class="text-sm font-semibold text-green-700 uppercase tracking-wide">Today's<br>Revenue</p>
                                    <div class="p-2.5 bg-green-500 bg-opacity-20 rounded-lg">
                                        <i class="fas fa-dollar-sign text-green-600 text-lg"></i>
                                    </div>
                                </div>
                                <p class="text-3xl font-bold text-gray-900 mb-1">₹{{ number_format($todaysRevenue, 0) }}</p>
                                <p class="text-xs text-green-600 font-medium">{{ date('M d, Y') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Charts Section -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                    <!-- Sales Trend Chart -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-lg font-semibold text-gray-900">Sales Trend</h3>
                            <div class="flex items-center space-x-2">
                                <div class="w-3 h-3 bg-blue-500 rounded-full"></div>
                                <span class="text-sm text-gray-600">Last 6 Months</span>
                            </div>
                        </div>
                        <div class="relative h-64">
                            <canvas id="salesChart" class="w-full h-full"></canvas>
                        </div>
                    </div>

                    <!-- Customer Growth Chart -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-lg font-semibold text-gray-900">Customer Growth</h3>
                            <div class="flex items-center space-x-2">
                                <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                                <span class="text-sm text-gray-600">New Customers</span>
                            </div>
                        </div>
                        <div class="relative h-64">
                            <canvas id="customerGrowthChart" class="w-full h-full"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Additional Charts Section -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                    <!-- Top Products Chart -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-lg font-semibold text-gray-900">Top Products by Sales</h3>
                            <div class="flex items-center space-x-2">
                                <div class="w-3 h-3 bg-purple-500 rounded-full"></div>
                                <span class="text-sm text-gray-600">Last 30 Days</span>
                            </div>
                        </div>
                        <div class="relative h-64">
                            <canvas id="topProductsChart" class="w-full h-full"></canvas>
                        </div>
                    </div>

                    <!-- Order Status Chart -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-lg font-semibold text-gray-900">Order Status Breakdown</h3>
                            <div class="text-sm text-gray-600">Current Status</div>
                        </div>
                        <div class="relative h-64">
                            <canvas id="orderStatusChart" class="w-full h-full"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Recent Activity & Quick Actions -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                    <!-- Recent Activity -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-lg font-semibold text-gray-900">Recent Activity</h3>
                            <a href="{{ route('activity-logs.index') }}"
                                class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                View All →
                            </a>
                        </div>

                        <div class="space-y-4">
                            @forelse($recentActivities as $activity)
                                <div class="flex items-center space-x-4 p-3 hover:bg-gray-50 rounded-lg transition-colors">
                                    @php
                                        $color = $activity['color'] ?? 'gray';
                                        $bgClass = "bg-{$color}-100";
                                        $textClass = "text-{$color}-600";
                                        $icon = $activity['icon'] ?? 'fas fa-circle';
                                    @endphp
                                    <div class="w-10 h-10 {{ $bgClass }} rounded-full flex items-center justify-center">
                                        <i class="{{ $icon }} {{ $textClass }}"></i>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-sm text-gray-900">{{ $activity['text'] ?? 'No description' }}</p>
                                        <p class="text-xs text-gray-500">{{ $activity['time'] ?? 'Unknown time' }} by {{ $activity['user'] ?? 'Unknown user' }}</p>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-8">
                                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                        <i class="fas fa-history text-gray-400 text-xl"></i>
                                    </div>
                                    <p class="text-gray-500">No recent activity</p>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <!-- Recent Orders -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-lg font-semibold text-gray-900">Recent Orders</h3>
                            <a href="{{ route('invoices.index') }}"
                                class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                View All →
                            </a>
                        </div>

                        <div class="space-y-4">
                            @forelse($recentOrders as $order)
                                <div class="flex items-center justify-between p-3 hover:bg-gray-50 rounded-lg transition-colors">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                            <i class="fas fa-file-invoice text-blue-600"></i>
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-gray-900">{{ $order->invoice_no }}</p>
                                            <p class="text-xs text-gray-500">{{ $order->customer->company_name ?? 'N/A' }}</p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-sm font-medium text-gray-900">₹{{ number_format($order->total, 0) }}</p>
                                        <p class="text-xs text-gray-500">{{ $order->created_at->diffForHumans() }}</p>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-8">
                                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                        <i class="fas fa-file-invoice text-gray-400 text-xl"></i>
                                    </div>
                                    <p class="text-gray-500">No recent orders</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Quick Actions Grid -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-8">
                    <h3 class="text-lg font-semibold text-gray-900 mb-6">Quick Actions</h3>
                    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
                        <a href="{{ route('customers.create') }}"
                            class="p-4 bg-blue-50 hover:bg-blue-100 rounded-lg transition-colors group text-center">
                            <div class="w-12 h-12 bg-blue-100 group-hover:bg-blue-200 rounded-lg flex items-center justify-center mx-auto mb-3">
                                <i class="fas fa-user-plus text-blue-600 text-xl"></i>
                            </div>
                            <p class="text-sm font-medium text-gray-900">Add Customer</p>
                            <p class="text-xs text-gray-500 mt-1">Create new customer</p>
                        </a>

                        @if (Route::has('products.create'))
                            <a href="{{ route('products.create') }}"
                                class="p-4 bg-purple-50 hover:bg-purple-100 rounded-lg transition-colors group text-center">
                                <div class="w-12 h-12 bg-purple-100 group-hover:bg-purple-200 rounded-lg flex items-center justify-center mx-auto mb-3">
                                    <i class="fas fa-box text-purple-600 text-xl"></i>
                                </div>
                                <p class="text-sm font-medium text-gray-900">Add Product</p>
                                <p class="text-xs text-gray-500 mt-1">Create new product</p>
                            </a>
                        @endif

                        @if (Route::has('invoices.create'))
                            <a href="{{ route('invoices.create') }}"
                                class="p-4 bg-green-50 hover:bg-green-100 rounded-lg transition-colors group text-center">
                                <div class="w-12 h-12 bg-green-100 group-hover:bg-green-200 rounded-lg flex items-center justify-center mx-auto mb-3">
                                    <i class="fas fa-file-invoice text-green-600 text-xl"></i>
                                </div>
                                <p class="text-sm font-medium text-gray-900">Create Order</p>
                                <p class="text-xs text-gray-500 mt-1">New invoice/booking</p>
                            </a>
                        @endif

                        @if (Route::has('stocks.index'))
                            <a href="{{ route('stocks.index') }}"
                                class="p-4 bg-yellow-50 hover:bg-yellow-100 rounded-lg transition-colors group text-center">
                                <div class="w-12 h-12 bg-yellow-100 group-hover:bg-yellow-200 rounded-lg flex items-center justify-center mx-auto mb-3">
                                    <i class="fas fa-warehouse text-yellow-600 text-xl"></i>
                                </div>
                                <p class="text-sm font-medium text-gray-900">Check Stock</p>
                                <p class="text-xs text-gray-500 mt-1">Inventory status</p>
                            </a>
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        function dashboard() {
            return {
                init() {
                    // Wait for Chart.js to load
                    if (typeof Chart !== 'undefined') {
                        this.initCharts();
                    } else {
                        setTimeout(() => {
                            this.initCharts();
                        }, 100);
                    }
                },

                initCharts() {
                    try {
                        const chartData = {!! json_encode($chartData ?? [
                            'labels' => [], 
                            'sales' => [], 
                            'customers' => [], 
                            'topProducts' => [], 
                            'topProductsSales' => []
                        ]) !!};
                        const orderStats = {!! json_encode($orderStats ?? [
                            'this_month' => 0, 
                            'total' => 0, 
                            'pending' => 0, 
                            'completed' => 0
                        ]) !!};

                        // Sales Trend Chart
                        this.initSalesChart(chartData);
                        
                        // Customer Growth Chart
                        this.initCustomerChart(chartData);
                        
                        // Top Products Chart
                        this.initTopProductsChart(chartData);
                        
                        // Order Status Chart
                        this.initOrderStatusChart(orderStats);
                        
                    } catch (error) {
                        console.error('Error initializing charts:', error);
                    }
                },

                initSalesChart(chartData) {
                    const salesCtx = document.getElementById('salesChart');
                    if (salesCtx && Chart) {
                        const hasSalesData = chartData.sales && chartData.sales.some(value => value > 0);
                        
                        new Chart(salesCtx, {
                            type: 'line',
                            data: {
                                labels: chartData.labels || [],
                                datasets: [{
                                    label: hasSalesData ? 'Sales (₹)' : 'No Sales Data',
                                    data: chartData.sales || [],
                                    borderColor: hasSalesData ? 'rgb(59, 130, 246)' : 'rgba(156, 163, 175, 0.5)',
                                    backgroundColor: hasSalesData ? 'rgba(59, 130, 246, 0.1)' : 'rgba(156, 163, 175, 0.1)',
                                    tension: 0.4,
                                    fill: true,
                                    borderWidth: 2
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: {
                                    legend: {
                                        display: false
                                    },
                                    tooltip: {
                                        callbacks: {
                                            label: function(context) {
                                                return '₹' + context.parsed.y.toLocaleString('en-IN');
                                            }
                                        }
                                    }
                                },
                                scales: {
                                    y: {
                                        beginAtZero: true,
                                        grid: {
                                            color: 'rgba(0, 0, 0, 0.1)'
                                        },
                                        ticks: {
                                            callback: function(value) {
                                                return '₹' + value.toLocaleString('en-IN');
                                            }
                                        }
                                    },
                                    x: {
                                        grid: {
                                            display: false
                                        }
                                    }
                                }
                            }
                        });
                    }
                },

                initCustomerChart(chartData) {
                    const customerCtx = document.getElementById('customerGrowthChart');
                    if (customerCtx && Chart) {
                        const hasCustomerData = chartData.customers && chartData.customers.some(value => value > 0);
                        
                        new Chart(customerCtx, {
                            type: 'bar',
                            data: {
                                labels: chartData.labels || [],
                                datasets: [{
                                    label: hasCustomerData ? 'New Customers' : 'No Customer Data',
                                    data: chartData.customers || [],
                                    backgroundColor: hasCustomerData ? 'rgba(16, 185, 129, 0.8)' : 'rgba(156, 163, 175, 0.5)',
                                    borderColor: hasCustomerData ? 'rgb(16, 185, 129)' : 'rgb(156, 163, 175)',
                                    borderWidth: 1,
                                    borderRadius: 4
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: {
                                    legend: {
                                        display: false
                                    }
                                },
                                scales: {
                                    y: {
                                        beginAtZero: true,
                                        grid: {
                                            color: 'rgba(0, 0, 0, 0.1)'
                                        },
                                        ticks: {
                                            stepSize: 1
                                        }
                                    },
                                    x: {
                                        grid: {
                                            display: false
                                        }
                                    }
                                }
                            }
                        });
                    }
                },

                initTopProductsChart(chartData) {
                    const topProductsCtx = document.getElementById('topProductsChart');
                    if (topProductsCtx && Chart) {
                        const hasProductData = chartData.topProducts && chartData.topProducts.length > 0;
                        
                        new Chart(topProductsCtx, {
                            type: 'doughnut',
                            data: {
                                labels: hasProductData ? chartData.topProducts : ['No Data'],
                                datasets: [{
                                    data: hasProductData ? chartData.topProductsSales : [1],
                                    backgroundColor: hasProductData ? [
                                        '#F59E0B', '#EF4444', '#8B5CF6', '#06B6D4', '#10B981'
                                    ] : ['rgba(156, 163, 175, 0.5)'],
                                    borderWidth: 2,
                                    borderColor: '#fff'
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: {
                                    legend: {
                                        position: 'bottom',
                                        labels: {
                                            usePointStyle: true,
                                            padding: 20
                                        }
                                    },
                                    tooltip: {
                                        callbacks: {
                                            label: function(context) {
                                                return context.label + ': ' + context.parsed + ' units sold';
                                            }
                                        }
                                    }
                                }
                            }
                        });
                    }
                },

                initOrderStatusChart(orderStats) {
                    const orderStatusCtx = document.getElementById('orderStatusChart');
                    if (orderStatusCtx && Chart) {
                        new Chart(orderStatusCtx, {
                            type: 'pie',
                            data: {
                                labels: ['This Month', 'Previous Orders', 'Pending', 'Completed'],
                                datasets: [{
                                    data: [
                                        orderStats.this_month || 0,
                                        Math.max((orderStats.total || 0) - (orderStats.this_month || 0), 0),
                                        orderStats.pending || 0,
                                        orderStats.completed || 0
                                    ],
                                    backgroundColor: [
                                        'rgba(59, 130, 246, 0.8)',
                                        'rgba(156, 163, 175, 0.8)',
                                        'rgba(245, 158, 11, 0.8)',
                                        'rgba(16, 185, 129, 0.8)'
                                    ],
                                    borderColor: [
                                        'rgb(59, 130, 246)',
                                        'rgb(156, 163, 175)',
                                        'rgb(245, 158, 11)',
                                        'rgb(16, 185, 129)'
                                    ],
                                    borderWidth: 2
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: {
                                    legend: {
                                        position: 'bottom',
                                        labels: {
                                            usePointStyle: true,
                                            padding: 20
                                        }
                                    }
                                }
                            }
                        });
                    }
                }
            };
        }

        // Auto-refresh dashboard data every 5 minutes
        setInterval(function() {
            if (document.visibilityState === 'visible') {
                window.location.reload();
            }
        }, 300000); // 5 minutes
    </script>
</x-app-layout>