<x-app-layout>
    <x-slot name="title">
        {{ __('AI Assistant') }} - {{ config('app.name', 'IMS') }}
    </x-slot>

    <div class="bg-white" x-data="aiAssistant()" x-init="init()">
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
                            <span class="text-sm font-medium text-gray-500">AI Assistant</span>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>

        <!-- Header -->
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-robot text-white text-lg"></i>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">AI Assistant Studio</h1>
                        <p class="text-sm text-gray-600 mt-1">Powered by Google Gemini AI • Your intelligent business companion</p>
                    </div>
                </div>
                <div class="flex items-center space-x-3">
                    <!-- Status Badge -->
                    <div class="inline-flex items-center px-3 py-1.5 bg-green-100 text-green-800 rounded-full text-sm font-medium border border-green-200">
                        <div class="w-2 h-2 bg-green-500 rounded-full mr-2 animate-pulse"></div>
                        AI Online
                    </div>
                    <!-- Help Button -->
                    <button @click="showHelpModal = true" 
                            class="inline-flex items-center px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition-colors">
                        <i class="fas fa-question-circle w-4 h-4 mr-2"></i>
                        Help
                    </button>
                    <!-- Test Connection Button -->
                    <button @click="testConnection()" 
                            class="inline-flex items-center px-4 py-2 bg-orange-600 hover:bg-orange-700 text-white text-sm font-medium rounded-lg transition-colors">
                        <i class="fas fa-network-wired w-4 h-4 mr-2"></i>
                        Test AI
                    </button>
                    <!-- Clear Chat Button -->
                    <button @click="clearChat()" 
                            class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-colors">
                        <i class="fas fa-trash w-4 h-4 mr-2"></i>
                        Clear Chat
                    </button>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="bg-gray-50 min-h-screen">
            <!-- Stats Cards -->
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <div class="flex items-center">
                            <div class="p-3 bg-blue-100 rounded-full">
                                <i class="fas fa-comments text-blue-600 text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600">Requests Today</p>
                                <p class="text-2xl font-bold text-gray-900" x-text="stats.requests"></p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <div class="flex items-center">
                            <div class="p-3 bg-green-100 rounded-full">
                                <i class="fas fa-check-circle text-green-600 text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600">Success Rate</p>
                                <p class="text-2xl font-bold text-gray-900" x-text="stats.successRate + '%'"></p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <div class="flex items-center">
                            <div class="p-3 bg-purple-100 rounded-full">
                                <i class="fas fa-clock text-purple-600 text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600">Avg Response</p>
                                <p class="text-2xl font-bold text-gray-900" x-text="stats.avgResponse + 's'"></p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <div class="flex items-center">
                            <div class="p-3 bg-indigo-100 rounded-full">
                                <i class="fas fa-magic text-indigo-600 text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600">AI Model</p>
                                <p class="text-sm font-bold text-gray-900">Gemini Pro</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-6">
                    <!-- Main Chat Area -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                        <!-- Chat Header -->
                        <div class="bg-gradient-to-r from-blue-500 to-indigo-600 px-6 py-4">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-robot text-white"></i>
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-semibold text-white">{{ config('app.name', 'IMS') }} AI Assistant</h3>
                                        <p class="text-blue-100 text-sm">Powered by Google Gemini AI • Customized for {{ config('app.company_name', config('app.name', 'Your Company')) }}</p>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-4">
                                    <div class="text-white text-sm">
                                        <span x-text="new Date().toLocaleDateString()"></span>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <button @click="showQuickActions = !showQuickActions" 
                                                class="text-white/80 hover:text-white transition-colors p-2 rounded-lg hover:bg-white/10"
                                                title="Quick Templates">
                                            <i class="fas fa-magic"></i>
                                        </button>
                                        <button @click="exportChat()" 
                                                class="text-white/80 hover:text-white transition-colors p-2 rounded-lg hover:bg-white/10"
                                                title="Export Chat">
                                            <i class="fas fa-download"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Quick Actions Dropdown (Compact) -->
                        <div x-show="showQuickActions" 
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 transform -translate-y-2"
                             x-transition:enter-end="opacity-100 transform translate-y-0"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100 transform translate-y-0"
                             x-transition:leave-end="opacity-0 transform -translate-y-2"
                             class="bg-blue-50 border-b border-blue-200 p-4"
                             style="display: none;">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                                <template x-for="template in quickTemplates" :key="template.title">
                                    <button @click="setPrompt(template.prompt); showQuickActions = false"
                                            class="text-left p-3 bg-white hover:bg-blue-50 border border-blue-200 hover:border-blue-300 rounded-lg transition-all duration-200 group">
                                        <div class="flex items-start space-x-3">
                                            <span x-text="template.emoji" class="text-lg group-hover:scale-110 transition-transform"></span>
                                            <div class="flex-1 min-w-0">
                                                <div class="font-medium text-gray-900 group-hover:text-blue-700" x-text="template.title"></div>
                                                <div class="text-xs text-gray-500 mt-1" x-text="template.description"></div>
                                            </div>
                                        </div>
                                    </button>
                                </template>
                            </div>
                        </div>                        <!-- Chat Messages -->
                        <div id="chat-container" class="h-96 overflow-y-auto p-6 space-y-4 bg-gray-50">
                            <!-- Welcome Message -->
                            <div class="flex items-start space-x-3">
                                <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center flex-shrink-0">
                                    <i class="fas fa-robot text-white text-sm"></i>
                                </div>
                                <div class="flex-1 bg-white border border-gray-200 rounded-lg p-4 shadow-sm">
                                    <div class="flex items-center mb-2">
                                        <span class="text-lg mr-2">👋</span>
                                        <h4 class="font-semibold text-gray-900">Welcome to AI Assistant!</h4>
                                    </div>
                                    <p class="text-gray-700 mb-3">I'm your AI assistant powered by Google Gemini. I can help you with:</p>
                                    
                                    <div class="grid grid-cols-2 gap-2 mb-3">
                                        <div class="flex items-center text-sm text-gray-600">
                                            <i class="fas fa-check text-green-500 mr-2"></i>
                                            Professional emails
                                        </div>
                                        <div class="flex items-center text-sm text-gray-600">
                                            <i class="fas fa-check text-green-500 mr-2"></i>
                                            Business proposals
                                        </div>
                                        <div class="flex items-center text-sm text-gray-600">
                                            <i class="fas fa-check text-green-500 mr-2"></i>
                                            Content creation
                                        </div>
                                        <div class="flex items-center text-sm text-gray-600">
                                            <i class="fas fa-check text-green-500 mr-2"></i>
                                            Document templates
                                        </div>
                                    </div>

                                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-3">
                                        <p class="text-blue-800 text-sm">
                                            💡 <strong>Tip:</strong> Use the quick templates above or type your request below!
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Input Area -->
                        <div class="border-t border-gray-200 p-6 bg-white">
                            <form id="ai-form" @submit.prevent="sendMessage()">
                                @csrf
                                <div class="flex space-x-4">
                                    <div class="flex-1">
                                        <textarea id="prompt" x-model="currentPrompt" rows="3"
                                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none"
                                                  placeholder="Type your message or ask me anything..."
                                                  @keydown.enter.exact.prevent="sendMessage()"
                                                  @keydown.enter.shift.prevent="currentPrompt += '\n'"></textarea>
                                        <div class="mt-2 flex justify-between items-center">
                                            <div class="text-xs text-gray-500">
                                                <kbd class="px-2 py-1 bg-gray-100 rounded text-xs">Enter</kbd> to send • 
                                                <kbd class="px-2 py-1 bg-gray-100 rounded text-xs">Shift+Enter</kbd> for new line
                                            </div>
                                            <div class="text-xs text-gray-500" x-show="currentPrompt.length > 0">
                                                <span x-text="currentPrompt.length"></span> characters
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex flex-col space-y-2">
                                        <button type="submit" :disabled="isLoading || !currentPrompt.trim()"
                                                class="px-6 py-3 bg-blue-600 hover:bg-blue-700 disabled:bg-gray-300 text-white rounded-lg font-medium transition-colors flex items-center">
                                            <template x-if="!isLoading">
                                                <div class="flex items-center">
                                                    <i class="fas fa-paper-plane mr-2"></i>
                                                    Send
                                                </div>
                                            </template>
                                            <template x-if="isLoading">
                                                <div class="flex items-center">
                                                    <i class="fas fa-spinner animate-spin mr-2"></i>
                                                    Sending
                                                </div>
                                            </template>
                                        </button>
                                        <button type="button" @click="clearPrompt()"
                                                class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-sm transition-colors">
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

        <!-- Help Modal -->
        <div x-show="showHelpModal" 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-50 overflow-y-auto"
             style="display: none;">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" @click="showHelpModal = false"></div>
                
                <div class="inline-block w-full max-w-2xl my-8 overflow-hidden text-left align-middle transition-all transform bg-white shadow-xl rounded-xl">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">AI Assistant Help</h3>
                    </div>
                    
                    <div class="px-6 py-6 space-y-4">
                        <div>
                            <h4 class="font-medium text-gray-900 mb-2">Getting Started</h4>
                            <p class="text-sm text-gray-600">Use the quick actions on the left or type your custom prompt in the chat area to interact with the AI assistant.</p>
                        </div>
                        
                        <div>
                            <h4 class="font-medium text-gray-900 mb-2">Quick Actions</h4>
                            <ul class="text-sm text-gray-600 space-y-1">
                                <li><strong>Customer Relations:</strong> Welcome emails, payment follow-ups, feedback requests</li>
                                <li><strong>Business Documents:</strong> Quotations, invoices, proposals</li>
                                <li><strong>Marketing:</strong> Product descriptions, announcements, greetings</li>
                            </ul>
                        </div>
                        
                        <div>
                            <h4 class="font-medium text-gray-900 mb-2">Features</h4>
                            <ul class="text-sm text-gray-600 space-y-1">
                                <li>• Professional content generation powered by Google Gemini AI</li>
                                <li>• Copy and share AI responses</li>
                                <li>• Real-time usage statistics</li>
                                <li>• Connection testing and status monitoring</li>
                            </ul>
                        </div>
                        
                        <div>
                            <h4 class="font-medium text-gray-900 mb-2">Keyboard Shortcuts</h4>
                            <p class="text-sm text-gray-600">Press <kbd class="px-2 py-1 bg-gray-100 rounded text-xs">Enter</kbd> to send your message, or <kbd class="px-2 py-1 bg-gray-100 rounded text-xs">Shift+Enter</kbd> for a new line.</p>
                        </div>
                    </div>
                    
                    <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 rounded-b-xl">
                        <button @click="showHelpModal = false"
                                class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                            Got it!
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
                            

    <script>
        function aiAssistant() {
            return {
                // Data
                currentPrompt: '',
                isLoading: false,
                showHelpModal: false,
                showQuickActions: false,
                
                // Stats
                stats: {
                    requests: 0,
                    successRate: 100,
                    avgResponse: 2.1
                },

                // Quick templates with company context
                quickTemplates: [
                    {
                        title: 'Customer Report',
                        description: 'Generate customer analysis for {{ config("app.company_name", config("app.name", "your company")) }}',
                        emoji: '📊',
                        prompt: 'Generate a comprehensive customer analysis report for {{ config("app.company_name", config("app.name", "your company")) }} including key metrics, trends, and recommendations.'
                    },
                    {
                        title: 'Invoice Template',
                        description: 'Create professional invoice content',
                        emoji: '📄',
                        prompt: 'Create a professional invoice template for {{ config("app.company_name", config("app.name", "your company")) }} with proper formatting and payment terms.'
                    },
                    {
                        title: 'Business Email',
                        description: 'Draft professional business communication',
                        emoji: '✉️',
                        prompt: 'Help me draft a professional business email for {{ config("app.company_name", config("app.name", "your company")) }}. Please provide a template I can customize.'
                    },
                    {
                        title: 'Inventory Analysis',
                        description: 'Analyze stock levels and trends',
                        emoji: '📦',
                        prompt: 'Provide insights on inventory management best practices and analysis methods for {{ config("app.company_name", config("app.name", "your company")) }}.'
                    },
                    {
                        title: 'Financial Summary',
                        description: 'Create financial overview and insights',
                        emoji: '💰',
                        prompt: 'Help me create a financial summary template for {{ config("app.company_name", config("app.name", "your company")) }} including key performance indicators.'
                    },
                    {
                        title: 'Marketing Content',
                        description: 'Generate marketing materials',
                        emoji: '📈',
                        prompt: 'Create engaging marketing content for {{ config("app.company_name", config("app.name", "your company")) }} that highlights our key services and value proposition.'
                    }
                ],

                // Messages array
                messages: [],

                init() {
                    this.loadChatHistory();
                    // Welcome message with company context
                    if (this.messages.length === 0) {
                        this.messages.push({
                            type: 'assistant',
                            content: `Welcome to {{ config("app.company_name", config("app.name", "your company")) }} AI Assistant! I'm here to help you with business tasks, reports, analysis, and more. How can I assist you today?`,
                            timestamp: new Date().toLocaleTimeString()
                        });
                    }
                    this.loadStats();
                    this.updateStats();
                },

                loadChatHistory() {
                    const saved = localStorage.getItem('aiChatHistory');
                    if (saved) {
                        this.messages = JSON.parse(saved);
                    }
                },

                saveChatHistory() {
                    localStorage.setItem('aiChatHistory', JSON.stringify(this.messages));
                },

                loadStats() {
                    this.stats.requests = parseInt(localStorage.getItem('aiRequestCount') || '0');
                    const successCount = parseInt(localStorage.getItem('aiSuccessCount') || '0');
                    this.stats.successRate = this.stats.requests > 0 ? Math.round((successCount / this.stats.requests) * 100) : 100;
                    
                    const responseTimes = JSON.parse(localStorage.getItem('aiResponseTimes') || '[]');
                    if (responseTimes.length > 0) {
                        const avgTime = responseTimes.reduce((a, b) => a + b, 0) / responseTimes.length;
                        this.stats.avgResponse = (avgTime / 1000).toFixed(1);
                    }
                },

                updateStats() {
                    localStorage.setItem('aiRequestCount', this.stats.requests);
                    const successCount = Math.round((this.stats.successRate / 100) * this.stats.requests);
                    localStorage.setItem('aiSuccessCount', successCount);
                },

                exportChat() {
                    const chatContent = this.messages.map(msg => 
                        `[${msg.timestamp}] ${msg.type.toUpperCase()}: ${msg.content}`
                    ).join('\n\n');
                    
                    const blob = new Blob([chatContent], { type: 'text/plain' });
                    const url = URL.createObjectURL(blob);
                    const a = document.createElement('a');
                    a.href = url;
                    a.download = `{{ config("app.company_name", config("app.name", "Company")) }}_AI_Chat_${new Date().toISOString().slice(0,10)}.txt`;
                    a.click();
                    URL.revokeObjectURL(url);
                },

                setPrompt(prompt) {
                    this.currentPrompt = prompt;
                    this.showQuickActions = false;
                    document.getElementById('prompt').focus();
                },

                clearPrompt() {
                    this.currentPrompt = '';
                },

                async testConnection() {
                    this.setAIStatus('testing', 'Testing connection...');
                    try {
                        const response = await fetch("{{ url('/ims/ai-test-connection') }}", {
                            method: 'GET',
                            headers: {
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            }
                        });
                        
                        const data = await response.json();
                        if (data.success) {
                            this.addMessage(`✅ AI Connection Test Successful!\n\nStatus: ${data.status}\nMessage: ${data.message}\n\nThe AI service is working properly.`, false);
                            this.setAIStatus('ready', 'Connected');
                        } else {
                            this.addMessage(`❌ AI Connection Test Failed!\n\nStatus: ${data.status}\nMessage: ${data.message}`, false);
                            this.setAIStatus('error', 'Connection Failed');
                        }
                    } catch (error) {
                        this.addMessage(`❌ Connection Test Error: ${error.message}`, false);
                        this.setAIStatus('error', 'Error');
                    }
                },

                setAIStatus(status, message) {
                    // Status updates for debugging - since we removed the status indicator element
                    console.log(`AI Status: ${status} - ${message}`);
                    
                    // You could update the header status badge here if needed
                    // For now, we'll just log it
                },

                clearChat() {
                    const chatContainer = document.getElementById('chat-container');
                    const messages = chatContainer.querySelectorAll('.chat-message');
                    messages.forEach(msg => msg.remove());
                },

                addMessage(content, isUser = false) {
                    const chatContainer = document.getElementById('chat-container');
                    const messageDiv = document.createElement('div');
                    messageDiv.className = 'flex items-start space-x-3 chat-message animate-fade-in';
                    
                    if (isUser) {
                        messageDiv.innerHTML = `
                            <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-user text-white text-sm"></i>
                            </div>
                            <div class="flex-1 bg-green-50 border border-green-200 rounded-lg p-4 shadow-sm">
                                <div class="text-gray-800 whitespace-pre-wrap">${content}</div>
                            </div>
                        `;
                    } else {
                        messageDiv.innerHTML = `
                            <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-robot text-white text-sm"></i>
                            </div>
                            <div class="flex-1 bg-white border border-gray-200 rounded-lg p-4 shadow-sm">
                                <div class="text-gray-800 whitespace-pre-wrap">${content}</div>
                                <div class="mt-3 flex space-x-2">
                                    <button onclick="copyToClipboard(this)" class="text-xs px-3 py-1 bg-blue-100 hover:bg-blue-200 text-blue-700 rounded-md transition-colors">
                                        <i class="fas fa-copy mr-1"></i> Copy
                                    </button>
                                    <button onclick="shareContent(this)" class="text-xs px-3 py-1 bg-green-100 hover:bg-green-200 text-green-700 rounded-md transition-colors">
                                        <i class="fas fa-share mr-1"></i> Share
                                    </button>
                                </div>
                            </div>
                        `;
                    }
                    
                    chatContainer.appendChild(messageDiv);
                    chatContainer.scrollTop = chatContainer.scrollHeight;
                },

                addLoadingMessage() {
                    const chatContainer = document.getElementById('chat-container');
                    const loadingDiv = document.createElement('div');
                    loadingDiv.className = 'flex items-start space-x-3 loading-message animate-fade-in';
                    loadingDiv.innerHTML = `
                        <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-robot text-white text-sm"></i>
                        </div>
                        <div class="flex-1 bg-blue-50 border border-blue-200 rounded-lg p-4 shadow-sm">
                            <div class="flex items-center text-blue-700">
                                <div class="flex space-x-1 mr-3">
                                    <div class="w-2 h-2 bg-blue-500 rounded-full animate-bounce"></div>
                                    <div class="w-2 h-2 bg-blue-500 rounded-full animate-bounce" style="animation-delay: 0.1s"></div>
                                    <div class="w-2 h-2 bg-blue-500 rounded-full animate-bounce" style="animation-delay: 0.2s"></div>
                                </div>
                                AI is thinking...
                            </div>
                        </div>
                    `;
                    chatContainer.appendChild(loadingDiv);
                    chatContainer.scrollTop = chatContainer.scrollHeight;
                },

                removeLoadingMessage() {
                    const loadingMessage = document.querySelector('.loading-message');
                    if (loadingMessage) {
                        loadingMessage.remove();
                    }
                },

                async sendMessage() {
                    if (!this.currentPrompt.trim() || this.isLoading) return;
                    
                    const prompt = this.currentPrompt.trim();
                    this.stats.requests++;
                    this.updateStats();
                    
                    this.addMessage(prompt, true);
                    this.currentPrompt = '';
                    this.isLoading = true;
                    this.addLoadingMessage();
                    this.setAIStatus('testing', 'Processing...');
                    
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
                        
                        // Update response time
                        let responseTimes = JSON.parse(localStorage.getItem('aiResponseTimes') || '[]');
                        responseTimes.push(responseTime);
                        responseTimes = responseTimes.slice(-10); // Keep last 10
                        localStorage.setItem('aiResponseTimes', JSON.stringify(responseTimes));
                        
                        const data = await response.json();
                        this.removeLoadingMessage();
                        
                        if (data.content) {
                            this.addMessage(data.content);
                            this.stats.successRate = Math.min(100, this.stats.successRate + 1);
                        } else if (data.error) {
                            this.addMessage(`❌ AI Error: ${data.error}`);
                        } else {
                            this.addMessage('❌ Sorry, I encountered an unexpected error while generating content.');
                        }
                        
                        this.setAIStatus('ready', 'Ready');
                        this.loadStats(); // Refresh stats display
                        
                    } catch (error) {
                        this.removeLoadingMessage();
                        this.addMessage(`❌ Network error: ${error.message}`);
                        this.setAIStatus('error', 'Connection Error');
                    } finally {
                        this.isLoading = false;
                    }
                }
            };
        }

        // Global functions
        function copyToClipboard(button) {
            const content = button.closest('.bg-white').querySelector('.text-gray-800').textContent;
            navigator.clipboard.writeText(content).then(() => {
                const originalText = button.innerHTML;
                button.innerHTML = '<i class="fas fa-check mr-1"></i> Copied!';
                button.classList.add('bg-green-200', 'text-green-800');
                button.classList.remove('bg-blue-100', 'text-blue-700');
                
                setTimeout(() => {
                    button.innerHTML = originalText;
                    button.classList.remove('bg-green-200', 'text-green-800');
                    button.classList.add('bg-blue-100', 'text-blue-700');
                }, 2000);
            });
        }

        function shareContent(button) {
            const content = button.closest('.bg-white').querySelector('.text-gray-800').textContent;
            if (navigator.share) {
                navigator.share({
                    title: 'AI Generated Content',
                    text: content
                });
            } else {
                copyToClipboard(button);
            }
        }
    </script>

    <style>
        @keyframes fade-in {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in {
            animation: fade-in 0.3s ease-out;
        }

        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
    </style>
</x-app-layout>
