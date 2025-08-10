<x-app-layout>
    <div class="py-6 mt-20 ml-4 sm:ml-64">
        <div class="w-full mx-auto max-w-7xl sm:px-6 lg:px-8">
            <x-bread-crumb-navigation />

            <h2 class="text-3xl font-bold text-gray-200 mb-6">Compose Email</h2>

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

            <form action="{{ route('emails.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="mb-6">
                    <label class="block text-gray-300 font-semibold mb-2">To:</label>
                    <input type="text" name="to" id="to"
                        class="w-full px-4 py-3 border border-gray-700 bg-gray-800 text-gray-300 rounded-lg shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500 transition"
                        placeholder="Add recipient email addresses"
                        value="{{ isset($emailData) ? $emailData['to'] : old('to') }}" required>
                </div>

                <div class="mb-6">
                    <label class="block text-gray-300 font-semibold mb-2">CC:</label>
                    <input type="text" name="cc" id="cc"
                        class="w-full px-4 py-3 border border-gray-700 bg-gray-800 text-gray-300 rounded-lg shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500 transition"
                        placeholder="Add CC email addresses" value="{{ old('cc') }}">
                </div>

                <div class="mb-6">
                    <label class="block text-gray-300 font-semibold mb-2">BCC:</label>
                    <input type="text" name="bcc" id="bcc"
                        class="w-full px-4 py-3 border border-gray-700 bg-gray-800 text-gray-300 rounded-lg shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500 transition"
                        placeholder="Add BCC email addresses" value="{{ old('bcc') }}">
                </div>

                <div class="mb-6">
                    <label class="block text-gray-300 font-semibold mb-2">Subject:</label>
                    <input type="text" name="subject" id="subject"
                        class="w-full px-4 py-3 border border-gray-700 bg-gray-800 text-gray-300 rounded-lg shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500 transition"
                        placeholder="Enter email subject"
                        value="{{ isset($emailData) ? $emailData['subject'] : old('subject') }}" required>
                </div>

                <div class="mb-6">
                    <div class="flex items-center justify-between mb-2">
                        <label for="body" class="block text-gray-300 font-semibold">Body:</label>
                        <div class="flex space-x-2">
                            <button type="button" onclick="openAIAssistant()"
                                class="px-4 py-2 bg-gradient-to-r from-cyan-500 to-blue-600 hover:from-cyan-600 hover:to-blue-700 text-white rounded-lg transition-all duration-200 flex items-center text-sm shadow-lg">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                </svg>
                                AI Assistant
                            </button>
                            <button type="button" onclick="regenerateWithAI()"
                                class="px-4 py-2 bg-gradient-to-r from-purple-500 to-pink-600 hover:from-purple-600 hover:to-pink-700 text-white rounded-lg transition-all duration-200 flex items-center text-sm shadow-lg">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                                    </path>
                                </svg>
                                Regenerate
                            </button>
                        </div>
                    </div>

                    <!-- Rich Text Editor Container -->
                    <div id="editor-container" class="bg-gray-800 border border-gray-700 rounded-lg p-4 mb-2">
                        <!-- Quill.js or another editor will be initialized here -->
                    </div>

                    <!-- Hidden Textarea to store the editor content -->
                    <textarea name="body" id="body" class="hidden">
                        {{ isset($emailData)
                            ? $emailData['body']
                            : (old('body') ?:
                                "Dear Sir,
                                                    Good afternoon,
                                                    As discussed, please find the attached quotation for your requirements.
                                                    We kindly request you to confirm your valuable order with us at your earliest convenience.
                                                    We assure you of our best service and support at all times.
                                                    Thank you and regards,
                                                    R. Radhika
                                                    Partner
                                                    SKM and Company
                                                    8870820449
                                                    skmandcompany@yahoo.in") }}
                    </textarea>
                </div>

                <div class="mb-6">
                    <label class="block text-gray-300 font-semibold mb-2">Attachments:</label>
                    @if (isset($emailData['attachment_path']))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                            <div class="flex items-center">
                                <i class="fas fa-file-pdf text-red-500 mr-2"></i>
                                <span>{{ $emailData['attachment_name'] }} will be automatically attached</span>
                            </div>
                            <input type="hidden" name="auto_attachment" value="{{ $emailData['attachment_path'] }}">
                        </div>
                    @endif
                    <input type="file" name="attachments[]" id="attachments"
                        class="w-full px-4 py-3 border border-gray-700 bg-gray-800 text-gray-300 rounded-lg shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500 transition"
                        multiple>
                    <small class="text-gray-400">You can add additional attachments if needed</small>
                </div>

                <div class="flex justify-between">
                    <a href="{{ url()->previous() }}"
                        class="px-6 py-3 bg-gray-500 hover:bg-gray-600 text-white font-semibold rounded-lg shadow-md transition">
                        <i class="fas fa-arrow-left mr-2"></i>Back
                    </a>
                    <div class="space-x-3">
                        <button type="submit" name="save_draft" value="1" id="save-draft-btn"
                            class="px-6 py-3 bg-yellow-500 hover:bg-yellow-600 text-white font-semibold rounded-lg shadow-md transition">
                            <i class="fas fa-save mr-2"></i>Save as Draft
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

    <!-- AI Assistant Modal -->
    <div id="aiAssistantModal"
        class="fixed inset-0 z-50 hidden overflow-y-auto bg-black bg-opacity-50 backdrop-blur-sm">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="relative bg-gray-800 rounded-2xl shadow-2xl w-full max-w-2xl border border-gray-700">
                <!-- Modal Header -->
                <div class="flex items-center justify-between p-6 border-b border-gray-700">
                    <div class="flex items-center">
                        <div
                            class="w-10 h-10 bg-gradient-to-r from-cyan-500 to-blue-600 rounded-xl flex items-center justify-center mr-3">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-semibold text-white">AI Email Assistant</h3>
                            <p class="text-gray-400 text-sm">Generate professional email content with AI</p>
                        </div>
                    </div>
                    <button onclick="closeAIAssistant()" class="text-gray-400 hover:text-white transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <!-- Modal Body -->
                <div class="p-6">
                    <!-- Document Selection -->
                    <div class="mb-6">
                        <label class="block text-gray-300 font-medium mb-3">Generate content based on document:</label>
                        <div class="grid grid-cols-1 gap-4">
                            <div class="space-y-3">
                                <label class="block text-sm font-medium text-gray-400">Document Type</label>
                                <select id="ai_document_type" onchange="loadDocuments()"
                                    class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white focus:ring-2 focus:ring-cyan-500 focus:border-transparent">
                                    <option value="">Select document type</option>
                                    <option value="invoice">Invoice</option>
                                    <option value="quotation">Quotation</option>
                                    <option value="all">All Documents</option>
                                </select>
                            </div>

                            <!-- Dynamic Document Selector -->
                            <div id="document_selector" class="space-y-3 hidden">
                                <label class="block text-sm font-medium text-gray-400">Select Document</label>
                                <div class="relative">
                                    <input type="text" id="document_search" onkeyup="searchDocuments()"
                                        class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white focus:ring-2 focus:ring-cyan-500 focus:border-transparent"
                                        placeholder="Search by document number or customer name...">
                                    <div class="absolute right-3 top-3">
                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                        </svg>
                                    </div>
                                </div>

                                <!-- Document List -->
                                <div id="document_list"
                                    class="max-h-48 overflow-y-auto bg-gray-900 border border-gray-600 rounded-lg">
                                    <div id="document_loading" class="hidden p-4 text-center text-gray-400">
                                        <div class="inline-flex items-center">
                                            <div
                                                class="w-4 h-4 border-2 border-cyan-500 border-t-transparent rounded-full animate-spin mr-2">
                                            </div>
                                            Loading documents...
                                        </div>
                                    </div>
                                    <div id="document_items" class="divide-y divide-gray-700">
                                        <!-- Documents will be loaded here -->
                                    </div>
                                    <div id="no_documents" class="hidden p-4 text-center text-gray-500 text-sm">
                                        No documents found
                                    </div>
                                </div>
                            </div>

                            <!-- Selected Document Info -->
                            <div id="selected_document_info"
                                class="hidden p-4 bg-gradient-to-r from-cyan-500/10 to-blue-500/10 border border-cyan-500/30 rounded-lg">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-cyan-400 mr-2" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                        </path>
                                    </svg>
                                    <span class="text-cyan-300 font-medium">Selected:</span>
                                    <span id="selected_document_text" class="text-white ml-2"></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Email Type Selection -->
                    <div class="mb-6">
                        <label class="block text-gray-300 font-medium mb-3">Email Tone:</label>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                            <button type="button" onclick="selectEmailType('standard')"
                                class="email-type-btn px-4 py-2 bg-gray-700 hover:bg-cyan-600 border border-gray-600 hover:border-cyan-500 rounded-lg text-white transition-all duration-200 text-sm"
                                data-type="standard">
                                Standard
                            </button>
                            <button type="button" onclick="selectEmailType('formal')"
                                class="email-type-btn px-4 py-2 bg-gray-700 hover:bg-cyan-600 border border-gray-600 hover:border-cyan-500 rounded-lg text-white transition-all duration-200 text-sm"
                                data-type="formal">
                                Formal
                            </button>
                            <button type="button" onclick="selectEmailType('friendly')"
                                class="email-type-btn px-4 py-2 bg-gray-700 hover:bg-cyan-600 border border-gray-600 hover:border-cyan-500 rounded-lg text-white transition-all duration-200 text-sm"
                                data-type="friendly">
                                Friendly
                            </button>
                            <button type="button" onclick="selectEmailType('follow_up')"
                                class="email-type-btn px-4 py-2 bg-gray-700 hover:bg-cyan-600 border border-gray-600 hover:border-cyan-500 rounded-lg text-white transition-all duration-200 text-sm"
                                data-type="follow_up">
                                Follow-up
                            </button>
                        </div>
                    </div>

                    <!-- Custom Prompt -->
                    <div class="mb-6">
                        <label class="block text-gray-300 font-medium mb-3">Custom Instructions (Optional):</label>
                        <textarea id="ai_custom_prompt" rows="3"
                            class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white focus:ring-2 focus:ring-cyan-500 focus:border-transparent resize-none"
                            placeholder="Add any specific instructions for the AI (e.g., 'Include payment terms', 'Mention delivery timeline', etc.)"></textarea>
                    </div>

                    <!-- Loading State -->
                    <div id="ai_loading" class="hidden text-center py-6">
                        <div class="inline-flex items-center">
                            <div
                                class="w-6 h-6 border-2 border-cyan-500 border-t-transparent rounded-full animate-spin mr-3">
                            </div>
                            <span class="text-gray-300">AI is generating your email content...</span>
                        </div>
                    </div>

                    <!-- Generated Content Preview -->
                    <div id="ai_preview" class="hidden">
                        <label class="block text-gray-300 font-medium mb-3">Generated Content:</label>
                        <div id="ai_generated_content"
                            class="p-4 bg-gray-900 border border-gray-600 rounded-lg text-gray-200 max-h-60 overflow-y-auto whitespace-pre-wrap">
                        </div>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="flex justify-between items-center p-6 border-t border-gray-700">
                    <button onclick="closeAIAssistant()"
                        class="px-6 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg transition-colors">
                        Cancel
                    </button>
                    <div class="flex space-x-3">
                        <button onclick="generateAIContent()" id="generate_btn"
                            class="px-6 py-2 bg-gradient-to-r from-cyan-500 to-blue-600 hover:from-cyan-600 hover:to-blue-700 text-white rounded-lg transition-all duration-200 flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                            Generate
                        </button>
                        
                        <button onclick="regenerateAIContent()" id="regenerate_btn" style="display: none;"
                            class="px-6 py-2 bg-gradient-to-r from-orange-500 to-red-600 hover:from-orange-600 hover:to-red-700 text-white rounded-lg transition-all duration-200 flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                            Regenerate
                        </button>
                        
                        <button onclick="useGeneratedContent()" id="use_content_btn" style="display: none;"
                            class="px-6 py-2 bg-gradient-to-r from-emerald-500 to-green-600 hover:from-emerald-600 hover:to-green-700 text-white rounded-lg transition-all duration-200 flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7"></path>
                            </svg>
                            Use This Content
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

        // AI Assistant Functions
        let selectedEmailType = 'standard';
        let generatedContent = '';
        let selectedDocument = null;
        let documentsData = [];

        function openAIAssistant() {
            document.getElementById('aiAssistantModal').classList.remove('hidden');
            // Reset form
            document.getElementById('ai_document_type').value = '';
            document.getElementById('document_selector').classList.add('hidden');
            document.getElementById('selected_document_info').classList.add('hidden');
            document.getElementById('ai_custom_prompt').value = '';
            document.getElementById('ai_preview').classList.add('hidden');
            document.getElementById('use_content_btn').style.display = 'none';
            document.getElementById('regenerate_btn').style.display = 'none';
            document.getElementById('generate_btn').style.display = 'flex';
            selectedDocument = null;
            documentsData = [];
        }

        function closeAIAssistant() {
            document.getElementById('aiAssistantModal').classList.add('hidden');
        }

        function selectEmailType(type) {
            selectedEmailType = type;
            // Update button styles
            document.querySelectorAll('.email-type-btn').forEach(btn => {
                btn.classList.remove('bg-cyan-600', 'border-cyan-500');
                btn.classList.add('bg-gray-700', 'border-gray-600');
            });
            document.querySelector(`[data-type="${type}"]`).classList.remove('bg-gray-700', 'border-gray-600');
            document.querySelector(`[data-type="${type}"]`).classList.add('bg-cyan-600', 'border-cyan-500');
        }

        async function loadDocuments() {
            const documentType = document.getElementById('ai_document_type').value;
            if (!documentType) {
                document.getElementById('document_selector').classList.add('hidden');
                return;
            }

            document.getElementById('document_selector').classList.remove('hidden');
            document.getElementById('document_loading').classList.remove('hidden');
            document.getElementById('document_items').innerHTML = '';
            document.getElementById('no_documents').classList.add('hidden');

            try {
                const response = await fetch(`{{ route('emails.ai.documents') }}?type=${documentType}&limit=20`);
                const data = await response.json();

                if (data.success) {
                    documentsData = documentType === 'all' ? data.data.documents :
                        (data.data.invoices || []).concat(data.data.quotations || []);
                    displayDocuments(documentsData);
                } else {
                    console.error('Error loading documents:', data.error);
                    document.getElementById('no_documents').classList.remove('hidden');
                }
            } catch (error) {
                console.error('Error loading documents:', error);
                document.getElementById('no_documents').classList.remove('hidden');
            } finally {
                document.getElementById('document_loading').classList.add('hidden');
            }
        }

        function displayDocuments(documents) {
            const container = document.getElementById('document_items');
            container.innerHTML = '';

            if (documents.length === 0) {
                document.getElementById('no_documents').classList.remove('hidden');
                return;
            }

            documents.forEach(doc => {
                const item = document.createElement('div');
                item.className =
                    'p-3 hover:bg-gray-700 cursor-pointer transition-colors border-b border-gray-700 last:border-b-0';
                item.onclick = () => selectDocument(doc);

                const typeColor = doc.type === 'invoice' ? 'text-green-400' : 'text-blue-400';
                const typeIcon = doc.type === 'invoice' ? '📄' : '📋';

                item.innerHTML = `
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <span class="text-lg">${typeIcon}</span>
                            <div>
                                <div class="text-white font-medium">${doc.number}</div>
                                <div class="text-gray-400 text-sm">${doc.customer}</div>
                                ${doc.contact_person ? `<div class="text-gray-500 text-xs">Contact: ${doc.contact_person}</div>` : ''}
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="${typeColor} text-sm font-medium">₹${Number(doc.amount).toLocaleString()}</div>
                            <div class="text-gray-400 text-xs">${new Date(doc.date).toLocaleDateString()}</div>
                        </div>
                    </div>
                `;

                container.appendChild(item);
            });
        }

        function selectDocument(doc) {
            selectedDocument = doc;
            document.getElementById('selected_document_text').textContent = doc.display_text;
            document.getElementById('selected_document_info').classList.remove('hidden');

            // Close document list
            document.getElementById('document_search').value = doc.number;
        }

        function searchDocuments() {
            const searchTerm = document.getElementById('document_search').value.toLowerCase();
            const filteredDocs = documentsData.filter(doc =>
                doc.number.toLowerCase().includes(searchTerm) ||
                doc.customer.toLowerCase().includes(searchTerm)
            );
            displayDocuments(filteredDocs);
        }

        async function generateAIContent() {
            if (!selectedDocument) {
                alert('Please select a document first');
                return;
            }

            const customPrompt = document.getElementById('ai_custom_prompt').value;

            // Show loading
            document.getElementById('ai_loading').classList.remove('hidden');
            document.getElementById('generate_btn').disabled = true;

            try {
                const response = await fetch("{{ route('emails.ai.generate') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        document_type: selectedDocument.type,
                        document_id: selectedDocument.id,
                        email_type: selectedEmailType,
                        custom_prompt: customPrompt
                    })
                });

                const data = await response.json();

                if (data.success) {
                    generatedContent = data.content;
                    document.getElementById('ai_generated_content').textContent = data.content;
                    document.getElementById('ai_preview').classList.remove('hidden');
                    document.getElementById('use_content_btn').style.display = 'flex';
                    document.getElementById('regenerate_btn').style.display = 'flex';
                    document.getElementById('generate_btn').style.display = 'none';

                    // Auto-populate subject if not set
                    const subjectField = document.querySelector('input[name="subject"]');
                    if (subjectField && !subjectField.value.trim()) {
                        const docType = selectedDocument.type.charAt(0).toUpperCase() + selectedDocument.type.slice(1);
                        subjectField.value = `${docType} ${selectedDocument.number} - ${selectedDocument.customer}`;
                    }

                    // Auto-populate To field with company email
                    const recipientField = document.querySelector('input[name="recipient"]');
                    if (recipientField && !recipientField.value.trim() && data.document_data && data.document_data.company_email) {
                        recipientField.value = data.document_data.company_email;
                    }

                    // Auto-populate CC field with contact person email
                    const ccField = document.querySelector('input[name="cc"]');
                    if (ccField && !ccField.value.trim() && data.document_data && data.document_data.contact_person_email) {
                        ccField.value = data.document_data.contact_person_email;
                    }
                } else {
                    alert('Error: ' + data.error);
                }
            } catch (error) {
                console.error('Error generating content:', error);
                alert('Failed to generate content. Please try again.');
            } finally {
                document.getElementById('ai_loading').classList.add('hidden');
                document.getElementById('generate_btn').disabled = false;
            }
        }

        function useGeneratedContent() {
            if (generatedContent) {
                // Set the email body content in Quill editor
                bodyQuill.setContents([]);
                bodyQuill.clipboard.dangerouslyPasteHTML(generatedContent);
                
                closeAIAssistant();
                
                // Show success message
                const toast = document.createElement('div');
                toast.className = 'fixed top-4 right-4 bg-green-600 text-white px-6 py-3 rounded-lg shadow-lg z-50';
                toast.textContent = 'AI content added to email body!';
                document.body.appendChild(toast);
                
                setTimeout(() => {
                    toast.remove();
                }, 3000);
            }
        }

        async function regenerateAIContent() {
            if (!selectedDocument) {
                alert('Please select a document first');
                return;
            }

            const customPrompt = document.getElementById('ai_custom_prompt').value;

            // Show loading
            document.getElementById('ai_loading').classList.remove('hidden');
            document.getElementById('regenerate_btn').disabled = true;

            try {
                const response = await fetch("{{ route('emails.ai.regenerate') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        document_type: selectedDocument.type,
                        document_id: selectedDocument.id,
                        email_type: selectedEmailType,
                        custom_prompt: customPrompt
                    })
                });

                const data = await response.json();

                if (data.success) {
                    generatedContent = data.content;
                    document.getElementById('ai_generated_content').textContent = data.content;
                    
                    // Auto-populate fields on regeneration as well
                    if (data.document_data) {
                        // Auto-populate To field with company email
                        const recipientField = document.querySelector('input[name="recipient"]');
                        if (recipientField && !recipientField.value.trim() && data.document_data.company_email) {
                            recipientField.value = data.document_data.company_email;
                        }

                        // Auto-populate CC field with contact person email
                        const ccField = document.querySelector('input[name="cc"]');
                        if (ccField && !ccField.value.trim() && data.document_data.contact_person_email) {
                            ccField.value = data.document_data.contact_person_email;
                        }
                    }
                    
                    // Show regeneration success
                    const toast = document.createElement('div');
                    toast.className = 'fixed top-4 right-4 bg-orange-600 text-white px-6 py-3 rounded-lg shadow-lg z-50';
                    toast.textContent = 'Content regenerated with AI!';
                    document.body.appendChild(toast);
                    
                    setTimeout(() => {
                        toast.remove();
                    }, 3000);
                } else {
                    alert('Error: ' + data.error);
                }
            } catch (error) {
                console.error('Error regenerating content:', error);
                alert('Failed to regenerate content. Please try again.');
            } finally {
                document.getElementById('ai_loading').classList.add('hidden');
                document.getElementById('regenerate_btn').disabled = false;
            }
        }

        function useGeneratedContent() {
            if (generatedContent) {
                quill.root.innerHTML = generatedContent.replace(/\n/g, '<br>');
                closeAIAssistant();
                // Show success message
                const successMsg = document.createElement('div');
                successMsg.className = 'bg-green-500 text-white p-3 rounded mb-4';
                successMsg.textContent = 'AI-generated content has been added to your email body!';
                document.querySelector('form').insertBefore(successMsg, document.querySelector('form').firstChild);
                setTimeout(() => successMsg.remove(), 5000);
            }
        }

        async function regenerateWithAI() {
            const currentContent = quill.root.innerHTML.replace(/<br>/g, '\n').replace(/<[^>]*>/g, '');
            const prompt =
                `Please improve and regenerate this email content, making it more professional and engaging:\n\n${currentContent}`;

            try {
                const response = await fetch("{{ route('emails.ai.regenerate') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        prompt: prompt,
                        context: 'Email body regeneration'
                    })
                });

                const data = await response.json();

                if (data.success) {
                    quill.root.innerHTML = data.content.replace(/\n/g, '<br>');
                    // Show success message
                    const successMsg = document.createElement('div');
                    successMsg.className = 'bg-blue-500 text-white p-3 rounded mb-4';
                    successMsg.textContent = 'Email content has been regenerated with AI!';
                    document.querySelector('form').insertBefore(successMsg, document.querySelector('form').firstChild);
                    setTimeout(() => successMsg.remove(), 5000);
                } else {
                    alert('Error: ' + data.error);
                }
            } catch (error) {
                console.error('Error regenerating content:', error);
                alert('Failed to regenerate content. Please try again.');
            }
        }

        // Initialize email type selection
        selectEmailType('standard');
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
