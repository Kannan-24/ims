<x-app-layout>
    <x-slot name="title">
        {{ __('Compose Email') }} - {{ config('app.name', 'IMS') }}
    </x-slot>

    <div class="bg-white min-h-screen" x-data="emailComposer()">
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
                            <span class="text-sm font-medium text-gray-500">Compose Email</span>
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
                        <h1 class="text-3xl font-bold text-gray-900">Compose Email</h1>
                        <p class="text-lg text-gray-600 mt-2">Create and send professional emails with document attachments</p>
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

        <!-- Email Form -->
        <div class="px-6 py-6">
            <form action="{{ route('emails.store') }}" method="POST" enctype="multipart/form-data" id="emailForm">
                @csrf

                <!-- Recipients Section -->
                <div class="bg-white border border-gray-200 rounded-lg p-6 mb-6 shadow-sm">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">
                            <i class="fas fa-users text-blue-600 mr-2"></i>
                            Recipients
                        </h3>
                        <button type="button" @click="openContactBook('to')"
                                class="inline-flex items-center px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors"
                                title="Open Contact Book (Ctrl+T for TO, Ctrl+Shift+C for CC, Ctrl+Shift+B for BCC)">
                            <i class="fas fa-address-book w-4 h-4 mr-2"></i>
                            Contact Book
                        </button>
                    </div>
                    <div class="grid grid-cols-1 gap-4">
                        <!-- To Field -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">To <span class="text-red-500">*</span></label>
                            <div class="flex space-x-2">
                                <input type="text" name="to" id="to" required
                                    value="{{ old('to', isset($emailData['to']) ? $emailData['to'] : '') }}"
                                    placeholder="recipient@example.com, another@example.com"
                                    class="flex-1 px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    x-ref="toInput">
                                <button type="button" @click="openContactBook('to')"
                                        class="px-3 py-2 bg-blue-100 hover:bg-blue-200 text-blue-700 text-sm font-medium rounded-lg transition-colors"
                                        title="Open Contact Book for TO field (Ctrl+T)">
                                    <i class="fas fa-address-book"></i>
                                </button>
                            </div>
                            <p class="mt-1 text-sm text-gray-500">Separate multiple email addresses with commas</p>
                        </div>

                        <!-- CC and BCC Fields -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">CC (Carbon Copy)</label>
                                <div class="flex space-x-2">
                                    <input type="text" name="cc" id="cc"
                                        value="{{ old('cc', isset($emailData['cc']) ? $emailData['cc'] : '') }}"
                                        placeholder="cc@example.com"
                                        class="flex-1 px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                        x-ref="ccInput">
                                    <button type="button" @click="openContactBook('cc')"
                                            class="px-3 py-2 bg-green-100 hover:bg-green-200 text-green-700 text-sm font-medium rounded-lg transition-colors"
                                            title="Open Contact Book for CC field (Ctrl+Shift+C)">
                                        <i class="fas fa-address-book"></i>
                                    </button>
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">BCC (Blind Carbon Copy)</label>
                                <div class="flex space-x-2">
                                    <input type="text" name="bcc" id="bcc"
                                        value="{{ old('bcc', isset($emailData['bcc']) ? $emailData['bcc'] : '') }}"
                                        placeholder="bcc@example.com"
                                        class="flex-1 px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                        x-ref="bccInput">
                                    <button type="button" @click="openContactBook('bcc')"
                                            class="px-3 py-2 bg-purple-100 hover:bg-purple-200 text-purple-700 text-sm font-medium rounded-lg transition-colors"
                                            title="Open Contact Book for BCC field (Ctrl+Shift+B)">
                                        <i class="fas fa-address-book"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Subject Section -->
                <div class="bg-white border border-gray-200 rounded-lg p-6 mb-6 shadow-sm">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">
                        <i class="fas fa-tag text-green-600 mr-2"></i>
                        Subject
                    </h3>
                    <input type="text" name="subject" id="subject" required
                        value="{{ old('subject', isset($emailData['subject']) ? $emailData['subject'] : '') }}"
                        placeholder="Enter email subject"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-lg">
                </div>

                <!-- Content Section -->
                <div class="bg-white border border-gray-200 rounded-lg p-6 mb-6 shadow-sm">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">
                            <i class="fas fa-file-alt text-purple-600 mr-2"></i>
                            Message Content
                        </h3>
                        <div class="flex space-x-2">
                        </div>
                    </div>

                    <!-- Rich Text Editor Container -->
                    <div id="editor-container" class="bg-white border border-gray-300 rounded-lg min-h-[300px]"></div>
                    
                    <!-- Hidden Textarea to store the editor content -->
                    <textarea name="body" id="body" class="hidden">{{ old('body', isset($emailData['body']) ? $emailData['body'] : 'Dear Sir,

Good Afternoon,

As discussed, please find the attached quotation for your requirements.

We kindly request you to confirm your valuable order with us at your earliest convenience.

We assure you of our best service and support at all times.

Thank you and regards,

R. Radhika
Partner
SKM and Company
8870820449
skmandcompany@yahoo.in') }}</textarea>
                </div>

                <!-- Attachments Section -->
                <div class="bg-white border border-gray-200 rounded-lg p-6 mb-6 shadow-sm">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">
                        <i class="fas fa-paperclip text-orange-600 mr-2"></i>
                        Attachments
                    </h3>
                    
                    <!-- Document Attachment Feature -->
                    <div class="mb-6 p-4 bg-gray-50 border border-gray-200 rounded-lg">
                        <div class="flex items-center mb-3">
                            <input type="checkbox" id="addDocument" x-model="addDocumentChecked" 
                                @change="addDocumentChecked ? showDocumentPopup = true : (showDocumentPopup = false, resetDocumentSelection())"
                                class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <label for="addDocument" class="ml-2 text-md font-medium text-gray-900">
                                <i class="fas fa-file-invoice text-blue-600 mr-2"></i>
                                Add Invoice/Quotation Document
                            </label>
                        </div>
                        <p class="text-sm text-gray-600 mb-4">Check this box to attach an invoice or quotation document to your email</p>
                        <!-- Document Selection -->
                        <div x-show="addDocumentChecked && showDocumentPopup" class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Select Document Type</label>
                            <select x-model="documentType" @change="loadDocumentsForAttachment()" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 mb-4">
                                <option value="">Choose document type...</option>
                                <option value="invoice">Invoice</option>
                                <option value="quotation">Quotation</option>
                            </select>

                            <!-- Document Selection -->
                            <div x-show="documentType && availableDocuments.length > 0" class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Select Document</label>
                                <select x-model="selectedDocumentId" 
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">Choose a document...</option>
                                    <template x-for="doc in availableDocuments" :key="doc.id">
                                        <option :value="doc.id" x-text="`${doc.number} - ${doc.customer} (₹${new Intl.NumberFormat('en-IN').format(doc.amount)})`"></option>
                                    </template>
                                </select>
                            </div>

                            <!-- Loading State -->
                            <div x-show="loadingDocuments" class="text-center py-4">
                                <i class="fas fa-spinner fa-spin mr-2"></i>Loading documents...
                            </div>

                            <!-- No Document Type Selected -->
                            <div x-show="!documentType && !loadingDocuments" class="text-center py-4 text-gray-500">
                                Please select a document type to see available documents
                            </div>

                            <!-- No Documents Found -->
                            <div x-show="documentType && !loadingDocuments && availableDocuments.length === 0" class="text-center py-4 text-gray-500">
                                No documents found for the selected type
                            </div>

                            <!-- Generate Button -->
                            <div x-show="documentType && selectedDocumentId && !generatingDocument" class="mt-4">
                                <button type="button" @click="selectAndGenerateDocument()"
                                        class="w-full px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                                    <i class="fas fa-file-pdf mr-2"></i>
                                    Generate and Attach Document
                                </button>
                            </div>

                            <!-- Generating State -->
                            <div x-show="generatingDocument" class="text-center py-4">
                                <i class="fas fa-spinner fa-spin mr-2"></i>Generating document...
                            </div>
                        </div>
                        
                        <!-- Generated Attachments List -->
                        <div x-show="generatedAttachments.length > 0" style="display: none;" class="mt-4">
                            <h5 class="text-sm font-medium text-gray-700 mb-2">Document Attachments:</h5>
                            <div class="space-y-2">
                                <template x-for="attachment in generatedAttachments" :key="attachment.filename">
                                    <div class="flex items-center justify-between p-2 bg-green-50 border border-green-200 rounded">
                                        <div class="flex items-center">
                                            <i class="fas fa-file-pdf text-red-500 mr-2"></i>
                                            <span class="text-sm font-medium text-green-800" x-text="attachment.filename"></span>
                                            <span class="text-xs text-green-600 ml-2" x-text="attachment.document_info && attachment.document_info.customer ? '(' + attachment.document_info.customer + ')' : ''"></span>
                                        </div>
                                        <button type="button" @click="removeGeneratedAttachment(attachment.filename)"
                                            class="text-red-500 hover:text-red-700">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Existing Attachment (if editing) -->
                    @if (isset($emailData['attachment_path']))
                        <div class="mb-4 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                            <div class="flex items-center">
                                <i class="fas fa-file text-blue-600 mr-2"></i>
                                <span class="text-sm font-medium text-blue-900">
                                    Existing attachment: {{ basename($emailData['attachment_path']) }}
                                </span>
                            </div>
                        </div>
                    @endif

                    <!-- File Upload -->
                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-blue-400 transition-colors">
                        <input type="file" name="attachments[]" id="attachments" multiple 
                            class="hidden" onchange="updateFileList(this)">
                        <label for="attachments" class="cursor-pointer">
                            <div class="flex flex-col items-center">
                                <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 mb-4"></i>
                                <p class="text-lg font-medium text-gray-700">Click to upload additional files</p>
                                <p class="text-sm text-gray-500 mt-2">or drag and drop files here</p>
                                <p class="text-xs text-gray-400 mt-1">Supports: PDF, Word, Excel, Images (Max 10MB per file)</p>
                            </div>
                        </label>
                    </div>
                    
                    <!-- File List -->
                    <div id="fileList" class="mt-4 space-y-2" style="display: none;">
                        <h4 class="text-sm font-medium text-gray-700">Selected Files:</h4>
                        <div id="selectedFiles"></div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="bg-white border border-gray-200 rounded-lg p-6 shadow-sm">
                    <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
                        <a href="{{ url()->previous() }}"
                            class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition-colors">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Cancel
                        </a>
                        <div class="flex space-x-3">
                            <button type="submit" name="save_draft" value="1" id="save-draft-btn"
                                class="inline-flex items-center px-6 py-3 bg-orange-600 hover:bg-orange-700 text-white font-medium rounded-lg transition-colors">
                                <i class="fas fa-save mr-2"></i>
                                Save as Draft
                            </button>
                            <button type="submit" id="send-email-btn"
                                class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                                <i class="fas fa-paper-plane mr-2"></i>
                                Send Email
                            </button>
                        </div>
                    </div>
                </div>
            </form>
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
                    <h3 class="text-lg font-semibold text-gray-900">Email Composition Help</h3>
                    <button @click="showHelp = false" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <div class="space-y-4">
                    <div>
                        <h4 class="font-semibold text-gray-900 mb-2">Quick Tips</h4>
                        <ul class="space-y-1 text-gray-600">
                            <li>• Use clear, descriptive subject lines</li>
                            <li>• Separate multiple recipients with commas</li>
                            <li>• Save drafts frequently while composing</li>
                            <li>• Use document attachment feature for invoices/quotations</li>
                            <li>• Click contact book buttons to add emails from your contacts</li>
                        </ul>
                    </div>
                    
                    <div>
                        <h4 class="font-semibold text-gray-900 mb-2">
                            <i class="fas fa-keyboard text-blue-600 mr-2"></i>Keyboard Shortcuts
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            <div class="space-y-2">
                                <div class="flex items-center justify-between">
                                    <span class="text-gray-700">Contact Book (TO)</span>
                                    <kbd class="px-2 py-1 bg-gray-100 border border-gray-300 rounded text-sm">Ctrl+T</kbd>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-gray-700">Contact Book (CC)</span>
                                    <kbd class="px-2 py-1 bg-gray-100 border border-gray-300 rounded text-sm">Ctrl+Shift+C</kbd>
                                </div>
                            </div>
                            <div class="space-y-2">
                                <div class="flex items-center justify-between">
                                    <span class="text-gray-700">Contact Book (BCC)</span>
                                    <kbd class="px-2 py-1 bg-gray-100 border border-gray-300 rounded text-sm">Ctrl+Shift+B</kbd>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-gray-700">Close Modals</span>
                                    <kbd class="px-2 py-1 bg-gray-100 border border-gray-300 rounded text-sm">Esc</kbd>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div>
                        <h4 class="font-semibold text-gray-900 mb-2">
                            <i class="fas fa-address-book text-green-600 mr-2"></i>Contact Book Features
                        </h4>
                        <ul class="space-y-1 text-gray-600">
                            <li>• Search contacts by name, email, or type</li>
                            <li>• Filter by Customer, Supplier, or Contact Person</li>
                            <li>• Add emails to TO, CC, or BCC fields individually</li>
                            <li>• Supports multiple email addresses per field</li>
                        </ul>
                    </div>
                    
                    <div>
                        <h4 class="font-semibold text-gray-900 mb-2">Document Attachment</h4>
                        <p class="text-gray-600">Generate and attach professional PDF documents for your invoices or quotations.</p>
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

        <!-- Contact Book Modal -->
        <div x-show="showContactBook" 
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
             style="display: none;">
            <div x-show="showContactBook"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 transform scale-95"
                 x-transition:enter-end="opacity-100 transform scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 transform scale-100"
                 x-transition:leave-end="opacity-0 transform scale-95"
                 class="bg-white rounded-lg w-full max-w-4xl mx-4 max-h-[80vh] overflow-hidden">
                
                <!-- Modal Header -->
                <div class="flex items-center justify-between p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-address-book text-blue-600 mr-2"></i>
                        Contact Book
                        <span x-show="selectedField" class="ml-2 px-2 py-1 text-xs font-medium rounded-full"
                              :class="{
                                  'bg-blue-100 text-blue-800': selectedField === 'to',
                                  'bg-green-100 text-green-800': selectedField === 'cc',
                                  'bg-purple-100 text-purple-800': selectedField === 'bcc'
                              }">
                            Adding to <span x-text="selectedField ? selectedField.toUpperCase() : ''"></span>
                        </span>
                    </h3>
                    <button @click="closeContactBook()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <!-- Search and Filters -->
                <div class="p-6 border-b border-gray-200">
                    <div class="flex items-center space-x-4 mb-4">
                        <div class="flex-1">
                            <input type="text" 
                                   x-model="contactSearch"
                                   @input="searchContacts()"
                                   placeholder="Search contacts..."
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <select x-model="contactFilter" @change="searchContacts()"
                                class="border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="all">All Types</option>
                            <option value="Customer">Customers</option>
                            <option value="Supplier">Suppliers</option>
                            <option value="Contact Person">Contact Persons</option>
                        </select>
                    </div>
                    
                    <!-- Multi-select Controls -->
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <label class="flex items-center">
                                <input type="checkbox" x-model="isMultiSelectMode" 
                                       @change="isMultiSelectMode ? null : (selectedContacts = [])"
                                       class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                <span class="ml-2 text-sm font-medium text-gray-700">Multi-select mode</span>
                            </label>
                            <span x-show="selectedContacts.length > 0" 
                                  class="text-sm text-blue-600 font-medium">
                                <span x-text="selectedContacts.length"></span> selected
                            </span>
                        </div>
                        <div x-show="selectedContacts.length > 0" class="flex items-center space-x-2">
                            <button @click="addSelectedContactsToField()" 
                                    class="px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg">
                                Add Selected
                            </button>
                            <button @click="selectedContacts = []" 
                                    class="px-3 py-1.5 bg-gray-500 hover:bg-gray-600 text-white text-sm font-medium rounded-lg">
                                Clear Selection
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Contact List -->
                <div class="overflow-y-auto" style="max-height: 400px;">
                    <div x-show="contactLoading" class="p-8 text-center">
                        <i class="fas fa-spinner fa-spin mr-2"></i>
                        Loading contacts...
                    </div>
                    
                    <div x-show="!contactLoading && contacts.length === 0" class="p-8 text-center text-gray-500">
                        No contacts found matching your criteria.
                    </div>

                    <div x-show="!contactLoading && contacts.length > 0" class="divide-y divide-gray-200">
                        <template x-for="contact in contacts" :key="contact.id">
                            <div class="p-4 hover:bg-gray-50 cursor-pointer transition-colors"
                                 :class="selectedContacts.includes(contact.id) ? 'bg-blue-50 border-l-4 border-blue-500' : ''"
                                 @click="isMultiSelectMode ? toggleContactSelection(contact) : selectContact(contact)">
                                <div class="flex items-center">
                                    <!-- Multi-select checkbox -->
                                    <div x-show="isMultiSelectMode" class="flex-shrink-0 mr-3">
                                        <input type="checkbox" 
                                               :checked="selectedContacts.includes(contact.id)"
                                               @click.stop
                                               @change="toggleContactSelection(contact)"
                                               class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                    </div>
                                    
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <div class="h-10 w-10 bg-gray-100 rounded-full flex items-center justify-center">
                                            <span x-text="contact.type_icon" class="text-lg"></span>
                                        </div>
                                    </div>
                                    <div class="ml-4 flex-1">
                                        <div class="flex items-center justify-between">
                                            <div>
                                                <div class="text-sm font-medium text-gray-900" x-text="contact.name"></div>
                                                <div class="text-sm text-gray-500" x-text="contact.email || 'No email'"></div>
                                                <div x-show="contact.phone" class="text-xs text-gray-400" x-text="contact.phone"></div>
                                            </div>
                                            <div class="text-right">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                                                      :class="{
                                                          'bg-green-100 text-green-800': contact.type === 'Customer',
                                                          'bg-purple-100 text-purple-800': contact.type === 'Supplier',
                                                          'bg-blue-100 text-blue-800': contact.type === 'Contact Person'
                                                      }">
                                                    <span x-text="contact.type"></span>
                                                </span>
                                            </div>
                                        </div>
                                        <div x-show="contact.parent" class="text-xs text-gray-400 mt-1">
                                            <span x-text="contact.parent"></span>
                                        </div>
                                        <div x-show="contact.location" class="text-xs text-gray-400 mt-1">
                                            <i class="fas fa-map-marker-alt mr-1"></i>
                                            <span x-text="contact.location"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="p-6 border-t border-gray-200 bg-gray-50">
                    <div class="flex items-center justify-between">
                        <p class="text-sm text-gray-600">
                            <span x-show="!isMultiSelectMode">
                                Click on a contact to add their email to the <span x-text="selectedField ? selectedField.toUpperCase() : 'TO'"></span> field
                            </span>
                            <span x-show="isMultiSelectMode">
                                Select multiple contacts and click "Add Selected" to add them to the <span x-text="selectedField ? selectedField.toUpperCase() : 'TO'"></span> field
                            </span>
                        </p>
                        <div class="flex items-center space-x-3">
                            <a href="{{ route('contact-book.index') }}" target="_blank"
                               class="text-sm text-blue-600 hover:text-blue-800">
                                Open Full Contact Book
                            </a>
                            <button @click="closeContactBook()" 
                                    class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-lg">
                                Close
                            </button>
                        </div>
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
                    [{ 'header': [1, 2, 3, false] }],
                    ['bold', 'italic', 'underline', 'strike'],
                    [{ 'color': [] }, { 'background': [] }],
                    [{ 'list': 'ordered' }, { 'list': 'bullet' }],
                    [{ 'align': [] }],
                    ['link', 'blockquote', 'code-block'],
                    ['clean']
                ]
            },
            placeholder: 'Write your email message here...'
        });

        // Set initial content
        var bodyContent = document.getElementById('body').value;
        if (bodyContent) {
            quill.root.innerHTML = bodyContent;
        }

        // Function to update hidden textarea
        function updateHiddenTextarea() {
            var editorContent = quill.root.innerHTML;
            
            // Check if content is empty (various empty states from Quill)
            if (editorContent === '<p><br></p>' ||
                editorContent === '<p></p>' ||
                editorContent.trim() === '' ||
                editorContent === '<div><br></div>' ||
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

        // Email Composer Alpine.js Component
        function emailComposer() {
            return {
                showHelp: false,
                showContactBook: false,
                selectedField: 'to', // Track which field we're adding to (to, cc, bcc)
                
                // Contact book functionality
                contacts: [],
                contactSearch: '',
                contactFilter: 'all',
                contactLoading: false,
                selectedContacts: [], // Track multiple selected contacts
                isMultiSelectMode: false, // Toggle for multi-select mode
                
                // Document attachment feature
                addDocumentChecked: false,
                showDocumentPopup: false,
                documentType: '',
                availableDocuments: [],
                selectedDocumentId: '',
                loadingDocuments: false,
                generatingDocument: false,
                generatedAttachments: [],

                init() {
                    // Check for recipients parameter in URL
                    const urlParams = new URLSearchParams(window.location.search);
                    const recipients = urlParams.get('recipients');
                    if (recipients) {
                        this.$refs.toInput.value = recipients;
                    }

                    // Add keyboard shortcuts
                    document.addEventListener('keydown', (e) => {
                        // Only trigger shortcuts when not typing in inputs
                        if (e.target.tagName === 'INPUT' || e.target.tagName === 'TEXTAREA' || e.target.getAttribute('contenteditable')) {
                            return;
                        }

                        // Ctrl/Cmd + T: Open contact book for TO field
                        if ((e.ctrlKey || e.metaKey) && e.key.toLowerCase() === 't') {
                            e.preventDefault();
                            this.openContactBook('to');
                        }
                        
                        // Ctrl/Cmd + Shift + C: Open contact book for CC field
                        if ((e.ctrlKey || e.metaKey) && e.shiftKey && e.key.toLowerCase() === 'c') {
                            e.preventDefault();
                            this.openContactBook('cc');
                        }
                        
                        // Ctrl/Cmd + Shift + B: Open contact book for BCC field
                        if ((e.ctrlKey || e.metaKey) && e.shiftKey && e.key.toLowerCase() === 'b') {
                            e.preventDefault();
                            this.openContactBook('bcc');
                        }

                        // Escape: Close contact book
                        if (e.key === 'Escape' && this.showContactBook) {
                            e.preventDefault();
                            this.closeContactBook();
                        }
                    });
                },

                // Contact book methods
                async searchContacts() {
                    this.contactLoading = true;
                    
                    try {
                        const response = await fetch('/ims/contact-book/contacts?' + new URLSearchParams({
                            search: this.contactSearch,
                            type: this.contactFilter
                        }));
                        
                        const data = await response.json();
                        this.contacts = data.contacts.filter(contact => contact.email); // Only show contacts with email
                    } catch (error) {
                        console.error('Error fetching contacts:', error);
                        this.contacts = [];
                    } finally {
                        this.contactLoading = false;
                    }
                },

                // Contact Book Methods
                openContactBook(field = 'to') {
                    this.selectedField = field;
                    this.showContactBook = true;
                    this.searchContacts();
                },

                closeContactBook() {
                    this.showContactBook = false;
                    this.selectedField = 'to';
                    this.contactSearch = '';
                    this.contactFilter = 'all';
                    this.selectedContacts = [];
                    this.isMultiSelectMode = false;
                },

                toggleContactSelection(contact) {
                    if (!contact.email) return;
                    
                    const index = this.selectedContacts.findIndex(id => id === contact.id);
                    if (index > -1) {
                        this.selectedContacts.splice(index, 1);
                    } else {
                        this.selectedContacts.push(contact.id);
                    }
                },

                addSelectedContactsToField() {
                    if (this.selectedContacts.length === 0) return;
                    
                    const selectedEmails = this.contacts
                        .filter(contact => this.selectedContacts.includes(contact.id) && contact.email)
                        .map(contact => contact.email);
                    
                    if (selectedEmails.length === 0) return;
                    
                    // Determine target input
                    let targetInput;
                    switch(this.selectedField) {
                        case 'cc':
                            targetInput = this.$refs.ccInput;
                            break;
                        case 'bcc':
                            targetInput = this.$refs.bccInput;
                            break;
                        case 'to':
                        default:
                            targetInput = this.$refs.toInput;
                            break;
                    }
                    
                    const currentValue = targetInput.value.trim();
                    const newEmails = selectedEmails.join(', ');
                    
                    if (currentValue) {
                        targetInput.value = currentValue + ', ' + newEmails;
                    } else {
                        targetInput.value = newEmails;
                    }
                    
                    // Close modal and reset
                    this.closeContactBook();
                    targetInput.focus();
                },

                selectContact(contact) {
                    if (!contact.email) return;
                    
                    // Determine which input to update based on selectedField
                    let targetInput;
                    switch(this.selectedField) {
                        case 'cc':
                            targetInput = this.$refs.ccInput;
                            break;
                        case 'bcc':
                            targetInput = this.$refs.bccInput;
                            break;
                        case 'to':
                        default:
                            targetInput = this.$refs.toInput;
                            break;
                    }
                    
                    const currentValue = targetInput.value.trim();
                    
                    if (currentValue) {
                        // Add comma and space if there's existing content
                        targetInput.value = currentValue + ', ' + contact.email;
                    } else {
                        targetInput.value = contact.email;
                    }
                    
                    // Close modal
                    this.closeContactBook();
                    
                    // Focus back to input
                    targetInput.focus();
                },

                resetDocumentSelection() {
                    this.documentType = '';
                    this.selectedDocumentId = '';
                    this.availableDocuments = [];
                    this.generatedAttachments = [];
                },

                // Document attachment methods
                async loadDocumentsForAttachment() {
                    if (!this.documentType) {
                        this.availableDocuments = [];
                        return;
                    }

                    this.loadingDocuments = true;
                    this.selectedDocumentId = '';

                    try {
                        const response = await fetch(`{{ route('emails.documents') }}?type=${this.documentType}&limit=50`);
                        
                        if (!response.ok) {
                            throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                        }
                        
                        const data = await response.json();

                        if (data.success) {
                            this.availableDocuments = data.documents || [];
                        } else {
                            this.availableDocuments = [];
                            console.error('API returned error:', data);
                            this.showNotification('Error: ' + (data.error || 'Failed to load documents'), 'error');
                        }
                    } catch (error) {
                        console.error('Error loading documents:', error);
                        this.availableDocuments = [];
                        this.showNotification('Network error: ' + error.message, 'error');
                    } finally {
                        this.loadingDocuments = false;
                    }
                },

                async selectAndGenerateDocument() {
                    if (!this.documentType || !this.selectedDocumentId) {
                        this.showNotification('Please select a document type and document', 'error');
                        return;
                    }

                    this.generatingDocument = true;

                    try {
                        const response = await fetch('{{ route('emails.generate-attachment') }}', {
                            method: 'POST',
                            headers: {
                                'Accept': 'application/json',
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify({
                                document_type: this.documentType,
                                document_id: this.selectedDocumentId
                            })
                        });

                        if (!response.ok) {
                            throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                        }
                        
                        const data = await response.json();

                        if (data.success) {
                            // Add to generated attachments list
                            this.generatedAttachments.push(data.attachment);
                            
                            // Create hidden input for form submission
                            this.addAttachmentToForm(data.attachment);
                            
                            // Reset selection
                            this.selectedDocumentId = '';
                            this.documentType = '';
                            this.availableDocuments = [];
                            this.showDocumentPopup = false;
                            this.addDocumentChecked = true;
                            
                            // Show success message
                            this.showNotification('Document generated and attached successfully!', 'success');
                        } else {
                            throw new Error(data.error || 'Failed to generate document');
                        }
                    } catch (error) {
                        console.error('Error generating document:', error);
                        this.showNotification('Error: ' + error.message, 'error');
                    } finally {
                        this.generatingDocument = false;
                    }
                },

                addAttachmentToForm(attachment) {
                    // Create hidden input for the generated attachment
                    const hiddenInput = document.createElement('input');
                    hiddenInput.type = 'hidden';
                    hiddenInput.name = 'generated_attachments[]';
                    hiddenInput.value = attachment.path;
                    hiddenInput.setAttribute('data-filename', attachment.filename);
                    
                    const form = document.querySelector('form');
                    if (form) {
                        form.appendChild(hiddenInput);
                    } else {
                        console.error('Form not found - cannot add attachment input');
                    }
                    
                    // Also update the file list to show the generated attachment
                    this.updateFileListWithGenerated();
                },

                updateFileListWithGenerated() {
                    const fileList = document.getElementById('fileList');
                    const selectedFiles = document.getElementById('selectedFiles');
                    
                    // Show the file list if we have generated attachments
                    if (this.generatedAttachments.length > 0) {
                        fileList.style.display = 'block';
                        
                        // Clear and rebuild the file list
                        selectedFiles.innerHTML = '';
                        
                        // Add generated attachments to the file list
                        this.generatedAttachments.forEach(attachment => {
                            const fileItem = document.createElement('div');
                            fileItem.className = 'flex items-center justify-between p-2 bg-green-50 rounded border border-green-200';
                            fileItem.innerHTML = `
                                <div class="flex items-center">
                                    <i class="fas fa-file-pdf text-red-500 mr-2"></i>
                                    <span class="text-sm text-green-900 font-medium">${attachment.filename}</span>
                                    <span class="text-xs text-green-600 ml-2">(Generated PDF)</span>
                                </div>
                                <button type="button" onclick="this.parentElement.remove()" 
                                        class="text-green-600 hover:text-green-800 text-sm">
                                    <i class="fas fa-check-circle"></i>
                                </button>
                            `;
                            selectedFiles.appendChild(fileItem);
                        });
                    }
                },

                removeGeneratedAttachment(filename) {
                    // Remove from list
                    this.generatedAttachments = this.generatedAttachments.filter(att => att.filename !== filename);
                    
                    // Remove from form
                    const hiddenInput = document.querySelector(`input[data-filename="${filename}"]`);
                    if (hiddenInput) {
                        hiddenInput.remove();
                    }

                    // If no attachments left, uncheck the checkbox
                    if (this.generatedAttachments.length === 0) {
                        this.addDocumentChecked = false;
                    }
                },

                showNotification(message, type = 'info') {
                    // Create a simple notification
                    const notification = document.createElement('div');
                    notification.className = `fixed top-4 right-4 p-4 rounded-lg shadow-lg z-50 ${
                        type === 'success' ? 'bg-green-500 text-white' : 
                        type === 'error' ? 'bg-red-500 text-white' : 
                        'bg-blue-500 text-white'
                    }`;
                    notification.innerHTML = `
                        <div class="flex items-center">
                            <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : 'info-circle'} mr-2"></i>
                            ${message}
                        </div>
                    `;
                    document.body.appendChild(notification);
                    
                    // Auto remove after 3 seconds
                    setTimeout(() => {
                        notification.remove();
                    }, 3000);
                },

                init() {
                    // Add CSRF token to meta if not present
                    if (!document.querySelector('meta[name="csrf-token"]')) {
                        const meta = document.createElement('meta');
                        meta.name = 'csrf-token';
                        meta.content = '{{ csrf_token() }}';
                        document.head.appendChild(meta);
                    }

                    // Add button click handlers
                    document.getElementById('save-draft-btn').addEventListener('click', function(e) {
                        updateHiddenTextarea();
                    });

                    document.getElementById('send-email-btn').addEventListener('click', function(e) {
                        updateHiddenTextarea();
                        
                        const textContent = quill.getText().trim();
                        if (!textContent) {
                            alert('Please enter email body content before sending.');
                            e.preventDefault();
                            return false;
                        }
                    });

                    // Form submission handler
                    document.querySelector('form').onsubmit = function(e) {
                        updateHiddenTextarea();
                        
                        const isDraft = e.submitter && e.submitter.name === 'save_draft';
                        const textContent = quill.getText().trim();
                        
                        // Only validate body if not saving as draft
                        if (!isDraft && !textContent) {
                            alert('Please enter email body content before sending.');
                            e.preventDefault();
                            return false;
                        }
                        
                        return true;
                    };
                }
            }
        }

        // File upload handling
        function updateFileList(input) {
            const fileList = document.getElementById('fileList');
            const selectedFiles = document.getElementById('selectedFiles');
            
            if (input.files.length > 0) {
                fileList.style.display = 'block';
                selectedFiles.innerHTML = '';
                
                Array.from(input.files).forEach(file => {
                    const fileItem = document.createElement('div');
                    fileItem.className = 'flex items-center justify-between p-2 bg-gray-50 rounded border';
                    fileItem.innerHTML = `
                        <div class="flex items-center">
                            <i class="fas fa-file text-gray-600 mr-2"></i>
                            <span class="text-sm text-gray-900">${file.name}</span>
                            <span class="text-xs text-gray-500 ml-2">(${(file.size / 1024).toFixed(1)} KB)</span>
                        </div>
                    `;
                    selectedFiles.appendChild(fileItem);
                });
            } else {
                fileList.style.display = 'none';
            }
        }
    </script>

    <style>
        .ql-editor {
            min-height: 200px;
            font-family: inherit;
        }
        
        .ql-toolbar {
            border-top: 1px solid #ccc;
            border-left: 1px solid #ccc;
            border-right: 1px solid #ccc;
        }
        
        .ql-container {
            border-bottom: 1px solid #ccc;
            border-left: 1px solid #ccc;
            border-right: 1px solid #ccc;
        }
    </style>
</x-app-layout>
