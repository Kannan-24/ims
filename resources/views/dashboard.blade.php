<x-app-layout>
    <x-slot name="title">Dashboard</x-slot>

    <div class="mt-20 py-6 px-4 sm:ml-64 bg-gray-900 text-white min-h-screen">
        <!-- Welcome -->
        <div class="text-center bg-gradient-to-r from-indigo-500 to-purple-600 p-6 rounded-lg shadow-lg mb-6">
            <h1 class="text-3xl font-bold">Welcome Back, {{ Auth::user()->name ?? 'User' }} 👋</h1>
            <p class="text-sm mt-2">Here's a quick look at today’s system performance.</p>
        </div>

        <!-- Metric Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">

            <!-- Customers -->
            <div class="flex items-center gap-4 p-5 bg-white dark:bg-gray-800 shadow rounded-2xl">
                <div class="bg-blue-100 dark:bg-blue-800 p-3 rounded-full">
                    <!-- Custom Customer Icon -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-blue-600 dark:text-blue-200"
                        viewBox="0 0 640 512" fill="currentColor">
                        <path
                            d="M144 0a80 80 0 1 1 0 160A80 80 0 1 1 144 0zM512 0a80 80 0 1 1 0 160A80 80 0 1 1 512 0zM0 298.7C0 239.8 47.8 192 106.7 192l42.7 0c15.9 0 31 3.5 44.6 9.7c-1.3 7.2-1.9 14.7-1.9 22.3c0 38.2 16.8 72.5 43.3 96c-.2 0-.4 0-.7 0L21.3 320C9.6 320 0 310.4 0 298.7zM405.3 320c-.2 0-.4 0-.7 0c26.6-23.5 43.3-57.8 43.3-96c0-7.6-.7-15-1.9-22.3c13.6-6.3 28.7-9.7 44.6-9.7l42.7 0C592.2 192 640 239.8 640 298.7c0 11.8-9.6 21.3-21.3 21.3l-213.3 0zM224 224a96 96 0 1 1 192 0 96 96 0 1 1 -192 0zM128 485.3C128 411.7 187.7 352 261.3 352l117.3 0C452.3 352 512 411.7 512 485.3c0 14.7-11.9 26.7-26.7 26.7l-330.7 0c-14.7 0-26.7-11.9-26.7-26.7z" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-300 font-medium">Customers</p>
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $totalCustomers }}</h2>
                </div>
            </div>

            <!-- Orders -->
            <div class="flex items-center gap-4 p-5 bg-white dark:bg-gray-800 shadow rounded-2xl">
                <div class="bg-yellow-100 dark:bg-yellow-800 p-3 rounded-full">
                    <!-- Custom Orders Icon -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-yellow-600 dark:text-yellow-200"
                        viewBox="0 0 384 512" fill="currentColor">
                        <path
                            d="M64 0C28.7 0 0 28.7 0 64L0 448c0 35.3 28.7 64 64 64l256 0c35.3 0 64-28.7 64-64l0-288-128 0c-17.7 0-32-14.3-32-32L224 0 64 0zM256 0l0 128 128 0L256 0zM80 64l64 0c8.8 0 16 7.2 16 16s-7.2 16-16 16L80 96c-8.8 0-16-7.2-16-16s7.2-16 16-16zm0 64l64 0c8.8 0 16 7.2 16 16s-7.2 16-16 16l-64 0c-8.8 0-16-7.2-16-16s7.2-16 16-16zm16 96l192 0c17.7 0 32 14.3 32 32l0 64c0 17.7-14.3 32-32 32L96 352c-17.7 0-32-14.3-32-32l0-64c0-17.7 14.3-32 32-32zm0 32l0 64 192 0 0-64L96 256zM240 416l64 0c8.8 0 16 7.2 16 16s-7.2 16-16 16l-64 0c-8.8 0-16-7.2-16-16s7.2-16 16-16z" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-300 font-medium">Orders</p>
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $totalInvoices }}</h2>
                </div>
            </div>

            <!-- Purchases -->
            <div class="flex items-center gap-4 p-5 bg-white dark:bg-gray-800 shadow rounded-2xl">
                <div class="bg-green-100 dark:bg-green-800 p-3 rounded-full">
                    <!-- Custom Purchase Icon -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-green-600 dark:text-green-200"
                        viewBox="0 0 576 512" fill="currentColor">
                        <path
                            d="M0 24C0 10.7 10.7 0 24 0L69.5 0c22 0 41.5 12.8 50.6 32l411 0c26.3 0 45.5 25 38.6 50.4l-41 152.3c-8.5 31.4-37 53.3-69.5 53.3l-288.5 0 5.4 28.5c2.2 11.3 12.1 19.5 23.6 19.5L488 336c13.3 0 24 10.7 24 24s-10.7 24-24 24l-288.3 0c-34.6 0-64.3-24.6-70.7-58.5L77.4 54.5c-.7-3.8-4-6.5-7.9-6.5L24 48C10.7 48 0 37.3 0 24zM128 464a48 48 0 1 1 96 0 48 48 0 1 1 -96 0zm336-48a48 48 0 1 1 0 96 48 48 0 1 1 0-96z" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-300 font-medium">Total Purchases</p>
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white">₹
                        {{ number_format($totalPurchases, 2) }}</h2>
                </div>
            </div>

            <!-- Total Sales -->
            <div class="flex items-center gap-4 p-5 bg-white dark:bg-gray-800 shadow rounded-2xl">
                <div class="bg-purple-100 dark:bg-purple-800 p-3 rounded-full">
                    <!-- Custom Sales Icon -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-purple-600 dark:text-purple-200"
                        viewBox="0 0 320 512" fill="currentColor">
                        <path
                            d="M0 64C0 46.3 14.3 32 32 32l64 0 16 0 176 0c17.7 0 32 14.3 32 32s-14.3 32-32 32l-56.2 0c9.6 14.4 16.7 30.6 20.7 48l35.6 0c17.7 0 32 14.3 32 32s-14.3 32-32 32l-35.6 0c-13.2 58.3-61.9 103.2-122.2 110.9L274.6 422c14.4 10.3 17.7 30.3 7.4 44.6s-30.3 17.7-44.6 7.4L13.4 314C2.1 306-2.7 291.5 1.5 278.2S18.1 256 32 256l80 0c32.8 0 61-19.7 73.3-48L32 208c-17.7 0-32-14.3-32-32s14.3-32 32-32l153.3 0C173 115.7 144.8 96 112 96L96 96 32 96C14.3 96 0 81.7 0 64z" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-300 font-medium">Total Sales</p>
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white">₹ {{ number_format($totalSales, 2) }}</h2>
                </div>
            </div>

        </div>


        <!-- Charts -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <!-- Monthly Sales Chart -->
            <div class="bg-gray-800 p-6 rounded-lg shadow-lg">
                <h3 class="text-xl mb-4 font-semibold text-white">Monthly Sales</h3>
                <canvas id="monthlySalesChart" height="150"></canvas>
            </div>

            <!-- Statistics Overview Chart -->
            <div class="bg-gray-800 p-6 rounded-lg shadow-lg">
                <h3 class="text-xl mb-4 font-semibold text-white">Statistics Overview</h3>
                <canvas id="statisticsChart" height="150"></canvas>
            </div>
        </div>

        <!-- Demographics Overview Card -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
            <div class="bg-gray-800 p-4 rounded-lg shadow mb-6 col-span-2">
                <h3 class="text-xl mb-4 font-semibold">Orders Overview</h3>
                <table class="table-auto w-full text-left text-sm">
                    <thead>
                        <tr class="border-b border-gray-700">
                            <th class="px-4 py-2">#</th>
                            <th class="px-4 py-2">Invoice No</th>
                            <th class="px-4 py-2">Customer</th>
                            <th class="px-4 py-2">Total (₹)</th>
                            <th class="px-4 py-2">Date</th>
                            <th class="px-4 py-2">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($recentInvoicesFiltered as $invoice)
                            <tr class="border-b border-gray-700">
                                <td class="px-4 py-2">{{ $loop->iteration }}</td>
                                 
                                <td class="px-4 py-2">{{ $invoice->Customer->company_name }}</td>
                                <td class="px-4 py-2">₹ {{ number_format($invoice->total, 2) }}</td>
                                <td class="px-4 py-2">{{ $invoice->invoice_date }}</td>
                                <td class="px-4 py-2">
                                    @if ($invoice->payment && $invoice->payment->first())
                                        @php
                                            $payment = $invoice->payment->first();
                                            $statusClass = match ($payment->status) {
                                                'paid' => 'bg-green-600',
                                                'partial' => 'bg-yellow-500',
                                                'unpaid' => 'bg-red-600',
                                                default => 'bg-gray-500',
                                            };
                                        @endphp
                                        <a href="{{ route('payments.show', $payment->id) }}"
                                            class="text-white px-2 py-1 rounded text-xs {{ $statusClass }}">
                                            {{ ucfirst($payment->status) }}
                                        </a>
                                    @else
                                        <span class="bg-gray-500 text-white px-2 py-1 rounded text-xs">Not
                                            Available</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>


                <!-- Pagination for Orders -->
                <div class="mt-4">
                    {{ $recentInvoicesFiltered->links() }} <!-- Pagination Links -->
                </div>
            </div>

            <!-- Demographics Overview -->
            <div class="bg-gray-800 p-4 rounded-lg shadow mb-6">
                <h3 class="text-xl mb-4 font-semibold">Demographics Overview</h3>
                <canvas id="demographicsChart" height="100"></canvas>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                const demographicsCtx = document.getElementById('demographicsChart').getContext('2d');
                new Chart(demographicsCtx, {
                    type: 'pie',
                    data: {
                        labels: ['Districts', 'States', 'Countries'],
                        datasets: [{
                            data: [{{ $districtsCount }}, {{ $statesCount }},
                                {{ $countriesCount }}
                            ],
                            backgroundColor: ['#FF8C66', '#66FF8C', '#668CFF'], // Lighter colors
                            hoverOffset: 4,
                            borderWidth: 0,
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                position: 'top',
                                labels: {
                                    font: {
                                        size: 14
                                    }
                                }
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(tooltipItem) {
                                        return tooltipItem.label + ': ' + tooltipItem.raw;
                                    }
                                }
                            }
                        }
                    }
                });

                const salesCtx = document.getElementById('monthlySalesChart').getContext('2d');
                new Chart(salesCtx, {
                    type: 'bar',
                    data: {
                        labels: {!! json_encode($months) !!},
                        datasets: [{
                            label: 'Monthly Sales',
                            data: {!! json_encode($monthlySales) !!},
                            backgroundColor: '#6366f1',
                            borderRadius: 5,
                            borderColor: '#4f46e5',
                            borderWidth: 2,
                            hoverBackgroundColor: '#4f46e5',
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        scales: {
                            x: {
                                beginAtZero: true,
                                ticks: {
                                    font: {
                                        size: 14,
                                        weight: 'bold'
                                    }
                                }
                            },
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    font: {
                                        size: 14,
                                        weight: 'bold'
                                    },
                                    callback: function(value) {
                                        return '₹ ' + value.toLocaleString();
                                    }
                                }
                            }
                        }
                    }
                });

                const statsCtx = document.getElementById('statisticsChart').getContext('2d');
                new Chart(statsCtx, {
                    type: 'line',
                    data: {
                        labels: {!! json_encode($months) !!},
                        datasets: [
                            {
                                label: 'Sales',
                                data: {!! json_encode($statisticsData['sales']) !!},
                                borderColor: '#34d399',
                                fill: false,
                                tension: 0.1,
                                borderWidth: 3,
                                pointRadius: 4,
                                pointBackgroundColor: '#34d399',
                            },
                            {
                                label: 'Purchases',
                                data: {!! json_encode($statisticsData['purchases']) !!},
                                borderColor: '#f87171',
                                fill: false,
                                tension: 0.1,
                                borderWidth: 3,
                                pointRadius: 4,
                                pointBackgroundColor: '#f87171',
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        scales: {
                            x: {
                                beginAtZero: true,
                                ticks: {
                                    font: {
                                        size: 14,
                                        weight: 'bold'
                                    }
                                }
                            },
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    font: {
                                        size: 14,
                                        weight: 'bold'
                                    },
                                    callback: function(value) {
                                        return '₹ ' + value.toLocaleString();
                                    }
                                }
                            }
                        }
                    }
                });
            });
        </script>
</x-app-layout>
