<x-app-layout>
    <x-slot name="title">
        {{ __('Edit Email Draft') }} - {{ config('app.name', 'IMS') }}
    </x-slot>

    <div class="bg-white min-h-screen" x-data="emailEditor()">
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
                    <li>
                        <div class="flex items-center">
                            <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                            <a href="{{ route('emails.drafts') }}"
                                class="text-sm font-medium text-gray-700 hover:text-blue-600">
                                Drafts
                            </a>
                        </div>
                    </li>
                    <li aria-current="page">
                        <div class="flex items-center">
                            <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                            <span class="text-sm font-medium text-gray-500">Edit Email</span>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>

        <!-- Header -->
        <div class="px-6 py-6 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-edit text-blue-600 text-xl"></i>
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">📧 Edit Email Draft</h1>
                        <p class="text-lg text-gray-600 mt-2">Modify and complete your draft email</p>
                        <div class="flex items-center mt-2 text-sm text-gray-500">
                            <i class="fas fa-calendar mr-1"></i>
                            <span>Last saved: {{ $email->updated_at->diffForHumans() }}</span>
                        </div>
                    </div>
                </div>
                <div class="flex items-center space-x-3">
                    <!-- Help Button -->
                    <button @click="showHelp = true"
                        class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition-colors">
                        <i class="fas fa-question-circle w-4 h-4 mr-2"></i>
                        Help
                    </button>
                </div>
            </div>
        </div>

        <!-- Success/Error Messages -->
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

        @if (session('error'))
            <div class="mx-6 mt-6">
                <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-circle text-red-400"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        @if ($errors->any())
            <div class="mx-6 mt-6">
                <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-circle text-red-400"></i>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800">Please fix the following errors:</h3>
                            <ul class="mt-2 text-sm text-red-700 list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Content -->
        <div class="p-6">
            <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">📝 Email Composition</h2>
                </div>

                <div class="p-6">
                    <form action="{{ route('emails.update', $email->id) }}" method="POST" enctype="multipart/form-data" id="emailForm">
                        @csrf
                        @method('PUT')

                        <!-- Recipient Fields -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-envelope mr-1 text-blue-500"></i>To: <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="to" id="to"
                                    value="{{ old('to', $email->to) }}"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    placeholder="recipient@example.com"
                                    required>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-copy mr-1 text-green-500"></i>CC:
                                </label>
                                <input type="text" name="cc" id="cc"
                                    value="{{ old('cc', $email->cc) }}"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    placeholder="cc@example.com">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-eye-slash mr-1 text-gray-500"></i>BCC:
                                </label>
                                <input type="text" name="bcc" id="bcc"
                                    value="{{ old('bcc', $email->bcc) }}"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    placeholder="bcc@example.com">
                            </div>
                        </div>

                        <!-- Subject -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-heading mr-1 text-purple-500"></i>Subject: <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="subject" id="subject"
                                value="{{ old('subject', $email->subject) }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                placeholder="Enter email subject"
                                required>
                        </div>

                        <!-- Body -->
                        <div class="mb-6">
                            <div class="flex items-center justify-between mb-2">
                                <label class="block text-sm font-medium text-gray-700">
                                    <i class="fas fa-align-left mr-1 text-orange-500"></i>Message Body: <span class="text-red-500">*</span>
                                </label>
                                <div class="text-xs text-gray-500">Use the toolbar to format your message</div>
                            </div>
                            <div id="editor-container" class="bg-white border border-gray-300 rounded-lg min-h-[300px]">
                            </div>
                            <textarea name="body" id="body" style="display:none;">{{ $email->body }}</textarea>
                        </div>

                        <!-- Attachments -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-3">
                                <i class="fas fa-paperclip mr-1 text-yellow-500"></i>Attachments
                            </label>
                            
                            @if ($email->attachments && count(json_decode($email->attachments, true)) > 0)
                                <div class="mb-4">
                                    <h4 class="text-sm font-medium text-gray-600 mb-2">Current Attachments:</h4>
                                    <div class="bg-gray-50 rounded-lg p-3">
                                        @foreach (json_decode($email->attachments, true) as $attachment)
                                            <div class="flex items-center text-sm text-gray-700">
                                                <i class="fas fa-file mr-2 text-blue-500"></i>
                                                <span>{{ $attachment }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <div>
                                <label class="block text-sm font-medium text-gray-600 mb-2">Add More Attachments:</label>
                                <input type="file" name="attachments[]" id="attachments"
                                    multiple
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <p class="text-xs text-gray-500 mt-1">You can add additional attachments if needed</p>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex flex-col sm:flex-row justify-between items-center gap-4 pt-6 border-t border-gray-200">
                            <a href="{{ route('emails.drafts') }}"
                                class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition-colors">
                                <i class="fas fa-arrow-left w-4 h-4 mr-2"></i>
                                Back to Drafts
                            </a>
                            <div class="flex items-center space-x-3">
                                <button type="submit" name="save_draft" id="save-draft-btn"
                                    class="inline-flex items-center px-6 py-3 bg-yellow-600 hover:bg-yellow-700 text-white text-sm font-medium rounded-lg transition-colors">
                                    <i class="fas fa-save w-4 h-4 mr-2"></i>
                                    Save Draft
                                </button>
                                <button type="submit" name="send_email" id="send-email-btn"
                                    class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                                    <i class="fas fa-paper-plane w-4 h-4 mr-2"></i>
                                    Send Email
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Help Modal -->
        <div x-show="showHelp" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0" class="fixed inset-0 z-50 overflow-y-auto"
            @keydown.escape.window="closeHelp()">

            <!-- Backdrop -->
            <div class="fixed inset-0 bg-gray-900 bg-opacity-50" @click="closeHelp()"></div>

            <!-- Modal -->
            <div class="relative flex min-h-screen items-center justify-center p-4">
                <div x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100"
                    x-transition:leave-end="opacity-0 scale-95"
                    class="bg-white rounded-lg shadow-xl max-w-md w-full p-6">
                    
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">📧 Email Editor Help</h3>
                        <button @click="closeHelp()" class="text-gray-400 hover:text-gray-600">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <h4 class="font-medium text-gray-900 mb-2">Quick Guide</h4>
                            <ul class="text-sm text-gray-600 space-y-1">
                                <li>• Fill in recipient email addresses in the "To" field</li>
                                <li>• Use CC for carbon copy recipients</li>
                                <li>• Use BCC for blind carbon copy recipients</li>
                                <li>• Write a clear, descriptive subject line</li>
                                <li>• Compose your message using the rich text editor</li>
                                <li>• Add file attachments if needed</li>
                                <li>• Click "Save Draft" to save for later or "Send Email" to send immediately</li>
                            </ul>
                        </div>
                        
                        <div>
                            <h4 class="font-medium text-gray-900 mb-2">Editor Tools</h4>
                            <ul class="text-sm text-gray-600 space-y-1">
                                <li>• Use the toolbar to format text (bold, italic, etc.)</li>
                                <li>• Add links, lists, and quotes</li>
                                <li>• Change text colors and alignment</li>
                                <li>• Insert headers for better organization</li>
                            </ul>
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end">
                        <button @click="closeHelp()" 
                                class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition-colors">
                            Got it
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
                       <!-- Include Quill Rich Text Editor -->
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>

    <script>
        function emailEditor() {
            return {
                showHelp: false,

                init() {
                    this.initializeQuillEditor();
                },

                initializeQuillEditor() {
                    // Initialize Quill editor
                    var quill = new Quill('#editor-container', {
                        theme: 'snow',
                        modules: {
                            toolbar: [
                                [{ 'header': [1, 2, 3, false] }],
                                ['bold', 'italic', 'underline', 'strike'],
                                [{ 'color': [] }, { 'background': [] }],
                                [{ 'list': 'ordered' }, { 'list': 'bullet' }],
                                [{ 'align': [] }],
                                ['link', 'blockquote'],
                                ['clean']
                            ]
                        },
                        placeholder: 'Write your email message here...'
                    });

                    // Set initial content
                    var bodyContent = document.getElementById('body').value;
                    if (bodyContent) {
                        var htmlContent = bodyContent.replace(/\n/g, '<br>');
                        quill.root.innerHTML = htmlContent;
                    }

                    // Function to update hidden textarea
                    function updateHiddenTextarea() {
                        var editorContent = quill.root.innerHTML;
                        if (editorContent === '<p><br></p>' || editorContent === '<p></p>' || 
                            editorContent.trim() === '' || editorContent === '<div><br></div>' || 
                            quill.getText().trim() === '') {
                            document.getElementById('body').value = '';
                        } else {
                            document.getElementById('body').value = editorContent;
                        }
                        return document.getElementById('body').value;
                    }

                    // Update on content change
                    quill.on('text-change', function() {
                        updateHiddenTextarea();
                    });

                    // Add event listeners
                    document.getElementById('save-draft-btn').addEventListener('click', function(e) {
                        updateHiddenTextarea();
                    });

                    document.getElementById('send-email-btn').addEventListener('click', function(e) {
                        updateHiddenTextarea();
                        var textContent = quill.getText().trim();
                        if (!textContent || textContent === '') {
                            alert('Please enter email body content before sending.');
                            e.preventDefault();
                            return false;
                        }
                    });

                    // Form submission handler
                    document.querySelector('form').onsubmit = function(e) {
                        updateHiddenTextarea();
                        var isDraft = e.submitter && e.submitter.name === 'save_draft';
                        var textContent = quill.getText().trim();
                        if (!isDraft && (!textContent || textContent === '')) {
                            alert('Please enter email body content before sending.');
                            e.preventDefault();
                            return false;
                        }
                        return true;
                    };
                },

                closeHelp() {
                    this.showHelp = false;
                }
            }
        }
    </script>

    <style>
        #editor-container {
            min-height: 300px;
        }
        .ql-editor {
            color: #1f2937;
            background-color: #ffffff;
        }
        .ql-toolbar {
            background-color: #f9fafb;
            border-color: #d1d5db;
        }
        .ql-toolbar .ql-picker-label {
            color: #374151;
        }
        .ql-toolbar .ql-stroke {
            stroke: #374151;
        }
        .ql-toolbar .ql-fill {
            fill: #374151;
        }
    </style>
</x-app-layout>
