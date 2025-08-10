<x-app-layout>
    <x-slot name="title">
        {{ __('AI Copilot') }} - {{ config('app.name', 'ATMS') }}
    </x-slot>

    <div
        class="py-4 mt-16 ml-4 sm:ml-64 min-h-screen bg-gradient-to-br from-slate-950 via-indigo-950 to-purple-950 relative overflow-hidden">
        <!-- Animated Background Elements -->
        <div class="absolute inset-0 opacity-20">
            <div
                class="absolute top-10 left-10 w-72 h-72 bg-blue-500 rounded-full mix-blend-multiply filter blur-xl animate-blob">
            </div>
            <div
                class="absolute top-0 right-4 w-72 h-72 bg-purple-500 rounded-full mix-blend-multiply filter blur-xl animate-blob animation-delay-2000">
            </div>
            <div
                class="absolute -bottom-8 left-20 w-72 h-72 bg-pink-500 rounded-full mix-blend-multiply filter blur-xl animate-blob animation-delay-4000">
            </div>
        </div>

        <div class="relative z-10 w-full mx-auto max-w-7xl sm:px-6 lg:px-8">
            <!-- Modern Header with Glass Morphism -->
            <div class="mb-6 text-center">
                <div class="relative inline-flex items-center justify-center w-24 h-24 mx-auto mb-6">
                    <div
                        class="absolute inset-0 bg-gradient-to-r from-cyan-400 via-blue-500 to-purple-600 rounded-3xl animate-pulse opacity-75">
                    </div>
                    <div
                        class="relative w-20 h-20 bg-white/10 backdrop-blur-xl rounded-2xl border border-white/20 flex items-center justify-center shadow-2xl">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                </div>
                <h1
                    class="text-5xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-cyan-400 via-blue-500 to-purple-600 mb-3 tracking-tight">
                    AI Copilot Studio
                </h1>
                <p class="text-slate-300 text-xl font-light max-w-2xl mx-auto">
                    Supercharged by Google Gemini AI • Your intelligent business companion for the digital age
                </p>
                <div
                    class="mt-4 inline-flex items-center px-4 py-2 bg-emerald-500/20 border border-emerald-400/30 rounded-full text-emerald-300 text-sm">
                    <div class="w-2 h-2 bg-emerald-400 rounded-full mr-2 animate-pulse"></div>
                    <span class="font-medium">AI Service Online</span>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
                <!-- Enhanced Left Sidebar - Quick Actions -->
                <div class="lg:col-span-1">
                    <div
                        class="bg-white/5 backdrop-blur-2xl border border-white/10 rounded-3xl p-6 shadow-2xl hover:shadow-3xl transition-all duration-300">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-xl font-bold text-white flex items-center">
                                <div
                                    class="w-8 h-8 bg-gradient-to-r from-cyan-500 to-blue-600 rounded-lg flex items-center justify-center mr-3 shadow-lg">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                    </svg>
                                </div>
                                Quick Actions
                            </h3>
                            <div class="w-3 h-3 bg-cyan-400 rounded-full animate-pulse"></div>
                        </div>

                        <!-- Enhanced Quick Actions with Categories -->
                        <div class="space-y-4 max-h-96 overflow-y-auto custom-scrollbar pr-2">
                            @php
                                $actionCategories = [
                                    'Customer Relations' => [
                                        [
                                            'Welcome Email',
                                            'Generate customer welcome',
                                            'Write a professional welcome email for a new customer',
                                            'emerald',
                                            '👋',
                                        ],
                                        [
                                            'Payment Follow-up',
                                            'Invoice reminders',
                                            'Write a follow-up email for pending invoice payment',
                                            'amber',
                                            '💰',
                                        ],
                                        [
                                            'Feedback Request',
                                            'Get client feedback',
                                            'Create a feedback request email for clients after service delivery',
                                            'pink',
                                            '📝',
                                        ],
                                    ],
                                    'Business Documents' => [
                                        [
                                            'Quotation T&C',
                                            'Business terms',
                                            'Create professional quotation terms and conditions for business services',
                                            'blue',
                                            '📋',
                                        ],
                                        [
                                            'Send Invoice',
                                            'Invoice with payment instructions',
                                            'Draft an invoice for a customer including payment details and terms',
                                            'purple',
                                            '🧾',
                                        ],
                                        [
                                            'Send Quotation',
                                            'Quotation with price and terms',
                                            'Send a quotation for business services including pricing and validity period',
                                            'indigo',
                                            '💼',
                                        ],
                                        [
                                            'Business Proposal',
                                            'Professional templates',
                                            'Generate a business proposal template',
                                            'cyan',
                                            '📊',
                                        ],
                                    ],
                                    'Marketing & Communication' => [
                                        [
                                            'Product Description',
                                            'Marketing content',
                                            'Create a professional product description for marketing purposes',
                                            'rose',
                                            '🎯',
                                        ],
                                        [
                                            'Company Announcement',
                                            'Product launch/update',
                                            'Draft a company announcement about a new product launch or important update',
                                            'orange',
                                            '📢',
                                        ],
                                        [
                                            'Greeting Message',
                                            'Event/Partnership greetings',
                                            'Write an official greeting message for a company event or new partnership',
                                            'teal',
                                            '🎉',
                                        ],
                                    ],
                                ];
                            @endphp

                            @foreach ($actionCategories as $category => $actions)
                                <div class="mb-4">
                                    <h4 class="text-sm font-semibold text-slate-300 mb-2 px-2">{{ $category }}</h4>
                                    <div class="space-y-2">
                                        @foreach ($actions as [$title, $desc, $prompt, $color, $emoji])
                                            <button onclick="setPrompt('{{ $prompt }}')"
                                                class="group w-full text-left p-3 bg-gradient-to-r from-{{ $color }}-500/10 to-{{ $color }}-600/10 hover:from-{{ $color }}-500/20 hover:to-{{ $color }}-600/20 border border-{{ $color }}-500/20 hover:border-{{ $color }}-400/40 rounded-xl transition-all duration-300 hover:scale-[1.02] hover:shadow-lg">
                                                <div class="flex items-start space-x-3">
                                                    <span
                                                        class="text-lg group-hover:scale-110 transition-transform duration-200">{{ $emoji }}</span>
                                                    <div class="flex-1 min-w-0">
                                                        <div
                                                            class="font-medium text-{{ $color }}-200 group-hover:text-{{ $color }}-100">
                                                            {{ $title }}</div>
                                                        <div
                                                            class="text-xs text-{{ $color }}-300/70 group-hover:text-{{ $color }}-200/80 mt-1">
                                                            {{ $desc }}</div>
                                                    </div>
                                                </div>
                                            </button>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Enhanced AI Stats Dashboard -->
                        <div class="mt-6 space-y-4">
                            <div
                                class="p-4 bg-gradient-to-r from-slate-800/50 to-slate-700/50 rounded-2xl border border-white/10 backdrop-blur-sm">
                                <h4 class="text-sm font-semibold text-slate-200 mb-3 flex items-center">
                                    <svg class="w-4 h-4 mr-2 text-cyan-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                                        </path>
                                    </svg>
                                    Today's Analytics
                                </h4>
                                <div class="grid grid-cols-2 gap-3">
                                    <div class="text-center p-2 bg-blue-500/10 rounded-lg border border-blue-500/20">
                                        <div class="text-lg font-bold text-blue-400" id="requestCount">0</div>
                                        <div class="text-xs text-blue-300">Requests</div>
                                    </div>
                                    <div
                                        class="text-center p-2 bg-emerald-500/10 rounded-lg border border-emerald-500/20">
                                        <div class="text-lg font-bold text-emerald-400" id="successCount">0</div>
                                        <div class="text-xs text-emerald-300">Success</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Enhanced Status Indicator -->
                            <div class="flex flex-col space-y-3">
                                <button type="button" onclick="testConnection()"
                                    class="w-full px-4 py-3 bg-gradient-to-r from-orange-500 to-red-500 hover:from-orange-600 hover:to-red-600 text-white rounded-xl transition-all duration-300 font-medium shadow-lg hover:shadow-xl transform hover:scale-105 flex items-center justify-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Test AI Connection
                                </button>

                                <div id="aiStatus"
                                    class="flex items-center justify-center px-4 py-3 rounded-xl text-sm bg-emerald-500/20 border border-emerald-500/30 backdrop-blur-sm">
                                    <div class="w-2 h-2 bg-emerald-400 rounded-full mr-3 animate-pulse"></div>
                                    <span class="text-emerald-300 font-medium">AI Service Ready</span>
                                </div>

                                <div id="responseTimeIndicator" class="text-center text-xs text-slate-400 hidden">
                                    <div class="flex items-center justify-center">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        Avg Response: <span id="avgResponseTime"
                                            class="font-medium text-cyan-400">-</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Enhanced Main Chat Area -->
                <div class="lg:col-span-3">
                    <div
                        class="bg-white/5 backdrop-blur-2xl border border-white/10 rounded-3xl shadow-2xl overflow-hidden hover:shadow-3xl transition-all duration-300">
                        <!-- Modern Chat Header -->
                        <div
                            class="bg-gradient-to-r from-slate-800/80 via-slate-700/80 to-slate-800/80 backdrop-blur-xl p-6 border-b border-white/10">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-4">
                                    <div class="relative">
                                        <div
                                            class="w-12 h-12 bg-gradient-to-r from-cyan-500 via-blue-500 to-purple-500 rounded-2xl flex items-center justify-center shadow-lg">
                                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                            </svg>
                                        </div>
                                        <div
                                            class="absolute -bottom-1 -right-1 w-4 h-4 bg-emerald-500 rounded-full border-2 border-slate-800 animate-pulse">
                                        </div>
                                    </div>
                                    <div>
                                        <h3 class="text-xl font-bold text-white">AI Assistant Studio</h3>
                                        <p class="text-slate-300 text-sm">Powered by Google Gemini AI • Always
                                            learning, always helping</p>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-3">
                                    <div
                                        class="px-3 py-1 bg-emerald-500/20 border border-emerald-400/30 rounded-full text-emerald-300 text-xs font-medium">
                                        Online
                                    </div>
                                    <button onclick="clearChat()"
                                        class="p-2 text-slate-400 hover:text-white hover:bg-white/10 rounded-xl transition-all duration-200"
                                        title="Clear conversation">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                            </path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Enhanced Chat Messages Container -->
                        <div id="chat-container"
                            class="h-[36rem] overflow-y-auto p-6 space-y-6 bg-gradient-to-b from-slate-950/50 to-slate-900/50 custom-scrollbar">
                            <!-- Enhanced Welcome Message -->
                            <div class="flex items-start space-x-4 animate-fade-in">
                                <div class="relative flex-shrink-0">
                                    <div
                                        class="w-10 h-10 bg-gradient-to-r from-cyan-500 via-blue-500 to-purple-500 rounded-2xl flex items-center justify-center shadow-lg">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                        </svg>
                                    </div>
                                    <div
                                        class="absolute -bottom-1 -right-1 w-3 h-3 bg-emerald-500 rounded-full border border-slate-900">
                                    </div>
                                </div>
                                <div
                                    class="flex-1 bg-gradient-to-r from-blue-500/10 via-purple-500/10 to-pink-500/10 border border-blue-400/20 rounded-2xl p-6 max-w-4xl backdrop-blur-sm">
                                    <div class="flex items-center mb-3">
                                        <span class="text-2xl mr-2">👋</span>
                                        <h4 class="text-lg font-semibold text-white">Welcome to AI Copilot Studio!</h4>
                                    </div>
                                    <p class="text-slate-200 mb-4">I'm your AI assistant powered by Google Gemini AI.
                                        I'm here to help you create professional business content with lightning-fast
                                        responses.</p>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mb-4">
                                        <div class="flex items-center text-sm text-slate-300">
                                            <svg class="w-4 h-4 mr-2 text-emerald-400" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M5 13l4 4L19 7"></path>
                                            </svg>
                                            Professional emails & content
                                        </div>
                                        <div class="flex items-center text-sm text-slate-300">
                                            <svg class="w-4 h-4 mr-2 text-emerald-400" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M5 13l4 4L19 7"></path>
                                            </svg>
                                            Business proposals & quotations
                                        </div>
                                        <div class="flex items-center text-sm text-slate-300">
                                            <svg class="w-4 h-4 mr-2 text-emerald-400" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M5 13l4 4L19 7"></path>
                                            </svg>
                                            Invoices & payment requests
                                        </div>
                                        <div class="flex items-center text-sm text-slate-300">
                                            <svg class="w-4 h-4 mr-2 text-emerald-400" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M5 13l4 4L19 7"></path>
                                            </svg>
                                            Marketing & announcements
                                        </div>
                                    </div>

                                    <div
                                        class="flex items-center justify-between p-3 bg-gradient-to-r from-cyan-500/10 to-blue-500/10 rounded-xl border border-cyan-400/20">
                                        <span class="text-cyan-200 text-sm font-medium">💡 Pro Tip:</span>
                                        <span class="text-slate-300 text-sm">Use the quick actions on the left or type
                                            your custom prompt below!</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Enhanced Input Area -->
                        <div
                            class="border-t border-white/10 p-6 bg-gradient-to-r from-slate-800/50 via-slate-700/50 to-slate-800/50 backdrop-blur-xl">
                            <form id="ai-form" class="space-y-4">
                                @csrf
                                <div class="flex space-x-4">
                                    <div class="flex-1 relative">
                                        <textarea id="prompt" name="prompt" rows="3"
                                            class="w-full px-6 py-4 bg-white/5 backdrop-blur-sm text-white border border-white/20 rounded-2xl focus:ring-2 focus:ring-cyan-500 focus:border-transparent resize-none transition-all duration-300 placeholder-slate-400 text-lg"
                                            placeholder="Type your message or ask me anything..."></textarea>
                                        <div class="absolute bottom-3 right-3 text-xs text-slate-400">
                                            <kbd class="px-2 py-1 bg-slate-700 rounded text-xs">Enter</kbd> to send •
                                            <kbd class="px-2 py-1 bg-slate-700 rounded text-xs">Shift+Enter</kbd> for
                                            new line
                                        </div>
                                    </div>
                                    <div class="flex flex-col space-y-3">
                                        <button type="submit"
                                            class="px-8 py-4 bg-gradient-to-r from-cyan-500 via-blue-500 to-purple-600 text-white rounded-2xl hover:from-cyan-600 hover:via-blue-600 hover:to-purple-700 transition-all duration-300 shadow-lg hover:shadow-2xl transform hover:scale-105 flex items-center font-medium text-lg group">
                                            <svg class="w-6 h-6 mr-3 group-hover:translate-x-1 transition-transform duration-200"
                                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                                            </svg>
                                            Send
                                        </button>
                                        <button type="button" onclick="clearPrompt()"
                                            class="px-4 py-2 bg-slate-600/50 hover:bg-slate-600/70 text-slate-300 hover:text-white rounded-xl transition-all duration-200 text-sm font-medium">
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
        </form>
    </div>
    </div>
    </div>
    </div>
    </div>
    </div>

    <style>
        /* Enhanced Animations & Styling */
        @keyframes blob {
            0% {
                transform: translate(0px, 0px) scale(1);
            }

            33% {
                transform: translate(30px, -50px) scale(1.1);
            }

            66% {
                transform: translate(-20px, 20px) scale(0.9);
            }

            100% {
                transform: translate(0px, 0px) scale(1);
            }
        }

        @keyframes fade-in {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-blob {
            animation: blob 7s infinite;
        }

        .animation-delay-2000 {
            animation-delay: 2s;
        }

        .animation-delay-4000 {
            animation-delay: 4s;
        }

        .animate-fade-in {
            animation: fade-in 0.6s ease-out;
        }

        /* Enhanced Custom Scrollbars */
        .custom-scrollbar::-webkit-scrollbar,
        #chat-container::-webkit-scrollbar {
            width: 12px;
            background: transparent;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb,
        #chat-container::-webkit-scrollbar-thumb {
            background: linear-gradient(135deg, #06b6d4 0%, #3b82f6 50%, #8b5cf6 100%);
            border-radius: 12px;
            border: 2px solid transparent;
            background-clip: content-box;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover,
        #chat-container::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(135deg, #0891b2 0%, #2563eb 50%, #7c3aed 100%);
            background-clip: content-box;
        }

        .custom-scrollbar::-webkit-scrollbar-track,
        #chat-container::-webkit-scrollbar-track {
            background: rgba(15, 23, 42, 0.4);
            border-radius: 12px;
            margin: 4px;
        }

        .custom-scrollbar,
        #chat-container {
            scrollbar-width: thin;
            scrollbar-color: #8b5cf6 rgba(15, 23, 42, 0.4);
        }

        /* Glass Morphism Effects */
        .backdrop-blur-2xl {
            backdrop-filter: blur(40px);
        }

        .backdrop-blur-3xl {
            backdrop-filter: blur(64px);
        }

        /* Responsive Design Enhancements */
        @media (max-width: 1024px) {
            #chat-container {
                height: 28rem;
            }

            .text-5xl {
                font-size: 2.5rem;
            }
        }

        @media (max-width: 768px) {
            #chat-container {
                height: 20rem;
            }

            .text-5xl {
                font-size: 2rem;
            }

            .px-8 {
                padding-left: 1rem;
                padding-right: 1rem;
            }

            .py-4 {
                padding-top: 0.75rem;
                padding-bottom: 0.75rem;
            }
        }

        @media (max-width: 640px) {
            #chat-container {
                height: 16rem;
            }

            .text-5xl {
                font-size: 1.75rem;
            }

            .rounded-3xl {
                border-radius: 1.5rem;
            }

            .p-6 {
                padding: 1rem;
            }
        }

        /* Enhanced Button Hover Effects */
        .group:hover .group-hover\:translate-x-1 {
            transform: translateX(0.25rem);
        }

        .group:hover .group-hover\:scale-110 {
            transform: scale(1.1);
        }

        /* Loading Animation Enhancement */
        @keyframes pulse-glow {

            0%,
            100% {
                box-shadow: 0 0 5px rgba(56, 189, 248, 0.5);
            }

            50% {
                box-shadow: 0 0 20px rgba(56, 189, 248, 0.8), 0 0 30px rgba(56, 189, 248, 0.4);
            }
        }

        .animate-pulse-glow {
            animation: pulse-glow 2s infinite;
        }

        /* Text Gradient Animation */
        @keyframes gradient-shift {

            0%,
            100% {
                background-position: 0% 50%;
            }

            50% {
                background-position: 100% 50%;
            }
        }

        .animate-gradient {
            background-size: 200% 200%;
            animation: gradient-shift 3s ease infinite;
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
                        <div class="w-2 h-2 bg-amber-400 rounded-full mr-3 animate-pulse"></div>
                        <span class="text-amber-300 font-medium">AI Service Slow</span>
                    `;
                    statusEl.className =
                        'flex items-center justify-center px-4 py-3 rounded-xl text-sm bg-amber-500/20 border border-amber-500/30 backdrop-blur-sm';
                } else {
                    statusEl.innerHTML = `
                        <div class="w-2 h-2 bg-emerald-400 rounded-full mr-3 animate-pulse"></div>
                        <span class="text-emerald-300 font-medium">AI Service Ready</span>
                    `;
                    statusEl.className =
                        'flex items-center justify-center px-4 py-3 rounded-xl text-sm bg-emerald-500/20 border border-emerald-500/30 backdrop-blur-sm';
                }
            }
        }

        function setAIStatus(status, message) {
            const statusEl = document.getElementById('aiStatus');
            switch (status) {
                case 'working':
                    statusEl.innerHTML = `
                        <div class="w-3 h-3 border-2 border-cyan-400 border-t-transparent rounded-full mr-3 animate-spin"></div>
                        <span class="text-cyan-300 font-medium">${message || 'Processing...'}</span>
                    `;
                    statusEl.className =
                        'flex items-center justify-center px-4 py-3 rounded-xl text-sm bg-cyan-500/20 border border-cyan-500/30 backdrop-blur-sm';
                    break;
                case 'error':
                    statusEl.innerHTML = `
                        <div class="w-2 h-2 bg-red-400 rounded-full mr-3"></div>
                        <span class="text-red-300 font-medium">${message || 'Service Error'}</span>
                    `;
                    statusEl.className =
                        'flex items-center justify-center px-4 py-3 rounded-xl text-sm bg-red-500/20 border border-red-500/30 backdrop-blur-sm';
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
            messageDiv.className = 'flex items-start space-x-4 chat-message animate-fade-in';
            if (isUser) {
                messageDiv.innerHTML = `
                    <div class="relative flex-shrink-0">
                        <div class="w-10 h-10 bg-gradient-to-r from-emerald-500 via-teal-500 to-cyan-500 rounded-2xl flex items-center justify-center shadow-lg">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <div class="absolute -bottom-1 -right-1 w-3 h-3 bg-green-500 rounded-full border border-slate-900"></div>
                    </div>
                    <div class="flex-1 bg-gradient-to-r from-emerald-500/10 via-teal-500/10 to-cyan-500/10 border border-emerald-400/20 rounded-2xl p-5 max-w-4xl backdrop-blur-sm">
                        <div class="text-slate-100 whitespace-pre-wrap font-medium">${content}</div>
                    </div>
                `;
            } else {
                messageDiv.innerHTML = `
                    <div class="relative flex-shrink-0">
                        <div class="w-10 h-10 bg-gradient-to-r from-cyan-500 via-blue-500 to-purple-500 rounded-2xl flex items-center justify-center shadow-lg">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                        </div>
                        <div class="absolute -bottom-1 -right-1 w-3 h-3 bg-emerald-500 rounded-full border border-slate-900"></div>
                    </div>
                    <div class="flex-1 bg-gradient-to-r from-blue-500/10 via-purple-500/10 to-pink-500/10 border border-blue-400/20 rounded-2xl p-5 max-w-4xl backdrop-blur-sm">
                        <div class="text-slate-100 whitespace-pre-wrap leading-relaxed">${content}</div>
                        <div class="mt-4 flex flex-wrap gap-2">
                            <button onclick="copyToClipboard(this)" class="inline-flex items-center px-3 py-2 bg-blue-600/30 hover:bg-blue-600/50 border border-blue-500/30 rounded-lg transition-all duration-200 text-blue-200 hover:text-blue-100 text-sm font-medium">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                </svg>
                                Copy
                            </button>
                            <button onclick="regenerateResponse()" class="inline-flex items-center px-3 py-2 bg-purple-600/30 hover:bg-purple-600/50 border border-purple-500/30 rounded-lg transition-all duration-200 text-purple-200 hover:text-purple-100 text-sm font-medium">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                </svg>
                                Regenerate
                            </button>
                            <button onclick="shareMessage(this)" class="inline-flex items-center px-3 py-2 bg-emerald-600/30 hover:bg-emerald-600/50 border border-emerald-500/30 rounded-lg transition-all duration-200 text-emerald-200 hover:text-emerald-100 text-sm font-medium">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z"></path>
                                </svg>
                                Share
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
            loadingDiv.className = 'flex items-start space-x-4 chat-message loading-message animate-fade-in';
            loadingDiv.innerHTML = `
                <div class="relative flex-shrink-0">
                    <div class="w-10 h-10 bg-gradient-to-r from-cyan-500 via-blue-500 to-purple-500 rounded-2xl flex items-center justify-center shadow-lg animate-pulse-glow">
                        <div class="w-5 h-5 border-2 border-white border-t-transparent rounded-full animate-spin"></div>
                    </div>
                    <div class="absolute -bottom-1 -right-1 w-3 h-3 bg-cyan-400 rounded-full border border-slate-900 animate-pulse"></div>
                </div>
                <div class="flex-1 bg-gradient-to-r from-slate-700/30 via-slate-600/30 to-slate-700/30 border border-slate-500/30 rounded-2xl p-5 max-w-4xl backdrop-blur-sm">
                    <div class="flex items-center space-x-3 text-slate-300">
                        <div class="flex space-x-1">
                            <div class="w-2 h-2 bg-cyan-400 rounded-full animate-bounce"></div>
                            <div class="w-2 h-2 bg-blue-400 rounded-full animate-bounce" style="animation-delay: 0.1s"></div>
                            <div class="w-2 h-2 bg-purple-400 rounded-full animate-bounce" style="animation-delay: 0.2s"></div>
                        </div>
                        <span class="font-medium">AI is thinking...</span>
                        <div class="text-xs text-slate-400">Powered by Google Gemini</div>
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
            const content = button.closest('.bg-gradient-to-r').querySelector('.text-slate-100').textContent;
            navigator.clipboard.writeText(content).then(() => {
                const originalIcon = button.querySelector('svg');
                const originalText = button.querySelector('svg + *');
                originalText.textContent = 'Copied!';
                originalIcon.innerHTML =
                    `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>`;
                button.classList.add('bg-emerald-600/50', 'border-emerald-500/30', 'text-emerald-100');
                button.classList.remove('bg-blue-600/30', 'border-blue-500/30', 'text-blue-200',
                    'hover:bg-blue-600/50', 'hover:text-blue-100');
                setTimeout(() => {
                    originalText.textContent = 'Copy';
                    originalIcon.innerHTML =
                        `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>`;
                    button.classList.remove('bg-emerald-600/50', 'border-emerald-500/30',
                        'text-emerald-100');
                    button.classList.add('bg-blue-600/30', 'border-blue-500/30', 'text-blue-200',
                        'hover:bg-blue-600/50', 'hover:text-blue-100');
                }, 2000);
            });
        }

        function shareMessage(button) {
            const content = button.closest('.bg-gradient-to-r').querySelector('.text-slate-100').textContent;
            if (navigator.share) {
                navigator.share({
                    title: 'AI Generated Content',
                    text: content
                });
            } else {
                // Fallback: copy to clipboard
                navigator.clipboard.writeText(`AI Generated Content:\n\n${content}`).then(() => {
                    const originalText = button.querySelector('svg + *');
                    originalText.textContent = 'Copied!';
                    setTimeout(() => {
                        originalText.textContent = 'Share';
                    }, 2000);
                });
            }
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
                    body: JSON.stringify({
                        prompt
                    })
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
                } else if (error.message.includes('Failed to fetch') || error.message.includes(
                    'NetworkError')) {
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
