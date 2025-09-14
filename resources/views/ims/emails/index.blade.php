<x-app-layout>
    <x-slot name="title">
        {{ __('Email Management') }} - {{ config('app.name', 'IMS') }}
    </x-slot>

    <div class="bg-white min-h-screen" x-data="emailManagement()" x-init="init()">
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
                    <li aria-current="page">
                        <div class="flex items-center">
                            <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                            <span class="text-sm font-medium text-gray-500">Email Management</span>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>

        <!-- Header -->
        <div class="px-6 py-6 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Email Management</h1>
                    <p class="text-lg text-gray-600 mt-2">Manage sent emails, drafts, and compose new messages</p>
                </div>
                <div class="flex items-center space-x-3">
                    <!-- Help Button -->
                    <button @click="showHelp = true"
                        class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition-colors">
                        <i class="fas fa-question-circle w-4 h-4 mr-2"></i>
                        Help
                    </button>
                    <!-- Drafts Button -->
                    <a href="{{ route('emails.drafts') }}"
                        class="inline-flex items-center px-4 py-2 bg-orange-600 hover:bg-orange-700 text-white text-sm font-medium rounded-lg transition-colors">
                        <i class="fas fa-edit w-4 h-4 mr-2"></i>
                        View Drafts ({{ $drafts }})
                    </a>
                    <!-- Compose Button -->
                    <a href="{{ route('emails.create') }}"
                        class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                        <i class="fas fa-plus w-4 h-4 mr-2"></i>
                        Compose Email
                    </a>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="px-6 py-6">
            <!-- Search and Filter Bar -->
            <div class="bg-white border border-gray-200 rounded-lg p-4 mb-6 shadow-sm">
                <!-- Top Controls -->
                <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4 mb-4">
                    <!-- Left Side: Selection Controls -->
                    <div class="flex items-center space-x-4">
                        <label class="flex items-center">
                            <input type="checkbox" x-model="selectAll" @change="toggleSelectAll()" 
                                   class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            <span class="ml-2 text-sm font-medium text-gray-700">Select All</span>
                        </label>
                        <span x-show="selectedEmails.length > 0" 
                              class="text-sm text-blue-600 font-medium">
                            <span x-text="selectedEmails.length"></span> selected
                        </span>
                        <div x-show="selectedEmails.length > 0" class="flex items-center space-x-2">
                            <button @click="deleteSelected()" 
                                    class="px-3 py-1.5 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-colors">
                                <i class="fas fa-trash mr-2"></i>Delete Selected
                            </button>
                            <button @click="markAsRead()" 
                                    class="px-3 py-1.5 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-colors">
                                <i class="fas fa-check mr-2"></i>Mark as Read
                            </button>
                        </div>
                    </div>
                    
                    <!-- Right Side: View Controls -->
                    <div class="flex items-center space-x-3">
                        <div class="flex items-center bg-gray-100 rounded-lg p-1">
                            <button @click="setViewMode('list')" 
                                    :class="viewMode === 'list' ? 'bg-white shadow-sm text-blue-600' : 'text-gray-600'"
                                    class="px-3 py-1.5 text-sm font-medium rounded-md transition-colors">
                                <i class="fas fa-list mr-2"></i>List
                            </button>
                            <button @click="setViewMode('grid')" 
                                    :class="viewMode === 'grid' ? 'bg-white shadow-sm text-blue-600' : 'text-gray-600'"
                                    class="px-3 py-1.5 text-sm font-medium rounded-md transition-colors">
                                <i class="fas fa-th-large mr-2"></i>Grid
                            </button>
                        </div>
                        <select x-model="sortBy" @change="applySorting()" 
                                class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="date_desc">Newest First</option>
                            <option value="date_asc">Oldest First</option>
                            <option value="subject_asc">Subject A-Z</option>
                            <option value="subject_desc">Subject Z-A</option>
                            <option value="recipient_asc">Recipient A-Z</option>
                        </select>
                    </div>
                </div>
                
                <!-- Search Form -->
                <form method="GET" action="{{ route('emails.index') }}" class="flex flex-col sm:flex-row gap-4">
                    <div class="flex-1">
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-search text-gray-400"></i>
                            </div>
                            <input type="text" 
                                name="search" 
                                value="{{ request('search') }}"
                                placeholder="Search by subject, recipient, or content..."
                                class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    </div>
                    <div class="flex gap-2">
                        <button type="submit"
                            class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                            <i class="fas fa-search mr-2"></i>
                            Filter
                        </button>
                        <a href="{{ route('emails.index') }}"
                            class="inline-flex items-center px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-700 text-sm font-medium rounded-lg transition-colors">
                            <i class="fas fa-redo mr-2"></i>
                            Reset
                        </a>
                    </div>
                </form>
            </div>

            <!-- Email Display -->
            <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden">
                @if ($emails->isEmpty())
                    <div class="text-center py-12">
                        <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-envelope text-gray-400 text-3xl"></i>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">No emails found</h3>
                        <p class="text-gray-600 mb-4">Get started by composing your first email.</p>
                        <a href="{{ route('emails.create') }}"
                            class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                            <i class="fas fa-plus mr-2"></i>
                            Compose Email
                        </a>
                    </div>
                @else
                    <!-- List View -->
                    <div x-show="viewMode === 'list'" 
                         x-transition:enter="transition-opacity duration-200"
                         x-transition:enter-start="opacity-0"
                         x-transition:enter-end="opacity-100"
                         class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left">
                                        <input type="checkbox" x-model="selectAll" @change="toggleSelectAll()" 
                                               class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100">
                                        <span class="flex items-center">
                                            Recipients
                                            <i class="fas fa-sort ml-1 text-gray-400"></i>
                                        </span>
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100">
                                        <span class="flex items-center">
                                            Subject
                                            <i class="fas fa-sort ml-1 text-gray-400"></i>
                                        </span>
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100">
                                        <span class="flex items-center">
                                            Date Sent
                                            <i class="fas fa-sort ml-1 text-gray-400"></i>
                                        </span>
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Status
                                    </th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($emails as $email)
                                    <tr class="hover:bg-gray-50 transition-colors"
                                        :class="selectedEmails.includes({{ $email->id }}) ? 'bg-blue-50' : ''">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <input type="checkbox" 
                                                   :checked="selectedEmails.includes({{ $email->id }})"
                                                   @change="toggleEmailSelection({{ $email->id }})"
                                                   class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10">
                                                    <div class="h-10 w-10 bg-blue-100 rounded-full flex items-center justify-center">
                                                        <i class="fas fa-envelope text-blue-600"></i>
                                                    </div>
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900">
                                                        {{ \Str::limit($email->to, 30) }}
                                                    </div>
                                                    @if($email->cc)
                                                        <div class="text-xs text-gray-500">
                                                            CC: {{ \Str::limit($email->cc, 20) }}
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ \Str::limit($email->subject, 50) }}
                                            </div>
                                            <div class="text-xs text-gray-500">
                                                {{ \Str::limit(strip_tags($email->body), 60) }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            <div class="flex flex-col">
                                                <span>{{ $email->created_at->format('M d, Y') }}</span>
                                                <span class="text-xs text-gray-500">{{ $email->created_at->format('h:i A') }}</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($email->is_draft)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                                    <i class="fas fa-edit mr-1"></i>Draft
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    <i class="fas fa-check mr-1"></i>Sent
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                            <div class="flex items-center justify-center space-x-2">
                                                <a href="{{ route('emails.show', $email) }}" 
                                                   class="text-blue-600 hover:text-blue-900" title="View Email">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                @if($email->is_draft)
                                                    <a href="{{ route('emails.edit', $email) }}" 
                                                       class="text-green-600 hover:text-green-900" title="Edit Draft">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                @endif
                                                <button @click="deleteEmail({{ $email->id }})" 
                                                        class="text-red-600 hover:text-red-900" title="Delete">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Grid View -->
                    <div x-show="viewMode === 'grid'" 
                         x-transition:enter="transition-opacity duration-200"
                         x-transition:enter-start="opacity-0"
                         x-transition:enter-end="opacity-100"
                         class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                            @foreach ($emails as $email)
                                <div class="bg-white border border-gray-200 rounded-lg p-4 hover:shadow-lg transition-all duration-200 cursor-pointer grid-card"
                                     :class="selectedEmails.includes({{ $email->id }}) ? 'ring-2 ring-blue-500 bg-blue-50' : ''"
                                     @click="toggleEmailSelection({{ $email->id }})">
                                    <!-- Header -->
                                    <div class="flex items-center justify-between mb-3">
                                        <div class="flex items-center">
                                            <input type="checkbox" 
                                                   :checked="selectedEmails.includes({{ $email->id }})"
                                                   @click.stop
                                                   @change="toggleEmailSelection({{ $email->id }})"
                                                   class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                            <div class="ml-2 w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                                <i class="fas fa-envelope text-blue-600 text-sm"></i>
                                            </div>
                                        </div>
                                        <div class="flex items-center space-x-1">
                                            @if($email->is_draft)
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                                    <i class="fas fa-edit mr-1"></i>Draft
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    <i class="fas fa-check mr-1"></i>Sent
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Content -->
                                    <div class="mb-3">
                                        <h3 class="text-sm font-semibold text-gray-900 mb-1 line-clamp-2">
                                            {{ \Str::limit($email->subject, 40) }}
                                        </h3>
                                        <p class="text-xs text-gray-600 mb-2">
                                            To: {{ \Str::limit($email->to, 30) }}
                                        </p>
                                        <p class="text-xs text-gray-500 line-clamp-3">
                                            {{ \Str::limit(strip_tags($email->body), 80) }}
                                        </p>
                                    </div>

                                    <!-- Footer -->
                                    <div class="flex items-center justify-between pt-3 border-t border-gray-100">
                                        <span class="text-xs text-gray-500">
                                            {{ $email->created_at->format('M d, Y') }}
                                        </span>
                                        <div class="flex items-center space-x-2">
                                            <a href="{{ route('emails.show', $email) }}" 
                                               @click.stop
                                               class="text-blue-600 hover:text-blue-900 text-sm" title="View">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            @if($email->is_draft)
                                                <a href="{{ route('emails.edit', $email) }}" 
                                                   @click.stop
                                                   class="text-green-600 hover:text-green-900 text-sm" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            @endif
                                            <button @click.stop="deleteEmail({{ $email->id }})" 
                                                    class="text-red-600 hover:text-red-900 text-sm" title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <!-- Pagination -->
            @if($emails->hasPages())
                <div class="mt-6">
                    {{ $emails->links() }}
                </div>
            @endif
        </div>

        <!-- Help Modal -->
        <div x-show="showHelp" 
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
             style="display: none;">
            <div x-show="showHelp"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 transform scale-95"
                 x-transition:enter-end="opacity-100 transform scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 transform scale-100"
                 x-transition:leave-end="opacity-0 transform scale-95"
                 class="bg-white rounded-lg p-6 w-full max-w-2xl mx-4 max-h-[80vh] overflow-y-auto">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">Email Management Help</h3>
                    <button @click="showHelp = false" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <div class="space-y-6">
                    <div>
                        <h4 class="font-semibold text-gray-900 mb-2">
                            <i class="fas fa-keyboard text-blue-600 mr-2"></i>Keyboard Shortcuts
                        </h4>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="space-y-2">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Compose Email</span>
                                    <kbd class="px-2 py-1 bg-gray-100 border border-gray-300 rounded text-xs">C</kbd>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">View Drafts</span>
                                    <kbd class="px-2 py-1 bg-gray-100 border border-gray-300 rounded text-xs">D</kbd>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Search Emails</span>
                                    <kbd class="px-2 py-1 bg-gray-100 border border-gray-300 rounded text-xs">S</kbd>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Toggle View</span>
                                    <kbd class="px-2 py-1 bg-gray-100 border border-gray-300 rounded text-xs">V</kbd>
                                </div>
                            </div>
                            <div class="space-y-2">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Select All</span>
                                    <kbd class="px-2 py-1 bg-gray-100 border border-gray-300 rounded text-xs">Ctrl+A</kbd>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Delete Selected</span>
                                    <kbd class="px-2 py-1 bg-gray-100 border border-gray-300 rounded text-xs">Del</kbd>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Show Help</span>
                                    <kbd class="px-2 py-1 bg-gray-100 border border-gray-300 rounded text-xs">H</kbd>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Close Modals</span>
                                    <kbd class="px-2 py-1 bg-gray-100 border border-gray-300 rounded text-xs">Esc</kbd>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div>
                        <h4 class="font-semibold text-gray-900 mb-2">
                            <i class="fas fa-th-large text-green-600 mr-2"></i>View Modes & Selection
                        </h4>
                        <ul class="space-y-2 text-gray-600">
                            <li>• <strong>List View:</strong> Traditional table layout with detailed information</li>
                            <li>• <strong>Grid View:</strong> Card-based layout for visual browsing</li>
                            <li>• <strong>Multi-Selection:</strong> Select multiple emails for bulk operations</li>
                            <li>• <strong>Bulk Actions:</strong> Delete multiple emails or mark as read simultaneously</li>
                            <li>• <strong>Sorting:</strong> Sort emails by date, subject, or recipient</li>
                        </ul>
                    </div>

                    <div>
                        <h4 class="font-semibold text-gray-900 mb-2">
                            <i class="fas fa-envelope text-purple-600 mr-2"></i>Email Features
                        </h4>
                        <ul class="space-y-2 text-gray-600">
                            <li>• <strong>Contact Book Integration:</strong> Select recipients from unified contact book</li>
                            <li>• <strong>CC/BCC Support:</strong> Send emails with carbon copy and blind carbon copy</li>
                            <li>• <strong>Rich Text Editor:</strong> Compose emails with formatting and attachments</li>
                            <li>• <strong>Document Attachments:</strong> Attach invoices and quotations automatically</li>
                            <li>• <strong>Draft Management:</strong> Save and edit email drafts before sending</li>
                            <li>• <strong>Multiple Recipients:</strong> Send to multiple contacts simultaneously</li>
                        </ul>
                    </div>

                    <div>
                        <h4 class="font-semibold text-gray-900 mb-2">
                            <i class="fas fa-lightbulb text-orange-600 mr-2"></i>Tips & Best Practices
                        </h4>
                        <ul class="space-y-2 text-gray-600">
                            <li>• Use grid view for visual browsing and list view for detailed information</li>
                            <li>• Select multiple emails for efficient bulk operations</li>
                            <li>• Use keyboard shortcuts for faster navigation</li>
                            <li>• Leverage contact book integration for quick recipient selection</li>
                            <li>• Always review emails before sending, especially when using bulk operations</li>
                        </ul>
                    </div>
                </div>

                <div class="mt-6 flex justify-end">
                    <a href="{{ route('emails.help') }}"
                        class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                        <i class="fas fa-external-link-alt mr-2"></i>
                        Full Help Documentation
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script>
        function emailManagement() {
            return {
                showHelp: false,
                viewMode: localStorage.getItem('emailViewMode') || 'list', // Initialize from localStorage immediately
                selectedEmails: [],
                selectAll: false,
                sortBy: 'date_desc',

                init() {
                    // Ensure viewMode is properly set from localStorage or default to list
                    const savedViewMode = localStorage.getItem('emailViewMode');
                    if (savedViewMode && (savedViewMode === 'list' || savedViewMode === 'grid')) {
                        this.viewMode = savedViewMode;
                    } else {
                        this.viewMode = 'list';
                        localStorage.setItem('emailViewMode', 'list');
                    }
                    
                    // Keyboard shortcuts
                    document.addEventListener('keydown', (e) => {
                        if (e.target.tagName === 'INPUT' || e.target.tagName === 'TEXTAREA') return;
                        
                        switch(e.key.toLowerCase()) {
                            case 'c':
                                e.preventDefault();
                                window.location.href = "{{ route('emails.create') }}";
                                break;
                            case 'd':
                                e.preventDefault();
                                window.location.href = "{{ route('emails.drafts') }}";
                                break;
                            case 's':
                                e.preventDefault();
                                const searchInput = document.querySelector('input[name="search"]');
                                if (searchInput) {
                                    searchInput.focus();
                                }
                                break;
                            case 'h':
                                e.preventDefault();
                                this.showHelp = true;
                                break;
                            case 'escape':
                                this.showHelp = false;
                                break;
                            case 'v':
                                e.preventDefault();
                                this.toggleViewMode();
                                break;
                            case 'a':
                                if (e.ctrlKey || e.metaKey) {
                                    e.preventDefault();
                                    this.toggleSelectAll();
                                }
                                break;
                            case 'delete':
                                if (this.selectedEmails.length > 0) {
                                    e.preventDefault();
                                    this.deleteSelected();
                                }
                                break;
                        }
                    });

                    // Force Alpine to update the DOM after initialization
                    this.$nextTick(() => {
                        // This ensures the view is properly rendered after initialization
                        console.log('Email Management initialized with view mode:', this.viewMode);
                    });
                },

                // View Mode Functions
                setViewMode(mode) {
                    if (mode === 'list' || mode === 'grid') {
                        this.viewMode = mode;
                        localStorage.setItem('emailViewMode', mode);
                    }
                },

                toggleViewMode() {
                    const newMode = this.viewMode === 'list' ? 'grid' : 'list';
                    this.setViewMode(newMode);
                },

                // Selection Functions
                toggleSelectAll() {
                    if (this.selectAll) {
                        this.selectedEmails = @json($emails->pluck('id')->toArray());
                    } else {
                        this.selectedEmails = [];
                    }
                },

                toggleEmailSelection(emailId) {
                    const index = this.selectedEmails.indexOf(emailId);
                    if (index > -1) {
                        this.selectedEmails.splice(index, 1);
                    } else {
                        this.selectedEmails.push(emailId);
                    }
                    
                    // Update select all checkbox
                    const totalEmails = @json($emails->count());
                    this.selectAll = this.selectedEmails.length === totalEmails;
                },

                // Action Functions
                async deleteSelected() {
                    if (this.selectedEmails.length === 0) return;
                    
                    if (!confirm(`Are you sure you want to delete ${this.selectedEmails.length} selected email(s)?`)) {
                        return;
                    }

                    try {
                        const response = await fetch('{{ route("emails.bulk-delete") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify({
                                email_ids: this.selectedEmails
                            })
                        });

                        if (response.ok) {
                            window.location.reload();
                        } else {
                            alert('Error deleting emails. Please try again.');
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        alert('Network error. Please try again.');
                    }
                },

                async markAsRead() {
                    if (this.selectedEmails.length === 0) return;

                    try {
                        const response = await fetch('{{ route("emails.mark-read") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify({
                                email_ids: this.selectedEmails
                            })
                        });

                        if (response.ok) {
                            // Update UI or reload
                            this.selectedEmails = [];
                            this.selectAll = false;
                        } else {
                            alert('Error marking emails as read. Please try again.');
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        alert('Network error. Please try again.');
                    }
                },

                deleteEmail(emailId) {
                    if (!confirm('Are you sure you want to delete this email?')) return;
                    
                    // Create and submit form
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `/ims/emails/${emailId}`;
                    
                    const csrfInput = document.createElement('input');
                    csrfInput.type = 'hidden';
                    csrfInput.name = '_token';
                    csrfInput.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                    
                    const methodInput = document.createElement('input');
                    methodInput.type = 'hidden';
                    methodInput.name = '_method';
                    methodInput.value = 'DELETE';
                    
                    form.appendChild(csrfInput);
                    form.appendChild(methodInput);
                    document.body.appendChild(form);
                    form.submit();
                },

                applySorting() {
                    // This would typically be handled by a server request
                    // For now, we'll just reload with the sort parameter
                    const url = new URL(window.location);
                    url.searchParams.set('sort', this.sortBy);
                    window.location.href = url.toString();
                }
            }
        }

        // Additional function to handle table sorting if needed
        function sortTable(columnIndex) {
            const table = document.getElementById('emailTable');
            if (!table) return;
            
            const rows = Array.from(table.rows);
            const isNumeric = columnIndex === 0;
            const isDate = columnIndex === 3;
            
            rows.sort((a, b) => {
                let aVal = a.cells[columnIndex].textContent.trim();
                let bVal = b.cells[columnIndex].textContent.trim();
                
                if (isNumeric) {
                    return parseInt(aVal) - parseInt(bVal);
                } else if (isDate) {
                    return new Date(aVal) - new Date(bVal);
                } else {
                    return aVal.localeCompare(bVal);
                }
            });
            
            rows.forEach(row => table.appendChild(row));
        }
    </script>
    
    <style>
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        
        .line-clamp-3 {
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        
        /* Grid card hover effects */
        .grid-card {
            transition: all 0.2s ease-in-out;
        }
        
        .grid-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        
        /* Selection animations */
        .selected-item {
            animation: selectPulse 0.3s ease-out;
        }
        
        @keyframes selectPulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.02); }
            100% { transform: scale(1); }
        }
        
        /* View mode toggle styles */
        .view-toggle button {
            transition: all 0.2s ease;
        }
        
        .view-toggle button:hover {
            background-color: #f3f4f6;
        }
        
        /* Enhanced table row hover */
        tbody tr:hover {
            background-color: #f8fafc;
            border-left: 4px solid #3b82f6;
        }
        
        /* Smooth transitions for view changes */
        .transition-opacity {
            transition-property: opacity;
            transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        /* Responsive grid adjustments */
        @media (max-width: 768px) {
            .xl\:grid-cols-4 {
                grid-template-columns: repeat(1, minmax(0, 1fr));
            }
        }
        
        @media (min-width: 769px) and (max-width: 1024px) {
            .xl\:grid-cols-4 {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }
        
        @media (min-width: 1025px) and (max-width: 1280px) {
            .xl\:grid-cols-4 {
                grid-template-columns: repeat(3, minmax(0, 1fr));
            }
        }
        
        /* Improved button states */
        .bg-white.shadow-sm {
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        }
        
        /* Active view button styling */
        button:focus {
            outline: 2px solid transparent;
            outline-offset: 2px;
        }
        
        button:focus-visible {
            outline: 2px solid #3b82f6;
            outline-offset: 2px;
        }
    </style>
</x-app-layout>e