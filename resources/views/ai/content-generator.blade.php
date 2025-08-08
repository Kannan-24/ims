<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>AI Content Generator - {{ config('app.name') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50 dark:bg-gray-900">
    <div class="min-h-screen">
        <!-- Header -->
        <div class="bg-white dark:bg-gray-800 shadow-sm border-b border-gray-200 dark:border-gray-700">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16">
                    <div class="flex items-center space-x-4">
                        <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-purple-600 rounded-xl flex items-center justify-center">
                            <i class="fas fa-robot text-white text-lg"></i>
                        </div>
                        <div>
                            <h1 class="text-xl font-bold text-gray-900 dark:text-white">AI Content Generator</h1>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Powered by DeepSeek AI via OpenRouter</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-4">
                        <button onclick="testConnection()" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors flex items-center space-x-2">
                            <i class="fas fa-plug"></i>
                            <span>Test Connection</span>
                        </button>
                        <a href="{{ route('dashboard') }}" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
                            <i class="fas fa-arrow-left mr-2"></i>Back to Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Status Card -->
            <div id="status-card" class="mb-8 p-6 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div id="status-icon" class="w-8 h-8 rounded-full bg-gray-300 flex items-center justify-center">
                            <i class="fas fa-spinner animate-spin text-gray-600"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">AI Service Status</h3>
                            <p id="status-message" class="text-sm text-gray-500 dark:text-gray-400">Checking configuration...</p>
                        </div>
                    </div>
                    <button onclick="checkStatus()" class="px-3 py-1 text-sm bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">
                        <i class="fas fa-refresh mr-1"></i>Refresh
                    </button>
                </div>
            </div>

            <!-- Tabs -->
            <div class="mb-8">
                <div class="border-b border-gray-200 dark:border-gray-700">
                    <nav class="-mb-px flex space-x-8">
                        <button onclick="switchTab('email')" id="tab-email" class="tab-button active py-2 px-1 border-b-2 border-blue-500 font-medium text-sm text-blue-600 dark:text-blue-400">
                            <i class="fas fa-envelope mr-2"></i>Email Generator
                        </button>
                        <button onclick="switchTab('quotation')" id="tab-quotation" class="tab-button py-2 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300">
                            <i class="fas fa-file-contract mr-2"></i>Quotation Terms
                        </button>
                        <button onclick="switchTab('custom')" id="tab-custom" class="tab-button py-2 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300">
                            <i class="fas fa-magic mr-2"></i>Custom Content
                        </button>
                    </nav>
                </div>
            </div>

            <!-- Email Generator Tab -->
            <div id="email-tab" class="tab-content">
                <div class="grid lg:grid-cols-2 gap-8">
                    <!-- Email Form -->
                    <div class="space-y-6">
                        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">Email Content Generator</h2>
                            
                            <form id="email-form" class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Email Type</label>
                                    <select name="type" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                                        <option value="invoice">Invoice Email</option>
                                        <option value="quotation">Quotation Email</option>
                                        <option value="payment_reminder">Payment Reminder</option>
                                        <option value="follow_up">Follow Up</option>
                                        <option value="thank_you">Thank You</option>
                                        <option value="general">General Business</option>
                                    </select>
                                </div>

                                <div class="grid md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Recipient Name</label>
                                        <input type="text" name="recipient_name" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white" placeholder="John Doe">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Company Name</label>
                                        <input type="text" name="company_name" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white" placeholder="ABC Company">
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Subject Context</label>
                                    <input type="text" name="subject" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white" placeholder="Invoice for Services Rendered">
                                </div>

                                <div class="grid md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Invoice/Quote Number</label>
                                        <input type="text" name="invoice_number" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white" placeholder="INV-2024-001">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Amount</label>
                                        <input type="number" name="amount" step="0.01" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white" placeholder="15000.00">
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Due Date</label>
                                    <input type="date" name="due_date" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Additional Context</label>
                                    <textarea name="context" rows="3" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white" placeholder="Any additional information or special instructions..."></textarea>
                                </div>

                                <button type="submit" class="w-full px-4 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors flex items-center justify-center space-x-2">
                                    <i class="fas fa-magic"></i>
                                    <span>Generate Email Content</span>
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Email Output -->
                    <div class="space-y-6">
                        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Generated Email Content</h3>
                                <button onclick="copyToClipboard('email-output')" class="px-3 py-1 text-sm bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">
                                    <i class="fas fa-copy mr-1"></i>Copy
                                </button>
                            </div>
                            <div id="email-output" class="min-h-96 p-4 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-lg">
                                <div class="flex items-center justify-center h-32 text-gray-500 dark:text-gray-400">
                                    <div class="text-center">
                                        <i class="fas fa-envelope text-3xl mb-2"></i>
                                        <p>Generated email content will appear here</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quotation Terms Tab -->
            <div id="quotation-tab" class="tab-content hidden">
                <div class="grid lg:grid-cols-2 gap-8">
                    <!-- Quotation Form -->
                    <div class="space-y-6">
                        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">Quotation Terms Generator</h2>
                            
                            <form id="quotation-form" class="space-y-4">
                                <div class="grid md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Company Name</label>
                                        <input type="text" name="company_name" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white" placeholder="SKM & Company">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Business Type</label>
                                        <input type="text" name="business_type" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white" placeholder="Software Development">
                                    </div>
                                </div>

                                <div class="grid md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Quotation Amount</label>
                                        <input type="number" name="quotation_amount" step="0.01" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white" placeholder="50000.00">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Validity Period (Days)</label>
                                        <input type="number" name="validity_period" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white" placeholder="30" value="30">
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Payment Terms</label>
                                    <input type="text" name="payment_terms" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white" placeholder="50% advance, 50% on completion">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Delivery Terms</label>
                                    <input type="text" name="delivery_terms" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white" placeholder="Delivery within 30 days">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Items/Services Description</label>
                                    <textarea name="items_description" rows="3" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white" placeholder="Web application development, mobile app, maintenance..."></textarea>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Special Conditions</label>
                                    <textarea name="special_conditions" rows="2" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white" placeholder="Any special conditions or requirements..."></textarea>
                                </div>

                                <button type="submit" class="w-full px-4 py-3 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors flex items-center justify-center space-x-2">
                                    <i class="fas fa-file-contract"></i>
                                    <span>Generate Quotation Terms</span>
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Quotation Output -->
                    <div class="space-y-6">
                        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Generated Terms & Conditions</h3>
                                <button onclick="copyToClipboard('quotation-output')" class="px-3 py-1 text-sm bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">
                                    <i class="fas fa-copy mr-1"></i>Copy
                                </button>
                            </div>
                            <div id="quotation-output" class="min-h-96 p-4 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-lg">
                                <div class="flex items-center justify-center h-32 text-gray-500 dark:text-gray-400">
                                    <div class="text-center">
                                        <i class="fas fa-file-contract text-3xl mb-2"></i>
                                        <p>Generated terms & conditions will appear here</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Custom Content Tab -->
            <div id="custom-tab" class="tab-content hidden">
                <div class="grid lg:grid-cols-2 gap-8">
                    <!-- Custom Form -->
                    <div class="space-y-6">
                        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">Custom Content Generator</h2>
                            
                            <form id="custom-form" class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Your Prompt</label>
                                    <textarea name="prompt" rows="6" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white" placeholder="Enter your custom prompt here. For example: 'Write a professional email to follow up on a project proposal...'"></textarea>
                                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Describe exactly what content you want to generate.</p>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Context Information (Optional)</label>
                                    <div class="space-y-2">
                                        <input type="text" name="context[customer_name]" placeholder="Customer Name" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                                        <input type="text" name="context[project_name]" placeholder="Project Name" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                                        <input type="text" name="context[amount]" placeholder="Amount" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                                        <input type="text" name="context[date]" placeholder="Date" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                                        <textarea name="context[additional_info]" rows="2" placeholder="Additional Information" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white"></textarea>
                                    </div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Add relevant context to improve the generated content.</p>
                                </div>

                                <button type="submit" class="w-full px-4 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors flex items-center justify-center space-x-2">
                                    <i class="fas fa-magic"></i>
                                    <span>Generate Custom Content</span>
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Custom Output -->
                    <div class="space-y-6">
                        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Generated Custom Content</h3>
                                <button onclick="copyToClipboard('custom-output')" class="px-3 py-1 text-sm bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">
                                    <i class="fas fa-copy mr-1"></i>Copy
                                </button>
                            </div>
                            <div id="custom-output" class="min-h-96 p-4 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-lg">
                                <div class="flex items-center justify-center h-32 text-gray-500 dark:text-gray-400">
                                    <div class="text-center">
                                        <i class="fas fa-magic text-3xl mb-2"></i>
                                        <p>Generated custom content will appear here</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Toast Container -->
    <div id="toast-container" class="fixed top-4 right-4 z-50 space-y-2"></div>

    <script>
        // CSRF Token setup
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // Check status on page load
        document.addEventListener('DOMContentLoaded', function() {
            checkStatus();
        });

        // Tab switching
        function switchTab(tab) {
            // Hide all tabs
            document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
            document.querySelectorAll('.tab-button').forEach(el => {
                el.classList.remove('active', 'border-blue-500', 'text-blue-600', 'dark:text-blue-400');
                el.classList.add('border-transparent', 'text-gray-500', 'dark:text-gray-400');
            });

            // Show selected tab
            document.getElementById(tab + '-tab').classList.remove('hidden');
            const activeTab = document.getElementById('tab-' + tab);
            activeTab.classList.add('active', 'border-blue-500', 'text-blue-600', 'dark:text-blue-400');
            activeTab.classList.remove('border-transparent', 'text-gray-500', 'dark:text-gray-400');
        }

        // Check AI service status
        async function checkStatus() {
            try {
                const response = await fetch('/ai/status', {
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    }
                });
                const data = await response.json();
                
                const statusIcon = document.getElementById('status-icon');
                const statusMessage = document.getElementById('status-message');
                
                if (data.success && data.data.configured) {
                    statusIcon.innerHTML = '<i class="fas fa-check text-white"></i>';
                    statusIcon.className = 'w-8 h-8 rounded-full bg-green-500 flex items-center justify-center';
                    statusMessage.textContent = `AI service is configured and ready (Model: ${data.data.model})`;
                } else {
                    statusIcon.innerHTML = '<i class="fas fa-exclamation text-white"></i>';
                    statusIcon.className = 'w-8 h-8 rounded-full bg-yellow-500 flex items-center justify-center';
                    statusMessage.textContent = 'AI service is not configured. Please set your OpenAI API key.';
                }
            } catch (error) {
                const statusIcon = document.getElementById('status-icon');
                const statusMessage = document.getElementById('status-message');
                statusIcon.innerHTML = '<i class="fas fa-times text-white"></i>';
                statusIcon.className = 'w-8 h-8 rounded-full bg-red-500 flex items-center justify-center';
                statusMessage.textContent = 'Failed to check AI service status';
            }
        }

        // Test connection
        async function testConnection() {
            showToast('Testing connection...', 'info');
            try {
                const response = await fetch('/ai/test-connection', {
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    }
                });
                const data = await response.json();
                
                if (data.success) {
                    showToast('Connection successful!', 'success');
                } else {
                    showToast('Connection failed: ' + data.message, 'error');
                }
            } catch (error) {
                showToast('Connection test failed', 'error');
            }
        }

        // Form submissions
        document.getElementById('email-form').addEventListener('submit', async function(e) {
            e.preventDefault();
            await generateContent('email', '/ai/generate-email', 'email-output');
        });

        document.getElementById('quotation-form').addEventListener('submit', async function(e) {
            e.preventDefault();
            await generateContent('quotation', '/ai/generate-quotation-terms', 'quotation-output');
        });

        document.getElementById('custom-form').addEventListener('submit', async function(e) {
            e.preventDefault();
            await generateContent('custom', '/ai/generate-custom', 'custom-output');
        });

        // Generate content
        async function generateContent(type, endpoint, outputId) {
            const form = document.getElementById(type + '-form');
            const output = document.getElementById(outputId);
            const formData = new FormData(form);
            
            // Show loading
            output.innerHTML = `
                <div class="flex items-center justify-center h-32 text-blue-600">
                    <div class="text-center">
                        <i class="fas fa-spinner animate-spin text-3xl mb-2"></i>
                        <p>Generating content...</p>
                    </div>
                </div>
            `;

            try {
                const response = await fetch(endpoint, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: formData
                });

                const data = await response.json();

                if (data.success) {
                    output.innerHTML = `<div class="whitespace-pre-wrap text-gray-900 dark:text-gray-100">${data.content}</div>`;
                    showToast('Content generated successfully!', 'success');
                } else {
                    output.innerHTML = `
                        <div class="text-center text-red-600 dark:text-red-400">
                            <i class="fas fa-exclamation-triangle text-3xl mb-2"></i>
                            <p>Error: ${data.message}</p>
                        </div>
                    `;
                    showToast('Generation failed: ' + data.message, 'error');
                }
            } catch (error) {
                output.innerHTML = `
                    <div class="text-center text-red-600 dark:text-red-400">
                        <i class="fas fa-exclamation-triangle text-3xl mb-2"></i>
                        <p>Network error occurred</p>
                    </div>
                `;
                showToast('Network error occurred', 'error');
            }
        }

        // Copy to clipboard
        async function copyToClipboard(elementId) {
            const element = document.getElementById(elementId);
            const text = element.textContent;
            
            if (text.trim() === '' || text.includes('will appear here')) {
                showToast('No content to copy', 'warning');
                return;
            }

            try {
                await navigator.clipboard.writeText(text);
                showToast('Content copied to clipboard!', 'success');
            } catch (error) {
                showToast('Failed to copy content', 'error');
            }
        }

        // Toast notifications
        function showToast(message, type = 'info', duration = 5000) {
            const toastContainer = document.getElementById('toast-container');
            const toast = document.createElement('div');
            
            const bgColors = {
                success: 'bg-green-500',
                error: 'bg-red-500',
                warning: 'bg-yellow-500',
                info: 'bg-blue-500'
            };

            const icons = {
                success: 'fas fa-check',
                error: 'fas fa-times',
                warning: 'fas fa-exclamation',
                info: 'fas fa-info'
            };

            toast.className = `${bgColors[type]} text-white px-6 py-3 rounded-lg shadow-lg flex items-center space-x-3 transform transition-all duration-300 translate-x-full opacity-0`;
            toast.innerHTML = `
                <i class="${icons[type]}"></i>
                <span>${message}</span>
                <button onclick="this.parentElement.remove()" class="ml-4 hover:text-gray-200">
                    <i class="fas fa-times"></i>
                </button>
            `;

            toastContainer.appendChild(toast);

            // Animate in
            setTimeout(() => {
                toast.classList.remove('translate-x-full', 'opacity-0');
                toast.classList.add('translate-x-0', 'opacity-100');
            }, 100);

            // Auto remove
            setTimeout(() => {
                toast.classList.add('translate-x-full', 'opacity-0');
                setTimeout(() => toast.remove(), 300);
            }, duration);
        }
    </script>
</body>
</html>
