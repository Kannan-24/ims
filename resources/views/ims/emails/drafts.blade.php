<x-app-layout>
    <x-slot name="title">
        {{ __('Email Drafts') }} - {{ config('app.name', 'IMS') }}
    </x-slot>

    <div class="bg-white min-h-screen" x-data="draftManagement()">
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
                            <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                            <a href="{{ route('emails.index') }}"
                                class="text-sm font-medium text-gray-700 hover:text-blue-600">
                                Emails
                            </a>
                        </div>
                    </li>
                    <li aria-current="page">
                        <div class="flex items-center">
                            <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                            <span class="text-sm font-medium text-gray-500">Email Drafts</span>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>

        <!-- Header -->
        <div class="px-6 py-6 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-edit text-orange-600 text-xl"></i>
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">Email Drafts</h1>
                        <p class="text-lg text-gray-600 mt-2">Manage your saved email drafts</p>
                    </div>
                </div>
                <div class="flex items-center space-x-3">
                    <!-- Help Button -->
                    <button @click="showHelp = true"
                        class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition-colors">
                        <i class="fas fa-question-circle w-4 h-4 mr-2"></i>
                        Help
                    </button>
                    <!-- Back to Emails -->
                    <a href="{{ route('emails.index') }}"
                        class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-lg transition-colors">
                        <i class="fas fa-arrow-left w-4 h-4 mr-2"></i>
                        All Emails
                    </a>
                    <!-- Compose Button -->
                    <a href="{{ route('emails.create') }}"
                        class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                        <i class="fas fa-plus w-4 h-4 mr-2"></i>
                        Compose New Email
                    </a>
                </div>
            </div>
        </div>

        <!-- Success Message -->
        @if (session('success'))
            <div class="mx-6 mt-6">
                <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-check-circle text-green-400"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Statistics Cards -->
        <div class="px-6 py-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Total Drafts -->
                <div class="bg-white border border-gray-200 rounded-lg p-6 shadow-sm">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-edit text-orange-600 text-xl"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Total Drafts</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $emails->count() }}</p>
                        </div>
                    </div>
                </div>

                <!-- With Attachments -->
                <div class="bg-white border border-gray-200 rounded-lg p-6 shadow-sm">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-paperclip text-blue-600 text-xl"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">With Attachments</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $emails->filter(function($email) { return $email->attachments && count(json_decode($email->attachments, true)) > 0; })->count() }}</p>
                        </div>
                    </div>
                </div>

                <!-- Recent Drafts -->
                <div class="bg-white border border-gray-200 rounded-lg p-6 shadow-sm">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-clock text-green-600 text-xl"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Recent (Today)</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $emails->where('created_at', '>=', now()->startOfDay())->count() }}</p>
                        </div>
                    </div>
                </div>

                <!-- Ready to Send -->
                <div class="bg-white border border-gray-200 rounded-lg p-6 shadow-sm">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-paper-plane text-purple-600 text-xl"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Ready to Send</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $emails->filter(function($email) { return !empty($email->to) && !empty($email->subject) && !empty($email->body); })->count() }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Search Bar -->
            <div class="bg-white border border-gray-200 rounded-lg p-4 mb-6 shadow-sm">
                <div class="flex flex-col sm:flex-row gap-4">
                    <div class="flex-1">
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-search text-gray-400"></i>
                            </div>
                            <input type="text" 
                                x-model="searchTerm"
                                placeholder="Search drafts by subject, recipient, or content..."
                                class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Drafts Table -->
            <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    @if ($emails->isEmpty())
                        <div class="text-center py-12">
                            <div class="w-24 h-24 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-edit text-orange-400 text-3xl"></i>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">No draft emails found</h3>
                            <p class="text-gray-600 mb-4">Create your first draft to get started with email composition.</p>
                            <a href="{{ route('emails.create') }}"
                                class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                                <i class="fas fa-plus mr-2"></i>
                                Create Your First Draft
                            </a>
                        </div>
                    @else
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        #
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Recipients
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Subject
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Created
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Attachments
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
                                        x-show="draftMatchesSearch({{ json_encode([
                                            'to' => $email->to ?? '',
                                            'subject' => $email->subject ?? '',
                                            'body' => strip_tags($email->body ?? '')
                                        ]) }})">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $loop->iteration }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($email->to)
                                                <div class="flex items-center">
                                                    <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                                        <i class="fas fa-user text-blue-600 text-sm"></i>
                                                    </div>
                                                    <span class="text-sm text-gray-900">{{ $email->to }}</span>
                                                </div>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                    <i class="fas fa-exclamation-triangle mr-1"></i>
                                                    No recipient
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $email->subject ?: 'No Subject' }}
                                            </div>
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
                                            @if ($email->attachments && count(json_decode($email->attachments, true)) > 0)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                    <i class="fas fa-paperclip mr-1"></i>
                                                    {{ count(json_decode($email->attachments, true)) }} file(s)
                                                </span>
                                            @else
                                                <span class="text-gray-400 text-sm">No attachments</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if(!empty($email->to) && !empty($email->subject) && !empty($email->body))
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    <i class="fas fa-check mr-1"></i>
                                                    Ready to Send
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                                    <i class="fas fa-clock mr-1"></i>
                                                    Incomplete
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            <div class="flex justify-center space-x-2">
                                                <a href="{{ route('emails.edit', $email) }}"
                                                    class="text-green-600 hover:text-green-900 transition-colors" title="Edit Draft">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <a href="{{ route('emails.show', $email) }}"
                                                    class="text-blue-600 hover:text-blue-900 transition-colors" title="Preview">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <form action="{{ route('emails.destroy', $email) }}" method="POST" class="inline">
                                                    @csrf @method('DELETE')
                                                    <button type="submit"
                                                        class="text-red-600 hover:text-red-900 transition-colors" title="Delete Draft"
                                                        onclick="return confirm('Are you sure you want to delete this draft?')">
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
                    <h3 class="text-lg font-semibold text-gray-900">Email Drafts Help</h3>
                    <button @click="showHelp = false" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <div class="space-y-6">
                    <div>
                        <h4 class="font-semibold text-gray-900 mb-2">Draft Status Indicators</h4>
                        <div class="space-y-2">
                            <div class="flex items-center">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 mr-3">
                                    Ready to Send
                                </span>
                                <span class="text-gray-600">Draft has recipient, subject, and content</span>
                            </div>
                            <div class="flex items-center">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800 mr-3">
                                    Incomplete
                                </span>
                                <span class="text-gray-600">Missing recipient, subject, or content</span>
                            </div>
                        </div>
                    </div>

                    <div>
                        <h4 class="font-semibold text-gray-900 mb-2">Managing Drafts</h4>
                        <ul class="space-y-2 text-gray-600">
                            <li>• <strong>Edit:</strong> Continue working on your draft</li>
                            <li>• <strong>Preview:</strong> View how your email will look</li>
                            <li>• <strong>Delete:</strong> Permanently remove unwanted drafts</li>
                            <li>• <strong>Search:</strong> Find specific drafts quickly</li>
                        </ul>
                    </div>

                    <div>
                        <h4 class="font-semibold text-gray-900 mb-2">Tips for Better Drafts</h4>
                        <ul class="space-y-2 text-gray-600">
                            <li>• Save drafts frequently while composing</li>
                            <li>• Use descriptive subjects for easy identification</li>
                            <li>• Complete all required fields before sending</li>
                            <li>• Review attachments before finalizing</li>
                        </ul>
                    </div>
                </div>

                <div class="mt-6 flex justify-end">
                    <a href="{{ route('emails.help') }}"
                        class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                        <i class="fas fa-external-link-alt mr-2"></i>
                        Full Documentation
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script>
        function draftManagement() {
            return {
                searchTerm: '',
                showHelp: false,

                draftMatchesSearch(draft) {
                    if (!this.searchTerm) return true;
                    
                    const searchLower = this.searchTerm.toLowerCase();
                    return draft.to.toLowerCase().includes(searchLower) ||
                           draft.subject.toLowerCase().includes(searchLower) ||
                           draft.body.toLowerCase().includes(searchLower);
                },

                init() {
                    // Keyboard shortcuts
                    document.addEventListener('keydown', (e) => {
                        if (e.target.tagName === 'INPUT' || e.target.tagName === 'TEXTAREA') return;
                        
                        switch(e.key.toLowerCase()) {
                            case 'c':
                                e.preventDefault();
                                window.location.href = "{{ route('emails.create') }}";
                                break;
                            case 's':
                                e.preventDefault();
                                document.querySelector('input[x-model="searchTerm"]').focus();
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
    </script>
</x-app-layout>
