<x-app-layout>
    <x-slot name="title">
        Create Note - {{ config('app.name', 'SKM') }}
    </x-slot>

    <div class="bg-white min-h-screen" x-data="createNoteApp()">
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
                            <span class="text-sm font-medium text-gray-500">Create Note</span>
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
                        <span class="text-2xl mr-2">✍️</span>
                        Create New Note
                    </h1>
                    <p class="text-sm text-gray-600 mt-1">Write down your thoughts and ideas</p>
                </div>
                <div class="flex items-center space-x-3">
                    <a href="{{ route('notes.index') }}" 
                       class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-lg transition-colors">
                        <i class="fas fa-arrow-left w-4 h-4 mr-2"></i>
                        Back to Notes
                    </a>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="p-6">
            <div class="max-w-4xl mx-auto">
                <form action="{{ route('notes.store') }}" method="POST" x-ref="noteForm">
                    @csrf
                    
                    <!-- Note Card -->
                    <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden">
                        <!-- Card Header -->
                        <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                                <span class="text-xl mr-2">📄</span>
                                Note Details
                            </h3>
                        </div>

                        <!-- Card Body -->
                        <div class="p-6">
                            <div class="space-y-6">
                                <!-- Title Field -->
                                <div>
                                    <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                                        <span class="mr-1">📝</span>
                                        Title *
                                    </label>
                                    <input type="text" 
                                           id="title" 
                                           name="title" 
                                           value="{{ old('title') }}"
                                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('title') border-red-500 @enderror"
                                           placeholder="Enter note title..."
                                           required
                                           x-ref="titleInput">
                                    @error('title')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Pin Option -->
                                <div class="flex items-center">
                                    <input type="checkbox" 
                                           id="is_pinned" 
                                           name="is_pinned" 
                                           value="1"
                                           {{ old('is_pinned') ? 'checked' : '' }}
                                           class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                    <label for="is_pinned" class="ml-2 block text-sm text-gray-700">
                                        <span class="mr-1">📌</span>
                                        Pin this note for quick access
                                    </label>
                                </div>

                                <!-- Content Field -->
                                <div>
                                    <label for="content" class="block text-sm font-medium text-gray-700 mb-2">
                                        <span class="mr-1">✍️</span>
                                        Content
                                    </label>
                                    <div class="relative">
                                        <textarea id="content" 
                                                  name="content" 
                                                  rows="12"
                                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('content') border-red-500 @enderror"
                                                  placeholder="Write your note content here...">{{ old('content') }}</textarea>
                                        
                                        <!-- Rich Text Toolbar -->
                                        <div class="absolute top-2 right-2 flex items-center space-x-2">
                                            <button type="button" 
                                                    @click="toggleBold()"
                                                    class="p-1 text-gray-400 hover:text-gray-600 rounded"
                                                    title="Bold (Ctrl+B)">
                                                <i class="fas fa-bold"></i>
                                            </button>
                                            <button type="button" 
                                                    @click="toggleItalic()"
                                                    class="p-1 text-gray-400 hover:text-gray-600 rounded"
                                                    title="Italic (Ctrl+I)">
                                                <i class="fas fa-italic"></i>
                                            </button>
                                            <button type="button" 
                                                    @click="insertList()"
                                                    class="p-1 text-gray-400 hover:text-gray-600 rounded"
                                                    title="List">
                                                <i class="fas fa-list-ul"></i>
                                            </button>
                                        </div>
                                    </div>
                                    @error('content')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                    <p class="mt-1 text-xs text-gray-500">
                                        Supports basic formatting: **bold**, *italic*, and bullet lists
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Card Footer -->
                        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                            <div class="flex items-center justify-between">
                                <div class="text-sm text-gray-500">
                                    <span class="mr-1">💡</span>
                                    Tip: Use Ctrl+S to save quickly
                                </div>
                                <div class="flex items-center space-x-3">
                                    <a href="{{ route('notes.index') }}" 
                                       class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition-colors">
                                        Cancel
                                    </a>
                                    <button type="submit" 
                                            class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors"
                                            :disabled="isSubmitting"
                                            x-text="isSubmitting ? 'Saving...' : 'Save Note'">
                                        Save Note
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>

                <!-- Help Card -->
                <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <h4 class="text-sm font-semibold text-blue-800 mb-2">
                        <span class="mr-1">⌨️</span>
                        Keyboard Shortcuts
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-2 text-xs text-blue-700">
                        <div><kbd class="bg-blue-100 px-2 py-1 rounded">Ctrl+S</kbd> Save note</div>
                        <div><kbd class="bg-blue-100 px-2 py-1 rounded">Ctrl+B</kbd> Bold text</div>
                        <div><kbd class="bg-blue-100 px-2 py-1 rounded">Ctrl+I</kbd> Italic text</div>
                        <div><kbd class="bg-blue-100 px-2 py-1 rounded">Esc</kbd> Cancel and go back</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script>
        function createNoteApp() {
            return {
                isSubmitting: false,

                init() {
                    // Focus on title input
                    this.$refs.titleInput?.focus();

                    // Hotkey bindings
                    document.addEventListener('keydown', (e) => {
                        // Ctrl+S - Save Note
                        if (e.ctrlKey && e.key === 's') {
                            e.preventDefault();
                            this.saveNote();
                        }
                        
                        // Esc - Cancel
                        if (e.key === 'Escape') {
                            e.preventDefault();
                            if (confirm('Are you sure you want to cancel? Any unsaved changes will be lost.')) {
                                window.location.href = '{{ route("notes.index") }}';
                            }
                        }

                        // Text formatting shortcuts
                        const textarea = document.getElementById('content');
                        if (document.activeElement === textarea) {
                            if (e.ctrlKey && e.key === 'b') {
                                e.preventDefault();
                                this.toggleBold();
                            }
                            if (e.ctrlKey && e.key === 'i') {
                                e.preventDefault();
                                this.toggleItalic();
                            }
                        }
                    });
                },

                saveNote() {
                    if (this.isSubmitting) return;
                    
                    this.isSubmitting = true;
                    this.$refs.noteForm.submit();
                },

                toggleBold() {
                    this.wrapSelectedText('**', '**');
                },

                toggleItalic() {
                    this.wrapSelectedText('*', '*');
                },

                insertList() {
                    const textarea = document.getElementById('content');
                    const start = textarea.selectionStart;
                    const end = textarea.selectionEnd;
                    const selectedText = textarea.value.substring(start, end);
                    
                    let listText;
                    if (selectedText) {
                        listText = selectedText.split('\n').map(line => line ? `• ${line}` : line).join('\n');
                    } else {
                        listText = '• ';
                    }
                    
                    textarea.value = textarea.value.substring(0, start) + listText + textarea.value.substring(end);
                    textarea.focus();
                    textarea.setSelectionRange(start + listText.length, start + listText.length);
                },

                wrapSelectedText(prefix, suffix) {
                    const textarea = document.getElementById('content');
                    const start = textarea.selectionStart;
                    const end = textarea.selectionEnd;
                    const selectedText = textarea.value.substring(start, end);
                    
                    const replacement = prefix + selectedText + suffix;
                    textarea.value = textarea.value.substring(0, start) + replacement + textarea.value.substring(end);
                    textarea.focus();
                    
                    if (selectedText) {
                        textarea.setSelectionRange(start + prefix.length, end + prefix.length);
                    } else {
                        textarea.setSelectionRange(start + prefix.length, start + prefix.length);
                    }
                }
            }
        }
    </script>
</x-app-layout>
