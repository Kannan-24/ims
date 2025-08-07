<x-app-layout>
    <div class="py-6 mt-20 ml-4 sm:ml-64">
        <div class="w-full mx-auto max-w-7xl sm:px-6 lg:px-8">
            <x-bread-crumb-navigation />

            <h2 class="text-3xl font-bold text-gray-200 mb-6">Edit Email Draft</h2>

            @if (session('success'))
                <div class="bg-green-500 text-white p-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="bg-red-500 text-white p-3 rounded mb-4">
                    {{ session('error') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="bg-red-500 text-white p-3 rounded mb-4">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('emails.update', $email->id) }}" method="POST" enctype="multipart/form-data"
                id="emailForm">
                @csrf
                @method('PUT')

                <div class="mb-6">
                    <label class="block text-gray-300 font-semibold mb-2">To:</label>
                    <input type="text" name="to" id="to"
                        class="w-full px-4 py-3 border border-gray-700 bg-gray-800 text-gray-300 rounded-lg shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500 transition"
                        placeholder="Add recipient email addresses" value="{{ $email->to }}" required>
                </div>

                <div class="mb-6">
                    <label class="block text-gray-300 font-semibold mb-2">CC:</label>
                    <input type="text" name="cc" id="cc"
                        class="w-full px-4 py-3 border border-gray-700 bg-gray-800 text-gray-300 rounded-lg shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500 transition"
                        placeholder="Add CC email addresses" value="{{ $email->cc }}">
                </div>

                <div class="mb-6">
                    <label class="block text-gray-300 font-semibold mb-2">BCC:</label>
                    <input type="text" name="bcc" id="bcc"
                        class="w-full px-4 py-3 border border-gray-700 bg-gray-800 text-gray-300 rounded-lg shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500 transition"
                        placeholder="Add BCC email addresses" value="{{ $email->bcc }}">
                </div>

                <div class="mb-6">
                    <label class="block text-gray-300 font-semibold mb-2">Subject:</label>
                    <input type="text" name="subject" id="subject"
                        class="w-full px-4 py-3 border border-gray-700 bg-gray-800 text-gray-300 rounded-lg shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500 transition"
                        placeholder="Enter email subject" value="{{ $email->subject }}" required>
                </div>

                <div class="mb-6">
                    <label class="block text-gray-300 font-semibold mb-2">Body:</label>
                    <div id="editor-container" class="bg-gray-800 border border-gray-700 rounded-lg">
                    </div>
                    <textarea name="body" id="body" style="display:none;">{{ $email->body }}</textarea>
                </div>

                <div class="mb-6">
                    <label class="block text-gray-300 font-semibold mb-2">Current Attachments:</label>
                    @if ($email->attachments && count(json_decode($email->attachments, true)) > 0)
                        <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded mb-4">
                            @foreach (json_decode($email->attachments, true) as $attachment)
                                <div class="flex items-center mb-2">
                                    <i class="fas fa-file-pdf text-red-500 mr-2"></i>
                                    <span>{{ basename($attachment) }}</span>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    <label class="block text-gray-300 font-semibold mb-2">Add More Attachments:</label>
                    <input type="file" name="attachments[]" id="attachments"
                        class="w-full px-4 py-3 border border-gray-700 bg-gray-800 text-gray-300 rounded-lg shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500 transition"
                        multiple>
                    <small class="text-gray-400">You can add additional attachments if needed</small>
                </div>

                <div class="flex justify-between">
                    <a href="{{ route('emails.drafts') }}"
                        class="px-6 py-3 bg-gray-500 hover:bg-gray-600 text-white font-semibold rounded-lg shadow-md transition">
                        <i class="fas fa-arrow-left mr-2"></i>Back to Drafts
                    </a>
                    <div class="space-x-3">
                        <button type="submit" name="save_draft" value="1" id="save-draft-btn"
                            class="px-6 py-3 bg-yellow-500 hover:bg-yellow-600 text-white font-semibold rounded-lg shadow-md transition">
                            <i class="fas fa-save mr-2"></i>Save Draft
                        </button>
                        <button type="submit" id="send-email-btn"
                            class="px-6 py-3 bg-blue-500 hover:bg-blue-600 text-white font-semibold rounded-lg shadow-md transition">
                            <i class="fas fa-paper-plane mr-2"></i>Send Email
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Include Quill Rich Text Editor -->
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>

    <script>
        // Initialize Quill editor
        var quill = new Quill('#editor-container', {
            theme: 'snow',
            modules: {
                toolbar: [
                    [{
                        'header': [1, 2, 3, false]
                    }],
                    ['bold', 'italic', 'underline', 'strike'],
                    [{
                        'color': []
                    }, {
                        'background': []
                    }],
                    [{
                        'list': 'ordered'
                    }, {
                        'list': 'bullet'
                    }],
                    [{
                        'align': []
                    }],
                    ['link', 'blockquote'],
                    ['clean']
                ]
            },
            placeholder: 'Write your email message here...'
        });

        // Set initial content
        var bodyContent = document.getElementById('body').value;
        if (bodyContent) {
            // Convert plain text to HTML with line breaks
            var htmlContent = bodyContent.replace(/\n/g, '<br>');
            quill.root.innerHTML = htmlContent;
        }

        // Function to update hidden textarea
        function updateHiddenTextarea() {
            var editorContent = quill.root.innerHTML;
            console.log('Raw editor content:', editorContent);

            // Check if content is empty (various empty states from Quill)
            if (editorContent === '<p><br></p>' ||
                editorContent === '<p></p>' ||
                editorContent.trim() === '' ||
                editorContent === '<div><br></div>' ||
                quill.getText().trim() === '') {
                document.getElementById('body').value = '';
                console.log('Set body to empty');
            } else {
                document.getElementById('body').value = editorContent;
                console.log('Set body to:', editorContent);
            }
            return document.getElementById('body').value;
        }

        // Update on content change
        quill.on('text-change', function() {
            updateHiddenTextarea();
        });

        // Add click handlers for buttons
        document.getElementById('save-draft-btn').addEventListener('click', function(e) {
            console.log('Save Draft button clicked');
            updateHiddenTextarea();

            // For draft, we don't need body validation
            var bodyValue = document.getElementById('body').value;
            console.log('Draft - Body value:', bodyValue);
        });

        document.getElementById('send-email-btn').addEventListener('click', function(e) {
            console.log('Send Email button clicked');
            updateHiddenTextarea();

            // Validate body for sending
            var bodyValue = document.getElementById('body').value;
            var textContent = quill.getText().trim();

            console.log('Send - Body value:', bodyValue);
            console.log('Send - Text content:', textContent);

            if (!textContent || textContent === '') {
                alert('Please enter email body content before sending.');
                e.preventDefault();
                return false;
            }
        });

        // Update hidden textarea when form is submitted
        document.querySelector('form').onsubmit = function(e) {
            console.log('Form submit event triggered');
            updateHiddenTextarea();

            // Check which button was clicked
            var isDraft = e.submitter && e.submitter.name === 'save_draft';
            var bodyValue = document.getElementById('body').value;
            var textContent = quill.getText().trim();

            console.log('Form submission:');
            console.log('- Is draft:', isDraft);
            console.log('- Body value:', bodyValue);
            console.log('- Text content:', textContent);
            console.log('- Submitter:', e.submitter);

            // Only validate body if not saving as draft
            if (!isDraft && (!textContent || textContent === '')) {
                alert('Please enter email body content before sending.');
                e.preventDefault();
                return false;
            }

            console.log('Form submission allowed');
            return true;
        };
    </script>

    <style>
        #editor-container {
            min-height: 300px;
        }

        .ql-editor {
            color: #e5e7eb;
            background-color: #374151;
        }

        .ql-toolbar {
            background-color: #4b5563;
            border-color: #6b7280;
        }

        .ql-toolbar .ql-picker-label {
            color: #e5e7eb;
        }

        .ql-toolbar .ql-stroke {
            stroke: #e5e7eb;
        }

        .ql-toolbar .ql-fill {
            fill: #e5e7eb;
        }
    </style>
</x-app-layout>
