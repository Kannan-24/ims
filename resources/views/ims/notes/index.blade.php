<x-app-layout>
    <x-slot name="title">
        📝 Notes - {{ config('app.name', 'SKM') }}
    </x-slot>

    <div class="bg-white min-h-screen" x-data="notesApp()">
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
                            <span class="text-sm font-medium text-gray-500">📝 Notes</span>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>

        <!-- Header -->
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 flex items-center">
                        <span class="text-2xl mr-2">📝</span>
                        Notes
                    </h1>
                    <p class="text-sm text-gray-600 mt-1">Create and manage your personal notes</p>
                </div>
                <div class="flex items-center space-x-3">
                    <a href="{{ route('notes.create') }}"
                        class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors"
                        title="Create New Note (Ctrl+N)">
                        <i class="fas fa-plus w-4 h-4 mr-2"></i>
                        New Note
                    </a>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="p-6">

            <!-- Search and Filters -->
            <div class="bg-white border border-gray-200 rounded-lg p-6 mb-6">
                <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4">
                    <!-- Left Side: Search -->
                    <div class="flex-1 max-w-md">
                        <form method="GET" action="{{ route('notes.index') }}" class="relative">
                            <input type="text" name="search" value="{{ request('search') }}"
                                placeholder="Search notes... (Ctrl+F)"
                                class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                x-ref="searchInput">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-search text-gray-400"></i>
                            </div>
                            <button type="submit" class="sr-only">Search</button>
                        </form>
                    </div>

                    <!-- Center: Filter Buttons -->
                    <div class="flex items-center space-x-3">
                        <a href="{{ route('notes.index', ['pinned' => 'true']) }}"
                            class="inline-flex items-center px-4 py-2 {{ request('pinned') === 'true' ? 'bg-orange-100 text-orange-800 border-orange-300' : 'bg-gray-100 text-gray-700 border-gray-300' }} border rounded-lg hover:bg-orange-200 transition-colors">
                            <span class="mr-2">📌</span>
                            Pinned Only
                        </a>
                        <a href="{{ route('notes.index') }}"
                            class="inline-flex items-center px-4 py-2 {{ !request()->hasAny(['search', 'pinned']) ? 'bg-blue-100 text-blue-800 border-blue-300' : 'bg-gray-100 text-gray-700 border-gray-300' }} border rounded-lg hover:bg-blue-200 transition-colors">
                            <span class="mr-2">📋</span>
                            All Notes
                        </a>
                    </div>

                    <!-- Right Side: View Mode Toggle -->
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
                    </div>
                </div>
            </div>

            <!-- Notes Display -->
            @if ($notes->count() > 0)
                <!-- Grid View -->
                <div x-show="viewMode === 'grid'" x-transition:enter="transition-opacity duration-200"
                    x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                    class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    @foreach ($notes as $note)
                        <div class="bg-white border border-gray-200 rounded-lg shadow-sm hover:shadow-md transition-all duration-200 grid-card"
                            x-data="{ isLoading: false }">
                            <!-- Note Header -->
                            <div class="p-4 border-b border-gray-200">
                                <div class="flex items-center justify-between">
                                    <h3 class="text-lg font-semibold text-gray-900 truncate flex-1 mr-2">
                                        {{ $note->title }}
                                    </h3>
                                    <div class="flex items-center space-x-2">
                                        @if ($note->is_pinned)
                                            <span class="text-orange-500" title="Pinned">📌</span>
                                        @endif
                                        <div class="relative" x-data="{ open: false }">
                                            <button @click="open = !open"
                                                class="p-1 text-gray-400 hover:text-gray-600 rounded-full hover:bg-gray-100">
                                                <i class="fas fa-ellipsis-v"></i>
                                            </button>
                                            <div x-show="open" x-transition @click.away="open = false"
                                                class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 z-10">
                                                <div class="py-1">
                                                    <a href="{{ route('notes.show', $note->id) }}"
                                                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                        <i class="fas fa-eye mr-2"></i>View
                                                    </a>
                                                    <a href="{{ route('notes.edit', $note->id) }}"
                                                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                        <i class="fas fa-edit mr-2"></i>Edit
                                                    </a>
                                                    <button
                                                        @click="togglePin('{{ $note->id }}', {{ $note->is_pinned ? 'false' : 'true' }})"
                                                        class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                                                        :disabled="isLoading">
                                                        <i
                                                            class="fas fa-thumbtack mr-2"></i>{{ $note->is_pinned ? 'Unpin' : 'Pin' }}
                                                    </button>
                                                    <div class="border-t border-gray-100"></div>
                                                    <form action="{{ route('notes.destroy', $note->id) }}"
                                                        method="POST"
                                                        onsubmit="return confirm('Are you sure you want to delete this note?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                            class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                                                            <i class="fas fa-trash mr-2"></i>Delete
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <p class="text-xs text-gray-500 mt-1">
                                    Updated {{ $note->updated_at->diffForHumans() }}
                                </p>
                            </div>

                            <!-- Note Content -->
                            <div class="p-4">
                                <div class="text-sm text-gray-700 line-clamp-4">
                                    @if ($note->content)
                                        {!! nl2br(e(Str::limit($note->content, 150))) !!}
                                    @else
                                        <em class="text-gray-400">No content</em>
                                    @endif
                                </div>
                            </div>

                            <!-- Note Footer -->
                            <div class="px-4 py-3 bg-gray-50 border-t border-gray-200 rounded-b-lg">
                                <div class="flex items-center justify-between">
                                    <span class="text-xs text-gray-500">
                                        Created {{ $note->created_at->format('M j, Y') }}
                                    </span>
                                    <a href="{{ route('notes.show', $note->id) }}"
                                        class="text-xs text-blue-600 hover:text-blue-800 font-medium">
                                        Read more →
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- List View -->
                <div x-show="viewMode === 'list'" x-transition:enter="transition-opacity duration-200"
                    x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                    class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Note
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden lg:table-cell">
                                    Content Preview
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Date
                                </th>
                                <th
                                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($notes as $note)
                                <tr class="hover:bg-gray-50 transition-colors" x-data="{ isLoading: false }">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            <div>
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ \Str::limit($note->title, 50) }}
                                                </div>
                                                <div class="text-xs text-gray-500">
                                                    Updated {{ $note->updated_at->diffForHumans() }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 hidden lg:table-cell">
                                        <div class="text-sm text-gray-700 line-clamp-2">
                                            @if ($note->content)
                                                {{ \Str::limit(strip_tags($note->content), 100) }}
                                            @else
                                                <em class="text-gray-400">No content</em>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center space-x-2">
                                            @if ($note->is_pinned)
                                                <span
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                                    <span class="mr-1">📌</span>Pinned
                                                </span>
                                            @else
                                                <span
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                    <span class="mr-1">📝</span>Note
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <div class="flex flex-col">
                                            <span>{{ $note->created_at->format('M d, Y') }}</span>
                                            <span
                                                class="text-xs text-gray-500">{{ $note->created_at->format('h:i A') }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                        <div class="flex items-center justify-center space-x-2">
                                            <a href="{{ route('notes.show', $note->id) }}"
                                                class="text-blue-600 hover:text-blue-900" title="View Note">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('notes.edit', $note->id) }}"
                                                class="text-green-600 hover:text-green-900" title="Edit Note">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button
                                                @click="togglePin('{{ $note->id }}', {{ $note->is_pinned ? 'false' : 'true' }})"
                                                class="text-orange-600 hover:text-orange-900"
                                                title="{{ $note->is_pinned ? 'Unpin' : 'Pin' }} Note"
                                                :disabled="isLoading">
                                                <i class="fas fa-thumbtack"></i>
                                            </button>
                                            <form action="{{ route('notes.destroy', $note->id) }}" method="POST"
                                                onsubmit="return confirm('Are you sure you want to delete this note?')"
                                                class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900"
                                                    title="Delete Note">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-8">
                    {{ $notes->appends(request()->query())->links() }}
                </div>
            @else
                <!-- Empty State -->
                <div class="text-center py-12">
                    <div class="mx-auto h-24 w-24 text-gray-400 mb-4">
                        <span class="text-6xl">📝</span>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No notes found</h3>
                    <p class="text-gray-500 mb-6">
                        @if (request()->hasAny(['search', 'pinned']))
                            Try adjusting your search criteria or
                            <a href="{{ route('notes.index') }}" class="text-blue-600 hover:text-blue-800">view all
                                notes</a>.
                        @else
                            Get started by creating your first note.
                        @endif
                    </p>
                    <a href="{{ route('notes.create') }}"
                        class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                        <i class="fas fa-plus w-4 h-4 mr-2"></i>
                        Create Your First Note
                    </a>
                </div>
            @endif
        </div>

        <!-- Toast Notifications -->
        <div id="toast-container" class="fixed top-4 right-4 z-50"></div>
    </div>

    <!-- Scripts -->
    <script>
        function notesApp() {
            return {
                viewMode: localStorage.getItem('notesViewMode') || 'grid', // Initialize from localStorage immediately

                init() {
                    // Ensure viewMode is properly set from localStorage or default to grid
                    const savedViewMode = localStorage.getItem('notesViewMode');
                    if (savedViewMode && (savedViewMode === 'list' || savedViewMode === 'grid')) {
                        this.viewMode = savedViewMode;
                    } else {
                        this.viewMode = 'grid';
                        localStorage.setItem('notesViewMode', 'grid');
                    }

                    // Hotkey bindings
                    document.addEventListener('keydown', (e) => {
                        if (e.target.tagName === 'INPUT' || e.target.tagName === 'TEXTAREA') return;

                        switch (e.key.toLowerCase()) {
                            case 'n':
                                if (e.ctrlKey) {
                                    e.preventDefault();
                                    window.location.href = '{{ route('notes.create') }}';
                                }
                                break;
                            case 'f':
                                if (e.ctrlKey) {
                                    e.preventDefault();
                                    this.$refs.searchInput?.focus();
                                }
                                break;
                            case 'v':
                                e.preventDefault();
                                this.toggleViewMode();
                                break;
                            case 'g':
                                e.preventDefault();
                                this.setViewMode('grid');
                                break;
                            case 'l':
                                e.preventDefault();
                                this.setViewMode('list');
                                break;
                        }
                    });

                    // Force Alpine to update the DOM after initialization
                    this.$nextTick(() => {
                        console.log('Notes app initialized with view mode:', this.viewMode);
                    });
                },

                // View Mode Functions
                setViewMode(mode) {
                    if (mode === 'list' || mode === 'grid') {
                        this.viewMode = mode;
                        localStorage.setItem('notesViewMode', mode);
                    }
                },

                toggleViewMode() {
                    const newMode = this.viewMode === 'list' ? 'grid' : 'list';
                    this.setViewMode(newMode);
                },

                async togglePin(noteId, isPinned) {
                    this.isLoading = true;

                    try {
                        const response = await fetch(`/ims/notes/${noteId}/toggle-pin`, {
                            method: 'PATCH',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                    'content')
                            }
                        });

                        const data = await response.json();

                        if (data.success) {
                            this.showToast('success', data.message);
                            // Refresh the page to update the UI
                            setTimeout(() => {
                                window.location.reload();
                            }, 1000);
                        } else {
                            this.showToast('error', 'Failed to update note');
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        this.showToast('error', 'An error occurred');
                    } finally {
                        this.isLoading = false;
                    }
                },

                showToast(type, message) {
                    const toast = document.createElement('div');
                    toast.className = `mb-4 p-4 rounded-lg shadow-lg transition-all duration-300 transform translate-x-0 ${
                        type === 'success' ? 'bg-green-100 border border-green-200 text-green-800' :
                        type === 'error' ? 'bg-red-100 border border-red-200 text-red-800' :
                        'bg-blue-100 border border-blue-200 text-blue-800'
                    }`;

                    toast.innerHTML = `
                        <div class="flex items-center">
                            <i class="fas ${type === 'success' ? 'fa-check-circle' : type === 'error' ? 'fa-exclamation-circle' : 'fa-info-circle'} mr-3"></i>
                            <span>${message}</span>
                            <button onclick="this.parentElement.parentElement.remove()" class="ml-auto text-lg hover:opacity-70">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    `;

                    document.getElementById('toast-container').appendChild(toast);

                    setTimeout(() => {
                        if (toast.parentNode) {
                            toast.remove();
                        }
                    }, 5000);
                }
            }
        }
    </script>

    <style>
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .line-clamp-4 {
            display: -webkit-box;
            -webkit-line-clamp: 4;
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
</x-app-layout>
