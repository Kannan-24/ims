<x-app-layout>
    <x-slot name="title">
        {{ $note->title }} - {{ config('app.name', 'SKM') }}
    </x-slot>

    <div class="bg-white min-h-screen" x-data="showNoteApp()">
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
                            <a href="{{ route('notes.index') }}"
                               class="text-sm font-medium text-gray-700 hover:text-blue-600">
                                📝 Notes
                            </a>
                        </div>
                    </li>
                    <li aria-current="page">
                        <div class="flex items-center">
                            <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                            <span class="text-sm font-medium text-gray-500">{{ Str::limit($note->title, 30) }}</span>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>

        <!-- Header -->
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div class="flex-1 mr-4">
                    <div class="flex items-center">
                        <h1 class="text-2xl font-bold text-gray-900 flex items-center">
                            <span class="text-2xl mr-2">📄</span>
                            {{ $note->title }}
                        </h1>
                        @if($note->is_pinned)
                            <span class="ml-3 text-orange-500" title="Pinned Note">📌</span>
                        @endif
                    </div>
                    <div class="flex items-center mt-2 text-sm text-gray-600 space-x-4">
                        <span>
                            <i class="fas fa-user mr-1"></i>
                            {{ $note->creator->name }}
                        </span>
                        <span>
                            <i class="fas fa-calendar mr-1"></i>
                            Created {{ $note->created_at->format('M j, Y \a\t g:i A') }}
                        </span>
                        @if($note->updated_at->ne($note->created_at))
                            <span>
                                <i class="fas fa-edit mr-1"></i>
                                Updated {{ $note->updated_at->diffForHumans() }}
                            </span>
                        @endif
                    </div>
                </div>
                <div class="flex items-center space-x-3">
                    <button @click="togglePin()"
                            class="inline-flex items-center px-4 py-2 {{ $note->is_pinned ? 'bg-orange-600 hover:bg-orange-700' : 'bg-gray-600 hover:bg-gray-700' }} text-white text-sm font-medium rounded-lg transition-colors"
                            :disabled="isLoading">
                        <span class="w-4 h-4 mr-2">{{ $note->is_pinned ? '📌' : '📍' }}</span>
                        {{ $note->is_pinned ? 'Unpin' : 'Pin' }}
                    </button>
                    <a href="{{ route('notes.edit', $note->id) }}" 
                       class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                        <i class="fas fa-edit w-4 h-4 mr-2"></i>
                        Edit
                    </a>
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" 
                                class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-lg transition-colors">
                            <i class="fas fa-ellipsis-v w-4 h-4"></i>
                        </button>
                        <div x-show="open" 
                             x-transition
                             @click.away="open = false"
                             class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 z-10">
                            <div class="py-1">
                                <a href="{{ route('notes.index') }}" 
                                   class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <i class="fas fa-arrow-left mr-2"></i>Back to Notes
                                </a>
                                <a href="{{ route('notes.create') }}" 
                                   class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <i class="fas fa-plus mr-2"></i>New Note
                                </a>
                                <div class="border-t border-gray-100"></div>
                                <form action="{{ route('notes.destroy', $note->id) }}" 
                                      method="POST" 
                                      onsubmit="return confirm('Are you sure you want to delete this note?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                                        <i class="fas fa-trash mr-2"></i>Delete Note
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="p-6">
            <div class="max-w-4xl mx-auto">
                <!-- Note Content -->
                <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden">
                    <!-- Card Header -->
                    <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                            <span class="text-xl mr-2">📋</span>
                            Note Content
                        </h3>
                    </div>

                    <!-- Card Body -->
                    <div class="p-6">
                        @if($note->content)
                            <div class="prose prose-gray max-w-none">
                                <div class="text-gray-700 leading-relaxed whitespace-pre-wrap" x-html="formatContent('{{ addslashes($note->content) }}')">
                                    {{ $note->content }}
                                </div>
                            </div>
                        @else
                            <div class="text-center py-8">
                                <div class="text-gray-400 text-4xl mb-4">📝</div>
                                <p class="text-gray-500">This note has no content yet.</p>
                                <a href="{{ route('notes.edit', $note->id) }}" 
                                   class="mt-4 inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                                    <i class="fas fa-edit w-4 h-4 mr-2"></i>
                                    Add Content
                                </a>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Note Actions -->
                <div class="mt-6 bg-gray-50 border border-gray-200 rounded-lg p-4">
                    <div class="flex items-center justify-between">
                        <div class="text-sm text-gray-600">
                            <span class="mr-1">💡</span>
                            Want to make changes? You can edit this note anytime.
                        </div>
                        <div class="flex items-center space-x-3">
                            <a href="{{ route('notes.edit', $note->id) }}" 
                               class="inline-flex items-center px-3 py-1 bg-blue-100 hover:bg-blue-200 text-blue-700 text-sm rounded-lg transition-colors">
                                <i class="fas fa-edit w-3 h-3 mr-1"></i>
                                Edit
                            </a>
                            <button @click="copyToClipboard()"
                                    class="inline-flex items-center px-3 py-1 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm rounded-lg transition-colors">
                                <i class="fas fa-copy w-3 h-3 mr-1"></i>
                                Copy
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Keyboard Shortcuts Help -->
                <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <h4 class="text-sm font-semibold text-blue-800 mb-2">
                        <span class="mr-1">⌨️</span>
                        Keyboard Shortcuts
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-2 text-xs text-blue-700">
                        <div><kbd class="bg-blue-100 px-2 py-1 rounded">E</kbd> Edit this note</div>
                        <div><kbd class="bg-blue-100 px-2 py-1 rounded">Ctrl+N</kbd> Create new note</div>
                        <div><kbd class="bg-blue-100 px-2 py-1 rounded">Ctrl+C</kbd> Copy content</div>
                        <div><kbd class="bg-blue-100 px-2 py-1 rounded">Esc</kbd> Back to notes list</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Toast Notifications -->
        <div id="toast-container" class="fixed top-4 right-4 z-50"></div>
    </div>

    <!-- Scripts -->
    <script>
        function showNoteApp() {
            return {
                isLoading: false,

                init() {
                    // Hotkey bindings
                    document.addEventListener('keydown', (e) => {
                        // E - Edit Note
                        if (e.key === 'e' && !e.ctrlKey && !e.altKey && document.activeElement.tagName !== 'INPUT' && document.activeElement.tagName !== 'TEXTAREA') {
                            e.preventDefault();
                            window.location.href = '{{ route("notes.edit", $note->id) }}';
                        }
                        
                        // Ctrl+N - New Note
                        if (e.ctrlKey && e.key === 'n') {
                            e.preventDefault();
                            window.location.href = '{{ route("notes.create") }}';
                        }
                        
                        // Ctrl+C - Copy Content
                        if (e.ctrlKey && e.key === 'c' && document.activeElement.tagName !== 'INPUT' && document.activeElement.tagName !== 'TEXTAREA') {
                            e.preventDefault();
                            this.copyToClipboard();
                        }
                        
                        // Esc - Back to Notes
                        if (e.key === 'Escape') {
                            e.preventDefault();
                            window.location.href = '{{ route("notes.index") }}';
                        }
                    });
                },

                async togglePin() {
                    if (this.isLoading) return;
                    
                    this.isLoading = true;
                    
                    try {
                        const response = await fetch(`/ims/notes/{{ $note->id }}/toggle-pin`, {
                            method: 'PATCH',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
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

                copyToClipboard() {
                    const content = `{{ $note->title }}\n\n{{ addslashes($note->content ?? '') }}`;
                    
                    if (navigator.clipboard) {
                        navigator.clipboard.writeText(content).then(() => {
                            this.showToast('success', 'Note content copied to clipboard!');
                        }).catch(() => {
                            this.fallbackCopyToClipboard(content);
                        });
                    } else {
                        this.fallbackCopyToClipboard(content);
                    }
                },

                fallbackCopyToClipboard(text) {
                    const textArea = document.createElement('textarea');
                    textArea.value = text;
                    textArea.style.position = 'fixed';
                    textArea.style.left = '-999999px';
                    textArea.style.top = '-999999px';
                    document.body.appendChild(textArea);
                    textArea.focus();
                    textArea.select();
                    
                    try {
                        document.execCommand('copy');
                        this.showToast('success', 'Note content copied to clipboard!');
                    } catch (err) {
                        this.showToast('error', 'Could not copy to clipboard');
                    }
                    
                    document.body.removeChild(textArea);
                },

                formatContent(content) {
                    if (!content) return '';
                    
                    // Basic formatting: bold, italic, lists
                    return content
                        .replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>')
                        .replace(/\*(.*?)\*/g, '<em>$1</em>')
                        .replace(/^• (.+)$/gm, '<li>$1</li>')
                        .replace(/(<li>.*<\/li>)/s, '<ul>$1</ul>')
                        .replace(/\n/g, '<br>');
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
</x-app-layout>
