<x-app-layout>
    <x-slot name="title">
        {{ __('Activity Logs') }} - {{ config('app.name', 'IMS') }}
    </x-slot>

    <div class="bg-white" x-data="activityLogs()" x-init="init()">
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
                            <i class="fas fa-chevron-right w-3 h-3 text-gray-400 mx-1"></i>
                            <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">Activity Logs</span>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>

        <!-- Header -->
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center mr-4">
                        <i class="fas fa-history text-gray-600 text-xl"></i>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">Activity Logs</h1>
                        <p class="text-sm text-gray-600 mt-1">Track all user activities and system events</p>
                    </div>
                </div>
                <div class="flex items-center space-x-3">
                    <!-- View Toggle -->
                    <div class="flex items-center bg-gray-100 rounded-lg p-1">
                        <button @click="viewMode = 'timeline'" 
                                :class="viewMode === 'timeline' ? 'bg-white shadow-sm text-gray-900' : 'text-gray-600'"
                                class="px-3 py-1 rounded text-sm font-medium transition-colors">
                            <i class="fas fa-timeline mr-1"></i>
                            Timeline
                        </button>
                        <button @click="viewMode = 'table'" 
                                :class="viewMode === 'table' ? 'bg-white shadow-sm text-gray-900' : 'text-gray-600'"
                                class="px-3 py-1 rounded text-sm font-medium transition-colors">
                            <i class="fas fa-table mr-1"></i>
                            Table
                        </button>
                    </div>
                    
                    <!-- Help Button -->
                    <button @click="showHelp = true" 
                            class="p-2 bg-gray-100 hover:bg-gray-200 text-gray-600 rounded-lg transition-colors">
                        <i class="fas fa-question-circle w-4 h-4"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="bg-gray-50 min-h-screen">
            <div class="p-6">

                <!-- Search and Filter Section -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
                    <div class="flex flex-col space-y-4">
                        <!-- Search Form -->
                        <form method="GET" action="{{ route('activity-logs.index') }}" 
                              class="flex flex-col sm:flex-row gap-4">
                            @if (request('module'))
                                <input type="hidden" name="module" value="{{ request('module') }}">
                            @endif
                            
                            <div class="flex-1">
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-search text-gray-400"></i>
                                    </div>
                                    <input type="text" 
                                           name="search" 
                                           value="{{ request('search') }}"
                                           placeholder="Search logs by action, user type, or description..."
                                           class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                </div>
                            </div>
                            
                            <div class="flex gap-2">
                                <button type="submit"
                                        class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition-colors">
                                    <i class="fas fa-search mr-2"></i>
                                    Search
                                </button>
                                <a href="{{ route('activity-logs.index') }}"
                                   class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg font-medium transition-colors">
                                    <i class="fas fa-times mr-2"></i>
                                    Clear
                                </a>
                            </div>
                        </form>

                        <!-- Active Filter Display -->
                        @if (request('module'))
                            <div class="flex items-center">
                                <span class="text-sm text-gray-600 mr-2">Filtering by module:</span>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    {{ ucfirst(request('module')) }}
                                    <a href="{{ route('activity-logs.index') }}" class="ml-1 text-blue-600 hover:text-blue-800">
                                        <i class="fas fa-times"></i>
                                    </a>
                                </span>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Module Filter Cards -->
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 gap-4 mb-6">
                    <!-- All Logs -->
                    <a href="{{ route('activity-logs.index') }}"
                       class="p-4 rounded-lg border-2 transition-all hover:shadow-md {{ !request('module') ? 'border-blue-500 bg-blue-50' : 'border-gray-200 bg-white hover:border-gray-300' }}">
                        <div class="text-center">
                            <div class="w-8 h-8 mx-auto mb-2 rounded-full flex items-center justify-center {{ !request('module') ? 'bg-blue-100' : 'bg-gray-100' }}">
                                <i class="fas fa-history text-sm {{ !request('module') ? 'text-blue-600' : 'text-gray-600' }}"></i>
                            </div>
                            <div class="text-sm font-medium text-gray-900">All Logs</div>
                            <div class="text-xs text-gray-500">{{ $logs->total() }} entries</div>
                        </div>
                    </a>

                    @foreach ($modules as $mod)
                        <a href="{{ route('activity-logs.index', ['module' => $mod->module]) }}"
                           class="p-4 rounded-lg border-2 transition-all hover:shadow-md {{ request('module') === $mod->module ? 'border-blue-500 bg-blue-50' : 'border-gray-200 bg-white hover:border-gray-300' }}">
                            <div class="text-center">
                                <div class="w-8 h-8 mx-auto mb-2 rounded-full flex items-center justify-center {{ request('module') === $mod->module ? 'bg-blue-100' : 'bg-gray-100' }}">
                                    @php
                                        $icons = [
                                            'customers' => 'fa-users',
                                            'invoices' => 'fa-file-invoice',
                                            'products' => 'fa-box',
                                            'auth' => 'fa-sign-in-alt',
                                            'settings' => 'fa-cog',
                                            'reports' => 'fa-chart-bar'
                                        ];
                                        $icon = $icons[$mod->module] ?? 'fa-cube';
                                    @endphp
                                    <i class="fas {{ $icon }} text-sm {{ request('module') === $mod->module ? 'text-blue-600' : 'text-gray-600' }}"></i>
                                </div>
                                <div class="text-sm font-medium text-gray-900">{{ ucfirst($mod->module) }}</div>
                                <div class="text-xs text-gray-500">{{ $mod->count }} logs</div>
                            </div>
                        </a>
                    @endforeach
                </div>

                <!-- Timeline View -->
                <div x-show="viewMode === 'timeline'" class="space-y-6">
                    @if ($logs->isNotEmpty())
                        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-6">Activity Timeline</h3>
                            
                            <div class="flow-root">
                                <ul class="-mb-8">
                                    @foreach ($logs as $index => $log)
                                        <li class="relative pb-8 {{ !$loop->last ? '' : 'pb-0' }}">
                                            @if (!$loop->last)
                                                <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200"></span>
                                            @endif
                                            
                                            <div class="relative flex space-x-3">
                                                <!-- Activity Icon -->
                                                <div class="flex h-8 w-8 items-center justify-center rounded-full ring-8 ring-white 
                                                    @php
                                                        $bgColors = [
                                                            'created' => 'bg-green-500',
                                                            'updated' => 'bg-blue-500', 
                                                            'deleted' => 'bg-red-500',
                                                            'login' => 'bg-purple-500',
                                                            'logout' => 'bg-gray-500'
                                                        ];
                                                        echo $bgColors[strtolower($log->action_type)] ?? 'bg-gray-400';
                                                    @endphp
                                                ">
                                                    @php
                                                        $iconMap = [
                                                            'created' => 'fa-plus',
                                                            'updated' => 'fa-edit', 
                                                            'deleted' => 'fa-trash',
                                                            'login' => 'fa-sign-in-alt',
                                                            'logout' => 'fa-sign-out-alt'
                                                        ];
                                                        $icon = $iconMap[strtolower($log->action_type)] ?? 'fa-info-circle';
                                                    @endphp
                                                    <i class="fas {{ $icon }} text-white text-xs"></i>
                                                </div>
                                                
                                                <!-- Activity Details -->
                                                <div class="flex-1 min-w-0">
                                                    <div class="flex items-center justify-between">
                                                        <div class="flex-1">
                                                            <p class="text-sm font-medium text-gray-900">
                                                                {{ $log->user->name ?? 'System' }}
                                                                <span class="font-normal text-gray-600">{{ strtolower($log->action_type) }}</span>
                                                                <span class="font-medium text-blue-600">{{ $log->module }}</span>
                                                            </p>
                                                            @if($log->description)
                                                                <p class="text-sm text-gray-500">{{ $log->description }}</p>
                                                            @endif
                                                            <div class="flex items-center text-xs text-gray-400 mt-1 space-x-4">
                                                                <span>{{ $log->user_type }}</span>
                                                                <span>{{ $log->ip_address }}</span>
                                                                <span>{{ $log->created_at->format('M d, Y h:i A') }}</span>
                                                            </div>
                                                        </div>
                                                        <div class="flex items-center space-x-2">
                                                            <a href="{{ route('activity-logs.show', $log->id) }}" 
                                                               class="text-blue-600 hover:text-blue-800 p-1" title="View Details">
                                                                <i class="fas fa-eye text-sm"></i>
                                                            </a>
                                                            <form action="{{ route('activity-logs.destroy', $log->id) }}" 
                                                                  method="POST" 
                                                                  class="inline"
                                                                  onsubmit="return confirm('Are you sure you want to delete this log?')">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" 
                                                                        class="text-red-600 hover:text-red-800 p-1" title="Delete">
                                                                    <i class="fas fa-trash text-sm"></i>
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @else
                        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-12 text-center">
                            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-history text-gray-400 text-xl"></i>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">No Activity Found</h3>
                            <p class="text-gray-500">There are no activity logs to display.</p>
                        </div>
                    @endif
                </div>

                <!-- Table View -->
                <div x-show="viewMode === 'table'" class="bg-white rounded-lg shadow-sm border border-gray-200">
                    @if ($logs->isNotEmpty())
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-900">Activity Table</h3>
                        </div>
                        
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Module</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">IP Address</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach ($logs as $log)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center mr-3">
                                                        <i class="fas fa-user text-gray-600 text-sm"></i>
                                                    </div>
                                                    <div>
                                                        <div class="text-sm font-medium text-gray-900">{{ $log->user->name ?? '-' }}</div>
                                                        <div class="text-sm text-gray-500">{{ $log->user_type }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                    @php
                                                        $badgeColors = [
                                                            'created' => 'bg-green-100 text-green-800',
                                                            'updated' => 'bg-blue-100 text-blue-800',
                                                            'deleted' => 'bg-red-100 text-red-800',
                                                            'login' => 'bg-purple-100 text-purple-800',
                                                            'logout' => 'bg-gray-100 text-gray-800'
                                                        ];
                                                        echo $badgeColors[strtolower($log->action_type)] ?? 'bg-gray-100 text-gray-800';
                                                    @endphp
                                                ">
                                                    {{ $log->action_type }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ ucfirst($log->module) }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $log->created_at->format('M d, Y h:i A') }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $log->ip_address }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                <div class="flex items-center justify-end space-x-2">
                                                    <a href="{{ route('activity-logs.show', $log->id) }}" 
                                                       class="text-blue-600 hover:text-blue-900">View</a>
                                                    <form action="{{ route('activity-logs.destroy', $log->id) }}" 
                                                          method="POST" 
                                                          class="inline"
                                                          onsubmit="return confirm('Are you sure you want to delete this log?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-red-600 hover:text-red-900 ml-2">Delete</button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="p-12 text-center">
                            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-history text-gray-400 text-xl"></i>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">No Activity Found</h3>
                            <p class="text-gray-500">There are no activity logs to display.</p>
                        </div>
                    @endif
                </div>

                <!-- Pagination -->
                @if ($logs->hasPages())
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 px-6 py-4 mt-6">
                        {{ $logs->appends(request()->input())->links() }}
                    </div>
                @endif

                <!-- Bulk Actions -->
                @if ($logs->isNotEmpty())
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mt-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Bulk Actions</h3>
                        <div class="flex flex-wrap gap-3">
                            @if (request('module'))
                                <form action="{{ route('activity-logs.destroyModule', ['module' => request('module')]) }}" 
                                      method="POST" 
                                      class="inline"
                                      onsubmit="return confirm('Are you sure you want to delete all logs for this module?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-colors">
                                        <i class="fas fa-trash mr-2"></i>
                                        Clear {{ ucfirst(request('module')) }} Logs
                                    </button>
                                </form>
                            @else
                                <a href="{{ route('activity-logs.destroyAll') }}" 
                                   onclick="return confirm('Are you sure you want to clear all activity logs?')"
                                   class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-colors">
                                    <i class="fas fa-trash mr-2"></i>
                                    Clear All Logs
                                </a>
                            @endif
                        </div>
                    </div>
                @endif

            </div>
        </div>

        <!-- Help Modal -->
        <div x-show="showHelp" 
             x-transition.opacity
             class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50"
             style="display: none;">
            <div class="bg-white rounded-lg shadow-xl max-w-md w-full p-6" @click.outside="showHelp = false">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">Activity Logs Help</h3>
                    <button @click="showHelp = false" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="space-y-4 text-sm text-gray-600">
                    <div>
                        <h4 class="font-medium text-gray-900 mb-1">Timeline View</h4>
                        <p>Shows activities in chronological order with visual indicators for different action types.</p>
                    </div>
                    <div>
                        <h4 class="font-medium text-gray-900 mb-1">Table View</h4>
                        <p>Traditional table layout with sortable columns and detailed information.</p>
                    </div>
                    <div>
                        <h4 class="font-medium text-gray-900 mb-1">Module Filtering</h4>
                        <p>Click on module cards to filter activities by specific system components.</p>
                    </div>
                    <div>
                        <h4 class="font-medium text-gray-900 mb-1">Search</h4>
                        <p>Use the search bar to find specific activities by action type, user, or description.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function activityLogs() {
            return {
                viewMode: 'timeline',
                showHelp: false,
                
                init() {
                    // Initialize with saved view mode preference
                    const savedViewMode = localStorage.getItem('activity-logs-view-mode');
                    if (savedViewMode) {
                        this.viewMode = savedViewMode;
                    }
                },
                
                setViewMode(mode) {
                    this.viewMode = mode;
                    localStorage.setItem('activity-logs-view-mode', mode);
                }
            }
        }
    </script>
</x-app-layout>
