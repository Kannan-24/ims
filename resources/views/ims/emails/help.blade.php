<x-app-layout>
    <x-slot name="title">
        {{ __('Email Management Help') }} - {{ config('app.name', 'IMS') }}
    </x-slot>

    <div class="bg-white min-h-screen">
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
                                Email Management
                            </a>
                        </div>
                    </li>
                    <li aria-current="page">
                        <div class="flex items-center">
                            <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                            <span class="text-sm font-medium text-gray-500">Help</span>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>

        <!-- Header -->
        <div class="px-6 py-6 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Email Management Help Center</h1>
                    <p class="text-lg text-gray-600 mt-2">Complete guide to using the email management system effectively</p>
                </div>
                <div class="flex items-center space-x-3">
                    <!-- Back Button -->
                    <a href="{{ route('emails.index') }}"
                        class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                        <i class="fas fa-arrow-left w-4 h-4 mr-2"></i>
                        Back to Email Management
                    </a>
                </div>
            </div>
        </div>

        <!-- Content -->
        <div class="p-6">
            <!-- Quick Navigation -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-8">
                <h2 class="text-xl font-semibold text-blue-900 mb-4">
                    <i class="fas fa-compass text-blue-600 mr-2"></i>
                    Quick Navigation
                </h2>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <a href="#overview" class="flex items-center p-3 bg-white rounded-lg border border-blue-200 hover:bg-blue-100 transition-colors">
                        <i class="fas fa-envelope text-blue-600 mr-3"></i>
                        <span class="text-sm font-medium text-blue-900">Overview</span>
                    </a>
                    <a href="#features" class="flex items-center p-3 bg-white rounded-lg border border-blue-200 hover:bg-blue-100 transition-colors">
                        <i class="fas fa-cogs text-blue-600 mr-3"></i>
                        <span class="text-sm font-medium text-blue-900">Features</span>
                    </a>
                    <a href="#shortcuts" class="flex items-center p-3 bg-white rounded-lg border border-blue-200 hover:bg-blue-100 transition-colors">
                        <i class="fas fa-keyboard text-blue-600 mr-3"></i>
                        <span class="text-sm font-medium text-blue-900">Shortcuts</span>
                    </a>
                    <a href="#tips" class="flex items-center p-3 bg-white rounded-lg border border-blue-200 hover:bg-blue-100 transition-colors">
                        <i class="fas fa-lightbulb text-blue-600 mr-3"></i>
                        <span class="text-sm font-medium text-blue-900">Tips & Tricks</span>
                    </a>
                </div>
            </div>

            <!-- Overview Section -->
            <section id="overview" class="mb-12">
                <div class="bg-white border border-gray-200 rounded-lg p-6 shadow-sm">
                    <h2 class="text-2xl font-semibold text-gray-900 mb-6">
                        <i class="fas fa-envelope text-blue-600 mr-3"></i>
                        Email Management Overview
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-3">What is Email Management?</h3>
                            <p class="text-gray-600 mb-4">
                                The email management system allows you to compose, send, and organize business emails with 
                                advanced features like AI assistance, rich text editing, and attachment handling.
                            </p>
                            <ul class="space-y-2 text-gray-600">
                                <li class="flex items-start">
                                    <i class="fas fa-check text-green-600 mr-2 mt-1"></i>
                                    <span>Rich text email composition</span>
                                </li>
                                <li class="flex items-start">
                                    <i class="fas fa-check text-green-600 mr-2 mt-1"></i>
                                    <span>AI-powered content generation</span>
                                </li>
                                <li class="flex items-start">
                                    <i class="fas fa-check text-green-600 mr-2 mt-1"></i>
                                    <span>Draft management system</span>
                                </li>
                                <li class="flex items-start">
                                    <i class="fas fa-check text-green-600 mr-2 mt-1"></i>
                                    <span>Multiple recipients (CC/BCC)</span>
                                </li>
                            </ul>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-3">Key Components</h3>
                            <div class="space-y-3">
                                <div class="p-3 bg-gray-50 rounded-lg">
                                    <h4 class="font-medium text-gray-900">Email Dashboard</h4>
                                    <p class="text-sm text-gray-600">View all sent emails, statistics, and quick actions</p>
                                </div>
                                <div class="p-3 bg-gray-50 rounded-lg">
                                    <h4 class="font-medium text-gray-900">Draft Management</h4>
                                    <p class="text-sm text-gray-600">Save, edit, and organize email drafts</p>
                                </div>
                                <div class="p-3 bg-gray-50 rounded-lg">
                                    <h4 class="font-medium text-gray-900">Email Composer</h4>
                                    <p class="text-sm text-gray-600">Advanced editor with AI assistance</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Features Section -->
            <section id="features" class="mb-12">
                <div class="bg-white border border-gray-200 rounded-lg p-6 shadow-sm">
                    <h2 class="text-2xl font-semibold text-gray-900 mb-6">
                        <i class="fas fa-cogs text-blue-600 mr-3"></i>
                        Email Features & Capabilities
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <!-- Email Composition -->
                        <div class="p-4 border border-gray-200 rounded-lg hover:border-blue-300 transition-colors">
                            <div class="flex items-center mb-3">
                                <i class="fas fa-edit text-blue-600 text-xl mr-3"></i>
                                <h3 class="text-lg font-semibold text-gray-900">Rich Text Editor</h3>
                            </div>
                            <p class="text-gray-600 mb-3">Advanced email composition with formatting, links, and media support.</p>
                            <ul class="text-sm text-gray-600 space-y-1">
                                <li>• Text formatting (bold, italic, colors)</li>
                                <li>• Lists and bullet points</li>
                                <li>• Links and images</li>
                                <li>• HTML email support</li>
                            </ul>
                        </div>

                        <!-- AI Assistant -->
                        <div class="p-4 border border-gray-200 rounded-lg hover:border-blue-300 transition-colors">
                            <div class="flex items-center mb-3">
                                <i class="fas fa-robot text-purple-600 text-xl mr-3"></i>
                                <h3 class="text-lg font-semibold text-gray-900">AI Content Generation</h3>
                            </div>
                            <p class="text-gray-600 mb-3">Generate professional email content using AI for invoices and quotations.</p>
                            <ul class="text-sm text-gray-600 space-y-1">
                                <li>• Invoice email templates</li>
                                <li>• Quotation follow-ups</li>
                                <li>• Custom prompt support</li>
                                <li>• Content regeneration</li>
                            </ul>
                        </div>

                        <!-- Draft Management -->
                        <div class="p-4 border border-gray-200 rounded-lg hover:border-blue-300 transition-colors">
                            <div class="flex items-center mb-3">
                                <i class="fas fa-save text-orange-600 text-xl mr-3"></i>
                                <h3 class="text-lg font-semibold text-gray-900">Draft System</h3>
                            </div>
                            <p class="text-gray-600 mb-3">Save and manage email drafts with automatic saving and organization.</p>
                            <ul class="text-sm text-gray-600 space-y-1">
                                <li>• Auto-save functionality</li>
                                <li>• Draft status tracking</li>
                                <li>• Easy editing and resuming</li>
                                <li>• Draft completion indicators</li>
                            </ul>
                        </div>

                        <!-- Attachments -->
                        <div class="p-4 border border-gray-200 rounded-lg hover:border-blue-300 transition-colors">
                            <div class="flex items-center mb-3">
                                <i class="fas fa-paperclip text-green-600 text-xl mr-3"></i>
                                <h3 class="text-lg font-semibold text-gray-900">File Attachments</h3>
                            </div>
                            <p class="text-gray-600 mb-3">Attach multiple files with support for various document types.</p>
                            <ul class="text-sm text-gray-600 space-y-1">
                                <li>• Multiple file upload</li>
                                <li>• PDF, Word, Excel support</li>
                                <li>• Image attachments</li>
                                <li>• File preview and management</li>
                            </ul>
                        </div>

                        <!-- Recipients -->
                        <div class="p-4 border border-gray-200 rounded-lg hover:border-blue-300 transition-colors">
                            <div class="flex items-center mb-3">
                                <i class="fas fa-users text-indigo-600 text-xl mr-3"></i>
                                <h3 class="text-lg font-semibold text-gray-900">Recipient Management</h3>
                            </div>
                            <p class="text-gray-600 mb-3">Send emails to multiple recipients with CC and BCC functionality.</p>
                            <ul class="text-sm text-gray-600 space-y-1">
                                <li>• Multiple To recipients</li>
                                <li>• CC (Carbon Copy) support</li>
                                <li>• BCC (Blind Carbon Copy)</li>
                                <li>• Email validation</li>
                            </ul>
                        </div>

                        <!-- Email Tracking -->
                        <div class="p-4 border border-gray-200 rounded-lg hover:border-blue-300 transition-colors">
                            <div class="flex items-center mb-3">
                                <i class="fas fa-chart-line text-red-600 text-xl mr-3"></i>
                                <h3 class="text-lg font-semibold text-gray-900">Email Analytics</h3>
                            </div>
                            <p class="text-gray-600 mb-3">Track email statistics and monitor communication patterns.</p>
                            <ul class="text-sm text-gray-600 space-y-1">
                                <li>• Send date tracking</li>
                                <li>• Recipient count analytics</li>
                                <li>• Draft completion rates</li>
                                <li>• Daily email statistics</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Keyboard Shortcuts Section -->
            <section id="shortcuts" class="mb-12">
                <div class="bg-white border border-gray-200 rounded-lg p-6 shadow-sm">
                    <h2 class="text-2xl font-semibold text-gray-900 mb-6">
                        <i class="fas fa-keyboard text-blue-600 mr-3"></i>
                        Keyboard Shortcuts
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- Navigation Shortcuts -->
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Navigation & Actions</h3>
                            <div class="space-y-3">
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                    <span class="text-gray-700">Compose New Email</span>
                                    <kbd class="px-3 py-1 bg-gray-200 border border-gray-300 rounded text-sm font-mono">C</kbd>
                                </div>
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                    <span class="text-gray-700">View Drafts</span>
                                    <kbd class="px-3 py-1 bg-gray-200 border border-gray-300 rounded text-sm font-mono">D</kbd>
                                </div>
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                    <span class="text-gray-700">Focus Search</span>
                                    <kbd class="px-3 py-1 bg-gray-200 border border-gray-300 rounded text-sm font-mono">S</kbd>
                                </div>
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                    <span class="text-gray-700">Show Help</span>
                                    <kbd class="px-3 py-1 bg-gray-200 border border-gray-300 rounded text-sm font-mono">H</kbd>
                                </div>
                            </div>
                        </div>

                        <!-- Editor Shortcuts -->
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Editor Shortcuts</h3>
                            <div class="space-y-3">
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                    <span class="text-gray-700">Bold Text</span>
                                    <kbd class="px-3 py-1 bg-gray-200 border border-gray-300 rounded text-sm font-mono">Ctrl+B</kbd>
                                </div>
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                    <span class="text-gray-700">Italic Text</span>
                                    <kbd class="px-3 py-1 bg-gray-200 border border-gray-300 rounded text-sm font-mono">Ctrl+I</kbd>
                                </div>
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                    <span class="text-gray-700">Save Draft</span>
                                    <kbd class="px-3 py-1 bg-gray-200 border border-gray-300 rounded text-sm font-mono">Ctrl+S</kbd>
                                </div>
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                    <span class="text-gray-700">Close Modal</span>
                                    <kbd class="px-3 py-1 bg-gray-200 border border-gray-300 rounded text-sm font-mono">Esc</kbd>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Tips & Best Practices Section -->
            <section id="tips" class="mb-12">
                <div class="bg-white border border-gray-200 rounded-lg p-6 shadow-sm">
                    <h2 class="text-2xl font-semibold text-gray-900 mb-6">
                        <i class="fas fa-lightbulb text-blue-600 mr-3"></i>
                        Tips & Best Practices
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Email Composition Tips</h3>
                            <div class="space-y-4">
                                <div class="flex items-start">
                                    <i class="fas fa-pencil-alt text-blue-600 mr-3 mt-1"></i>
                                    <div>
                                        <h4 class="font-medium text-gray-900">Clear Subject Lines</h4>
                                        <p class="text-sm text-gray-600">Use descriptive, specific subject lines that clearly indicate the email's purpose.</p>
                                    </div>
                                </div>
                                <div class="flex items-start">
                                    <i class="fas fa-save text-green-600 mr-3 mt-1"></i>
                                    <div>
                                        <h4 class="font-medium text-gray-900">Save Drafts Frequently</h4>
                                        <p class="text-sm text-gray-600">Use the draft feature to save your work and prevent losing important content.</p>
                                    </div>
                                </div>
                                <div class="flex items-start">
                                    <i class="fas fa-robot text-purple-600 mr-3 mt-1"></i>
                                    <div>
                                        <h4 class="font-medium text-gray-900">Leverage AI Assistant</h4>
                                        <p class="text-sm text-gray-600">Use AI-generated content for professional invoice and quotation emails.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Professional Communication</h3>
                            <div class="space-y-4">
                                <div class="flex items-start">
                                    <i class="fas fa-eye text-orange-600 mr-3 mt-1"></i>
                                    <div>
                                        <h4 class="font-medium text-gray-900">Preview Before Sending</h4>
                                        <p class="text-sm text-gray-600">Always review your email content, attachments, and recipients before sending.</p>
                                    </div>
                                </div>
                                <div class="flex items-start">
                                    <i class="fas fa-paperclip text-gray-600 mr-3 mt-1"></i>
                                    <div>
                                        <h4 class="font-medium text-gray-900">Attachment Management</h4>
                                        <p class="text-sm text-gray-600">Verify all attachments are relevant and properly named before sending.</p>
                                    </div>
                                </div>
                                <div class="flex items-start">
                                    <i class="fas fa-users text-indigo-600 mr-3 mt-1"></i>
                                    <div>
                                        <h4 class="font-medium text-gray-900">Recipient Verification</h4>
                                        <p class="text-sm text-gray-600">Double-check all recipient email addresses and use CC/BCC appropriately.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Workflow Guide Section -->
            <section class="mb-12">
                <div class="bg-white border border-gray-200 rounded-lg p-6 shadow-sm">
                    <h2 class="text-2xl font-semibold text-gray-900 mb-6">
                        <i class="fas fa-tasks text-blue-600 mr-3"></i>
                        Email Workflow Guide
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Step 1 -->
                        <div class="text-center">
                            <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <span class="text-2xl font-bold text-blue-600">1</span>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Compose</h3>
                            <p class="text-gray-600">Start by clicking "Compose Email" and fill in recipients, subject, and content using the rich text editor.</p>
                        </div>

                        <!-- Step 2 -->
                        <div class="text-center">
                            <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <span class="text-2xl font-bold text-orange-600">2</span>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Draft & Review</h3>
                            <p class="text-gray-600">Save as draft if needed, add attachments, and use AI assistance for professional content generation.</p>
                        </div>

                        <!-- Step 3 -->
                        <div class="text-center">
                            <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <span class="text-2xl font-bold text-green-600">3</span>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Send & Track</h3>
                            <p class="text-gray-600">Review all details, send the email, and track it in your email dashboard for future reference.</p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Troubleshooting Section -->
            <section class="mb-12">
                <div class="bg-white border border-gray-200 rounded-lg p-6 shadow-sm">
                    <h2 class="text-2xl font-semibold text-gray-900 mb-6">
                        <i class="fas fa-tools text-blue-600 mr-3"></i>
                        Troubleshooting & FAQ
                    </h2>
                    <div class="space-y-6">
                        <div class="border-l-4 border-blue-500 pl-4">
                            <h3 class="font-semibold text-gray-900 mb-2">Q: My draft is not saving automatically</h3>
                            <p class="text-gray-600">Ensure you're connected to the internet and try manually saving with Ctrl+S. Check that all required fields are filled.</p>
                        </div>
                        <div class="border-l-4 border-green-500 pl-4">
                            <h3 class="font-semibold text-gray-900 mb-2">Q: AI assistant is not generating content</h3>
                            <p class="text-gray-600">Make sure you've selected a document type and specific document. Verify your internet connection and try refreshing the page.</p>
                        </div>
                        <div class="border-l-4 border-orange-500 pl-4">
                            <h3 class="font-semibold text-gray-900 mb-2">Q: Attachments are not uploading</h3>
                            <p class="text-gray-600">Check file size limits and supported formats. Ensure files are not corrupted and try uploading one at a time.</p>
                        </div>
                        <div class="border-l-4 border-purple-500 pl-4">
                            <h3 class="font-semibold text-gray-900 mb-2">Q: Email formatting is lost after sending</h3>
                            <p class="text-gray-600">Some email clients may not support all formatting. Preview your email and use standard formatting for better compatibility.</p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Contact Section -->
            <section class="bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-lg p-6">
                <div class="text-center">
                    <h2 class="text-2xl font-semibold text-gray-900 mb-4">
                        <i class="fas fa-question-circle text-blue-600 mr-3"></i>
                        Need Additional Support?
                    </h2>
                    <p class="text-gray-600 mb-6">
                        If you need help with specific email features or encounter technical issues, 
                        our support resources are here to assist you.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4 justify-center">
                        <a href="{{ route('emails.index') }}"
                            class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                            <i class="fas fa-envelope mr-2"></i>
                            Return to Email Management
                        </a>
                        <a href="{{ route('dashboard') }}"
                            class="inline-flex items-center px-6 py-3 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-lg transition-colors">
                            <i class="fas fa-home mr-2"></i>
                            Go to Dashboard
                        </a>
                    </div>
                </div>
            </section>
        </div>
    </div>
</x-app-layout>
