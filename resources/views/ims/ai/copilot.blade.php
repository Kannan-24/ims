<x-app-layout>
    <x-slot name="title">
        {{ __('AI Copilot') }} - {{ config('app.name', 'ATMS') }}
    </x-slot>

    <div class="py-6 mt-20 ml-4 sm:ml-64 min-h-screen bg-gradient-to-br from-gray-900 via-blue-900 to-purple-900">
        <div class="w-full mx-auto max-w-7xl sm:px-6 lg:px-8">
            <!-- Header with AI Branding -->
            <div class="mb-8 text-center">
                <div class="inline-flex items-center justify-center w-20 h-20 mx-auto mb-4 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full shadow-lg">
                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z">
                        </path>
                    </svg>
                </div>
                <h1 class="text-4xl font-bold text-white mb-2 bg-gradient-to-r from-blue-400 to-purple-400 bg-clip-text text-transparent drop-shadow-lg">
                    AI Copilot Assistant
                </h1>
                <p class="text-gray-300 text-lg">Powered by DeepSeek AI - Your intelligent business companion</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
                <!-- Left Sidebar - Quick Actions -->
                <div class="lg:col-span-1">
                    <div class="bg-gray-800/50 backdrop-blur-xl border border-gray-700/50 rounded-2xl p-6 shadow-xl">
                        <h3 class="text-xl font-semibold text-white mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-blue-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                            Quick Actions
                        </h3>
                        <!-- Scrollable Quick Actions -->
                        <div class="space-y-3 max-h-96 overflow-y-auto custom-scrollbar pr-1">
                            @foreach([
                                ['Welcome Email', 'Generate customer welcome', 'Write a professional welcome email for a new customer', 'blue'],
                                ['Quotation T&C', 'Business terms', 'Create professional quotation terms and conditions for business services', 'green'],
                                ['Payment Follow-up', 'Invoice reminders', 'Write a follow-up email for pending invoice payment', 'yellow'],
                                ['Product Description', 'Marketing content', 'Create a professional product description for marketing purposes', 'purple'],
                                ['Business Proposal', 'Professional templates', 'Generate a business proposal template', 'red'],
                                ['Send Invoice', 'Invoice with payment instructions', 'Draft an invoice for a customer including payment details and terms', 'indigo'],
                                ['Send Quotation', 'Quotation with price and terms', 'Send a quotation for business services including pricing and validity period', 'cyan'],
                                ['Greeting Message', 'Event/Partnership greetings', 'Write an official greeting message for a company event or new partnership', 'pink'],
                                ['Feedback Request', 'Get client feedback', 'Create a feedback request email for clients after service delivery', 'emerald'],
                                ['Company Announcement', 'Product launch/update', 'Draft a company announcement about a new product launch or important update', 'orange'],
                            ] as [$title, $desc, $prompt, $color])
                            <button onclick="setPrompt('{{ $prompt }}')"
                                class="w-full text-left p-3 bg-{{ $color }}-500/20 hover:bg-{{ $color }}-500/30 border border-{{ $color }}-500/30 rounded-lg transition-all duration-200 text-{{ $color }}-200 hover:text-{{ $color }}-100">
                                <div class="font-medium">{{ $title }}</div>
                                <div class="text-xs opacity-70">{{ $desc }}</div>
                            </button>
                            @endforeach
                        </div>
                        <!-- AI Stats -->
                        <div class="mt-6 p-4 bg-gradient-to-r from-blue-500/10 to-purple-500/10 rounded-lg border border-blue-500/20">
                            <h4 class="text-sm font-medium text-blue-200 mb-2">AI Usage Today</h4>
                            <div class="flex justify-between text-xs text-gray-300">
                                <span>Requests: <span id="requestCount" class="text-blue-400 font-semibold">0</span></span>
                                <span>Success: <span id="successCount" class="text-green-400 font-semibold">0</span></span>
                            </div>
                        </div>
                        <button type="button" onclick="testConnection()"
                            class="px-3 py-2 bg-orange-600/50 text-orange-300 rounded-lg hover:bg-orange-600 transition-all duration-200 text-sm">
                            Test AI
                        </button>
                        <div id="aiStatus" class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-green-500/20 border border-green-500/30 mt-4">
                            <div class="w-2 h-2 bg-green-400 rounded-full mr-2 animate-pulse"></div>
                            <span class="text-green-300">AI Service Ready</span>
                        </div>
                        <div id="responseTimeIndicator" class="mt-2 text-xs text-blue-200 hidden">
                            Avg Response: <span id="avgResponseTime">-</span>
                        </div>
                    </div>
                </div>

                <!-- Main Chat Area -->
                <div class="lg:col-span-3">
                    <div class="bg-gray-800/50 backdrop-blur-xl border border-gray-700/50 rounded-2xl shadow-xl overflow-hidden">
                        <!-- Chat Header -->
                        <div class="bg-gradient-to-r from-blue-600 to-purple-600 p-4">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="w-3 h-3 bg-green-400 rounded-full mr-2 animate-pulse"></div>
                                    <span class="text-white font-medium">AI Assistant Online</span>
                                </div>
                                <button onclick="clearChat()" class="text-white/70 hover:text-white transition-colors" title="Clear chat">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                        </path>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <!-- Chat Messages Container -->
                        <div id="chat-container" class="h-[32rem] overflow-y-auto p-6 space-y-4 bg-gray-900/50">
                            <!-- Welcome Message -->
                            <div class="flex items-start space-x-3">
                                <div class="w-8 h-8 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center flex-shrink-0">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z">
                                        </path>
                                    </svg>
                                </div>
                                <div class="bg-blue-500/20 border border-blue-500/30 rounded-lg p-4 max-w-3xl">
                                    <p class="text-blue-100">👋 Hello! I'm your AI assistant powered by DeepSeek AI. I
                                        can help you with:</p>
                                    <ul class="mt-2 text-blue-200 text-sm list-disc list-inside space-y-1">
                                        <li>Writing professional emails and business content</li>
                                        <li>Creating quotation terms and conditions</li>
                                        <li>Generating invoices and payment requests</li>
                                        <li>Marketing descriptions & company announcements</li>
                                        <li>Sending greetings, feedback requests, and more</li>
                                        <li>Custom business document templates</li>
                                    </ul>
                                    <p class="mt-2 text-blue-200 text-sm">Use the quick actions on the left or type
                                        your own prompt below!</p>
                                </div>
                            </div>
                        </div>

                        <!-- Input Area -->
                        <div class="border-t border-gray-700/50 p-4 bg-gray-800/30">
                            <form id="ai-form" class="space-y-4">
                                @csrf
                                <div class="flex space-x-3">
                                    <div class="flex-1">
                                        <textarea id="prompt" name="prompt" rows="3"
                                            class="w-full px-4 py-3 bg-gray-700/50 backdrop-blur-sm text-white border border-gray-600/50 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none transition-all duration-200 placeholder-gray-400"
                                            placeholder="Type your message or question here..."></textarea>
                                    </div>
                                    <div class="flex flex-col space-y-2">
                                        <button type="submit"
                                            class="px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:scale-105 flex items-center">
                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                                            </svg>
                                            Send
                                        </button>
                                        <button type="button" onclick="clearPrompt()"
                                            class="px-3 py-2 bg-gray-600/50 text-gray-300 rounded-lg hover:bg-gray-600 transition-all duration-200 text-sm">
                                            Clear
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Custom scrollbar for Quick Actions and Chat */
        .custom-scrollbar::-webkit-scrollbar,
        #chat-container::-webkit-scrollbar {
            width: 8px;
            background: transparent;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb,
        #chat-container::-webkit-scrollbar-thumb {
            background: linear-gradient(135deg, #3b82f6 40%, #8b5cf6 100%);
            border-radius: 8px;
        }
        .custom-scrollbar::-webkit-scrollbar-track,
        #chat-container::-webkit-scrollbar-track {
            background: rgba(31, 41, 55, 0.3);
            border-radius: 8px;
        }
        .custom-scrollbar,
        #chat-container {
            scrollbar-width: thin;
            scrollbar-color: #8b5cf6 #1f2937;
        }
        /* Responsive chat height */
        @media (max-width: 1024px) {
            #chat-container { height: 24rem; }
        }
        @media (max-width: 640px) {
            #chat-container { height: 16rem; }
        }
    </style>

    <script>
        let requestCount = 0;
        let successCount = 0;
        let responseTimes = [];

        const form = document.getElementById('ai-form');
        const chatContainer = document.getElementById('chat-container');
        const promptInput = document.getElementById('prompt');

        // Load stats from localStorage
        requestCount = parseInt(localStorage.getItem('aiRequestCount') || '0');
        successCount = parseInt(localStorage.getItem('aiSuccessCount') || '0');
        responseTimes = JSON.parse(localStorage.getItem('aiResponseTimes') || '[]');
        updateStats();
        updateStatusIndicator();

        function updateStats() {
            document.getElementById('requestCount').textContent = requestCount;
            document.getElementById('successCount').textContent = successCount;
            localStorage.setItem('aiRequestCount', requestCount);
            localStorage.setItem('aiSuccessCount', successCount);
            localStorage.setItem('aiResponseTimes', JSON.stringify(responseTimes.slice(-10)));
        }

        function updateStatusIndicator() {
            const statusEl = document.getElementById('aiStatus');
            const responseTimeEl = document.getElementById('responseTimeIndicator');
            const avgTimeEl = document.getElementById('avgResponseTime');
            if (responseTimes.length > 0) {
                const avgTime = responseTimes.reduce((a, b) => a + b, 0) / responseTimes.length;
                avgTimeEl.textContent = `${(avgTime / 1000).toFixed(1)}s`;
                responseTimeEl.classList.remove('hidden');
                if (avgTime > 30000) {
                    statusEl.innerHTML = `
                        <div class="w-2 h-2 bg-orange-400 rounded-full mr-2 animate-pulse"></div>
                        <span class="text-orange-300">AI Service Slow</span>
                    `;
                    statusEl.className = 'inline-flex items-center px-3 py-1 rounded-full text-sm bg-orange-500/20 border border-orange-500/30';
                } else {
                    statusEl.innerHTML = `
                        <div class="w-2 h-2 bg-green-400 rounded-full mr-2 animate-pulse"></div>
                        <span class="text-green-300">AI Service Ready</span>
                    `;
                    statusEl.className = 'inline-flex items-center px-3 py-1 rounded-full text-sm bg-green-500/20 border border-green-500/30';
                }
            }
        }

        function setAIStatus(status, message) {
            const statusEl = document.getElementById('aiStatus');
            switch(status) {
                case 'working':
                    statusEl.innerHTML = `
                        <div class="w-2 h-2 bg-blue-400 rounded-full mr-2 animate-spin border border-white border-t-transparent"></div>
                        <span class="text-blue-300">${message || 'Processing...'}</span>
                    `;
                    statusEl.className = 'inline-flex items-center px-3 py-1 rounded-full text-sm bg-blue-500/20 border border-blue-500/30';
                    break;
                case 'error':
                    statusEl.innerHTML = `
                        <div class="w-2 h-2 bg-red-400 rounded-full mr-2"></div>
                        <span class="text-red-300">${message || 'Service Error'}</span>
                    `;
                    statusEl.className = 'inline-flex items-center px-3 py-1 rounded-full text-sm bg-red-500/20 border border-red-500/30';
                    break;
                case 'ready':
                default:
                    updateStatusIndicator();
                    break;
            }
        }

        function setPrompt(text) {
            promptInput.value = text;
            promptInput.focus();
        }

        function clearPrompt() {
            promptInput.value = '';
            promptInput.focus();
        }

        async function testConnection() {
            try {
                addMessage('🔧 Testing AI connection...', false);
                const response = await fetch("{{ url('/ims/ai-test-connection') }}", {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                });
                const data = await response.json();
                if (data.success) {
                    addMessage(
                        `✅ AI Connection Test Successful!\n\nStatus: ${data.status}\nMessage: ${data.message}\n\nThe AI service is working properly. You can now use the assistant.`,
                        false);
                } else {
                    addMessage(
                        `❌ AI Connection Test Failed!\n\nStatus: ${data.status}\nMessage: ${data.message}\n\nPlease check:\n- Your internet connection\n- API key configuration\n- Server logs for more details`,
                        false);
                }
            } catch (error) {
                addMessage(
                    `❌ Connection Test Error: ${error.message}\n\nThis might be due to:\n- Network connectivity issues\n- Server configuration problems\n- Authentication issues\n\nPlease contact support if the issue persists.`,
                    false);
                console.error('Connection test error:', error);
            }
        }

        function clearChat() {
            const messages = chatContainer.querySelectorAll('.chat-message');
            messages.forEach(msg => msg.remove());
        }

        function addMessage(content, isUser = false) {
            const messageDiv = document.createElement('div');
            messageDiv.className = 'flex items-start space-x-3 chat-message';
            if (isUser) {
                messageDiv.innerHTML = `
                    <div class="w-8 h-8 bg-gradient-to-r from-green-500 to-blue-500 rounded-full flex items-center justify-center flex-shrink-0">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <div class="bg-green-500/20 border border-green-500/30 rounded-lg p-4 max-w-3xl ml-auto">
                        <p class="text-green-100">${content}</p>
                    </div>
                `;
            } else {
                messageDiv.innerHTML = `
                    <div class="w-8 h-8 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center flex-shrink-0">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                        </svg>
                    </div>
                    <div class="bg-blue-500/20 border border-blue-500/30 rounded-lg p-4 max-w-3xl">
                        <div class="text-blue-100 whitespace-pre-wrap">${content}</div>
                        <div class="mt-3 flex space-x-2">
                            <button onclick="copyToClipboard(this)" class="text-xs bg-blue-600/30 hover:bg-blue-600/50 px-3 py-1 rounded transition-colors text-blue-200">
                                📋 Copy
                            </button>
                            <button onclick="regenerateResponse()" class="text-xs bg-purple-600/30 hover:bg-purple-600/50 px-3 py-1 rounded transition-colors text-purple-200">
                                🔄 Regenerate
                            </button>
                        </div>
                    </div>
                `;
            }
            chatContainer.appendChild(messageDiv);
            chatContainer.scrollTop = chatContainer.scrollHeight;
        }

        function addLoadingMessage() {
            const loadingDiv = document.createElement('div');
            loadingDiv.className = 'flex items-start space-x-3 chat-message loading-message';
            loadingDiv.innerHTML = `
                <div class="w-8 h-8 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center flex-shrink-0">
                    <div class="w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin"></div>
                </div>
                <div class="bg-gray-700/50 border border-gray-600/50 rounded-lg p-4 max-w-3xl">
                    <div class="flex items-center space-x-2 text-gray-300">
                        <div class="w-2 h-2 bg-blue-400 rounded-full animate-bounce"></div>
                        <div class="w-2 h-2 bg-blue-400 rounded-full animate-bounce" style="animation-delay: 0.1s"></div>
                        <div class="w-2 h-2 bg-blue-400 rounded-full animate-bounce" style="animation-delay: 0.2s"></div>
                        <span class="ml-2">AI is thinking...</span>
                    </div>
                </div>
            `;
            chatContainer.appendChild(loadingDiv);
            chatContainer.scrollTop = chatContainer.scrollHeight;
        }

        function removeLoadingMessage() {
            const loadingMessage = chatContainer.querySelector('.loading-message');
            if (loadingMessage) {
                loadingMessage.remove();
            }
        }

        function copyToClipboard(button) {
            const content = button.closest('.bg-blue-500\\/20').querySelector('.text-blue-100').textContent;
            navigator.clipboard.writeText(content).then(() => {
                button.textContent = '✅ Copied!';
                setTimeout(() => {
                    button.textContent = '📋 Copy';
                }, 2000);
            });
        }

        let lastPrompt = '';

        function regenerateResponse() {
            if (lastPrompt) {
                promptInput.value = lastPrompt;
                form.dispatchEvent(new Event('submit'));
            }
        }

        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            const prompt = promptInput.value.trim();
            if (!prompt) return;
            lastPrompt = prompt;
            requestCount++;
            updateStats();
            addMessage(prompt, true);
            promptInput.value = '';
            addLoadingMessage();
            setAIStatus('working', 'AI is thinking...');
            const startTime = Date.now();
            try {
                const response = await fetch("{{ url('/ims/generate-content') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ prompt })
                });
                const endTime = Date.now();
                const responseTime = endTime - startTime;
                responseTimes.push(responseTime);
                responseTimes = responseTimes.slice(-10);
                if (!response.ok) {
                    throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                }
                const data = await response.json();
                removeLoadingMessage();
                if (data.content) {
                    addMessage(data.content);
                    successCount++;
                    updateStats();
                    setAIStatus('ready');
                } else if (data.error) {
                    addMessage(
                        `❌ AI Error: ${data.error}\n\nPlease try:\n- Simplifying your request\n- Checking your internet connection\n- Using the "Test AI" button to verify connectivity`
                    );
                    setAIStatus('error', 'Service Error');
                } else {
                    addMessage(
                        '❌ Sorry, I encountered an unexpected error while generating content. Please try the "Test AI" button to check connectivity.'
                    );
                    setAIStatus('error', 'Unexpected Error');
                }
            } catch (error) {
                removeLoadingMessage();
                let errorMessage = '❌ Network error occurred. ';
                if (error.message.includes('HTTP 401')) {
                    errorMessage += 'Authentication failed. Please check your API key configuration.';
                    setAIStatus('error', 'Auth Error');
                } else if (error.message.includes('HTTP 429')) {
                    errorMessage += 'Rate limit exceeded. Please wait a moment and try again.';
                    setAIStatus('error', 'Rate Limited');
                } else if (error.message.includes('HTTP 500')) {
                    errorMessage += 'Server error occurred. Please try again or contact support.';
                    setAIStatus('error', 'Server Error');
                } else if (error.message.includes('Failed to fetch') || error.message.includes('NetworkError')) {
                    errorMessage += 'Connection failed. Please check your internet connection and try again.';
                    setAIStatus('error', 'Connection Failed');
                } else {
                    errorMessage += `${error.message}. Please use the "Test AI" button to diagnose the issue.`;
                    setAIStatus('error', 'Unknown Error');
                }
                addMessage(errorMessage);
                console.error('AI Request Error:', error);
            }
        });

        // Auto-resize textarea
        promptInput.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = Math.min(this.scrollHeight, 150) + 'px';
        });

        // Enter to submit (Shift+Enter for new line)
        promptInput.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                form.dispatchEvent(new Event('submit'));
            }
        });
    </script>
</x-app-layout>
