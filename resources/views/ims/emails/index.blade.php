<x-app-layout>
    <x-slot name="title">
        {{ __('Email Management') }} - {{ config('app.name', 'IMS') }}
    </x-slot>

    <div class="bg-white min-h-screen" x-data="emailManagement()">
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
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Total Emails -->
                <div class="bg-white border border-gray-200 rounded-lg p-6 shadow-sm">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-envelope text-blue-600 text-xl"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Total Emails</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $emails->count() }}</p>
                        </div>
                    </div>
                </div>

                <!-- Draft Emails -->
                <div class="bg-white border border-gray-200 rounded-lg p-6 shadow-sm">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-edit text-orange-600 text-xl"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Draft Emails</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $drafts }}</p>
                        </div>
                    </div>
                </div>

                <!-- Today's Emails -->
                <div class="bg-white border border-gray-200 rounded-lg p-6 shadow-sm">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-paper-plane text-green-600 text-xl"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Today's Emails</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $emails->where('created_at', '>=', now()->startOfDay())->count() }}</p>
                        </div>
                    </div>
                </div>

                <!-- Unique Recipients -->
                <div class="bg-white border border-gray-200 rounded-lg p-6 shadow-sm">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-users text-purple-600 text-xl"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Unique Recipients</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $emails->pluck('to')->unique()->count() }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Search and Filter Bar -->
            <div class="bg-white border border-gray-200 rounded-lg p-4 mb-6 shadow-sm">
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

            <!-- Email Table -->
            <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
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
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100" onclick="sortTable(0)">
                                        <span class="flex items-center">
                                            #
                                            <i class="fas fa-sort ml-1 text-gray-400"></i>
                                        </span>
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100" onclick="sortTable(1)">
                                        <span class="flex items-center">
                                            Recipients
                                            <i class="fas fa-sort ml-1 text-gray-400"></i>
                                        </span>
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100" onclick="sortTable(2)">
                                        <span class="flex items-center">
                                            Subject
                                            <i class="fas fa-sort ml-1 text-gray-400"></i>
                                        </span>
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100" onclick="sortTable(3)">
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
                            <tbody class="bg-white divide-y divide-gray-200" id="emailTable">
                                @foreach ($emails as $email)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $loop->iteration }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if ($email->to)
                                                @php
                                                    $recipients = explode(',', $email->to);
                                                @endphp
                                                <div class="space-y-1">
                                                    @foreach (array_slice($recipients, 0, 2) as $index => $recipient)
                                                        <div class="flex items-center">
                                                            <div class="w-6 h-6 bg-blue-100 rounded-full flex items-center justify-center mr-2">
                                                                <i class="fas fa-user text-blue-600 text-xs"></i>
                                                            </div>
                                                            <span class="text-sm text-gray-900">{{ trim($recipient) }}</span>
                                                        </div>
                                                    @endforeach
                                                    @if(count($recipients) > 2)
                                                        <div class="text-xs text-gray-500">
                                                            +{{ count($recipients) - 2 }} more
                                                        </div>
                                                    @endif
                                                </div>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                    No recipient
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $email->subject ?: 'No Subject' }}</div>
                                            @if($email->body)
                                                <div class="text-sm text-gray-500 truncate max-w-xs">
                                                    {{ Str::limit(strip_tags($email->body), 50) }}
                                                </div>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            <div class="flex items-center">
                                                <i class="fas fa-calendar text-gray-400 mr-2"></i>
                                                {{ $email->created_at->format('M d, Y') }}
                                            </div>
                                            <div class="text-xs text-gray-500">
                                                {{ $email->created_at->format('g:i A') }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($email->is_draft)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                                    <i class="fas fa-edit mr-1"></i>
                                                    Draft
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    <i class="fas fa-check mr-1"></i>
                                                    Sent
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            <div class="flex justify-center space-x-2">
                                                <a href="{{ route('emails.show', $email) }}"
                                                    class="text-blue-600 hover:text-blue-900 transition-colors" title="View Email">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                @if($email->is_draft)
                                                    <a href="{{ route('emails.edit', $email) }}"
                                                        class="text-green-600 hover:text-green-900 transition-colors" title="Edit Draft">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                @endif
                                                <form action="{{ route('emails.destroy', $email) }}" method="POST" class="inline">
                                                    @csrf @method('DELETE')
                                                    <button type="submit"
                                                        class="text-red-600 hover:text-red-900 transition-colors" title="Delete Email"
                                                        onclick="return confirm('Are you sure you want to delete this email?')">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
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
                        <h4 class="font-semibold text-gray-900 mb-2">Quick Actions</h4>
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
                            </div>
                            <div class="space-y-2">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Search Emails</span>
                                    <kbd class="px-2 py-1 bg-gray-100 border border-gray-300 rounded text-xs">S</kbd>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Show Help</span>
                                    <kbd class="px-2 py-1 bg-gray-100 border border-gray-300 rounded text-xs">H</kbd>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div>
                        <h4 class="font-semibold text-gray-900 mb-2">Email Features</h4>
                        <ul class="space-y-2 text-gray-600">
                            <li>• <strong>Rich Text Editor:</strong> Compose emails with formatting, images, and attachments</li>
                            <li>• <strong>AI Assistant:</strong> Generate email content using AI for invoices and quotations</li>
                            <li>• <strong>Draft Management:</strong> Save and edit email drafts before sending</li>
                            <li>• <strong>Multiple Recipients:</strong> Send emails to multiple recipients with CC and BCC</li>
                        </ul>
                    </div>

                    <div>
                        <h4 class="font-semibold text-gray-900 mb-2">Tips & Best Practices</h4>
                        <ul class="space-y-2 text-gray-600">
                            <li>• Always review your emails before sending</li>
                            <li>• Use the AI assistant for professional email templates</li>
                            <li>• Save drafts for emails you're not ready to send</li>
                            <li>• Include relevant attachments for business communications</li>
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

                init() {
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
                                document.querySelector('input[name="search"]').focus();
                                break;
                            case 'h':
                                e.preventDefault();
                                this.showHelp = true;
                                break;
                            case 'escape':
                                this.showHelp = false;
                                break;
                        }
                    });
                }
            }
        }

        function sortTable(columnIndex) {
            const table = document.getElementById('emailTable');
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
</x-app-layout>
