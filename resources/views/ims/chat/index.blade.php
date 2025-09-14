<x-app-layout>
    <x-slot name="title">
        {{ __('Chat') }} - {{ config('app.name', 'IMS') }}
    </x-slot>

    <div class="flex h-[90vh] bg-gray-50 overflow-hidden relative">
        <!-- Mobile Menu Overlay -->
        <div id="mobileOverlay" class="fixed inset-0 bg-black bg-opacity-50 z-30 lg:hidden hidden"></div>

        <!-- User List Sidebar -->
        <div id="userSidebar" class="fixed lg:relative w-80 lg:w-80 h-full bg-white border-r border-gray-200 flex flex-col transform -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out z-40 lg:z-auto">
            <!-- Chat Header -->
            <div class="p-4 bg-gradient-to-r from-blue-600 to-indigo-600 text-white">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center mr-3">
                            <i class="fas fa-comments text-lg"></i>
                        </div>
                        <div>
                            <h2 class="text-xl font-semibold">Messages</h2>
                            <p class="text-blue-100 text-sm">{{ Auth::user()->name }}</p>
                        </div>
                    </div>
                    <!-- Close button for mobile -->
                    <button id="closeSidebar" class="lg:hidden p-2 text-white hover:bg-white/20 rounded-lg">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>

            <!-- Search Bar -->
            <div class="p-4 border-b border-gray-200">
                <div class="relative">
                    <input type="text" id="userSearch" placeholder="Search conversations..."
                        class="w-full pl-10 pr-4 py-2 bg-gray-50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm">
                    <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                </div>
            </div>

            <!-- Users List -->
            <div class="flex-1 overflow-y-auto scrollbar-thin scrollbar-thumb-slate-300" id="usersList">
                <div id="usersContainer" class="divide-y divide-gray-100">
                    <!-- Users will be loaded here -->
                </div>
            </div>
        </div>

        <!-- Chat Window -->
        <div class="flex-1 flex flex-col min-w-0" id="chatWindow">
            <!-- Mobile Header -->
            <div class="lg:hidden flex items-center justify-between p-4 bg-white border-b border-gray-200">
                <button id="openSidebar" class="p-2 text-gray-600 hover:bg-gray-100 rounded-lg">
                    <i class="fas fa-bars"></i>
                </button>
                <h1 class="text-lg font-semibold text-gray-800">Chat</h1>
                <div class="w-10"></div> <!-- Spacer -->
            </div>

            <!-- Welcome Screen -->
            <div class="flex-1 flex items-center justify-center bg-white" id="welcomeScreen">
                <div class="text-center max-w-md px-4">
                    <div class="w-16 h-16 sm:w-20 sm:h-20 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-comments text-xl sm:text-2xl text-white"></i>
                    </div>
                    <h3 class="text-xl sm:text-2xl font-semibold text-gray-700 mb-3">Select a conversation</h3>
                    <p class="text-gray-500 text-sm sm:text-base">Choose a contact from the sidebar to start messaging.</p>
                    <button id="mobileOpenSidebar" class="lg:hidden mt-4 px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        <i class="fas fa-users mr-2"></i>View Contacts
                    </button>
                </div>
            </div>

            <!-- Chat Interface (Hidden by default) -->
            <div class="flex-1 flex-col bg-white" id="chatInterface" style="display: none;">
                <!-- Chat Header -->
                <div class="px-4 lg:px-6 py-4 border-b border-gray-200 bg-white">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center min-w-0">
                            <!-- Back button for mobile -->
                            <button id="backToSidebar" class="lg:hidden mr-3 p-2 text-gray-600 hover:bg-gray-100 rounded-lg">
                                <i class="fas fa-arrow-left"></i>
                            </button>
                            <div class="relative flex-shrink-0">
                                <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center mr-3 sm:mr-4" id="chatUserAvatar">
                                    <i class="fas fa-user text-white text-sm sm:text-base"></i>
                                </div>
                                <div class="absolute -bottom-1 -right-1 w-3 h-3 sm:w-4 sm:h-4 bg-green-400 border-2 border-white rounded-full" id="onlineStatus"></div>
                            </div>
                            <div class="min-w-0 flex-1">
                                <h3 class="font-semibold text-gray-800 text-sm sm:text-base truncate" id="chatUserName">User Name</h3>
                                <p class="text-xs sm:text-sm text-gray-500" id="chatUserStatus">Online</p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-2">
                            <button id="viewProfileBtn" class="hidden sm:flex px-4 py-2 text-sm bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                <i class="fas fa-user mr-2"></i>View Profile
                            </button>
                            <button id="mobileProfileBtn" class="sm:hidden p-2 text-gray-500 hover:text-gray-700 rounded-lg hover:bg-gray-100">
                                <i class="fas fa-user"></i>
                            </button>
                            <button class="p-2 text-gray-500 hover:text-gray-700 rounded-lg hover:bg-gray-100">
                                <i class="fas fa-ellipsis-v"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Messages Area -->
                <div class="flex-1 overflow-y-auto p-3 sm:p-4 space-y-3 sm:space-y-4 bg-gray-50 scrollbar-thin scrollbar-thumb-slate-300" id="chatMessages">
                    <!-- Messages will be loaded here -->
                </div>

                <!-- Typing Indicator -->
                <div id="typingIndicator" class="px-4 lg:px-6 py-2 text-sm text-gray-500 bg-gray-50" style="display: none;">
                    <div class="flex items-center">
                        <div class="typing-dots">
                            <span></span>
                            <span></span>
                            <span></span>
                        </div>
                        <span class="ml-2" id="typingUser">Someone is typing...</span>
                    </div>
                </div>

                <!-- Message Input -->
                <div class="px-3 sm:px-4 lg:px-6 py-3 sm:py-4 bg-white border-t border-gray-200">
                    <form id="messageForm" class="flex items-end space-x-2 sm:space-x-3">
                        <div class="flex-1 relative">
                            <div class="flex items-center space-x-2 mb-2" id="filePreview" style="display: none;">
                                <div class="flex items-center bg-blue-50 px-3 py-2 rounded-lg">
                                    <i class="fas fa-file mr-2 text-blue-600"></i>
                                    <span class="text-sm text-blue-600 truncate max-w-32 sm:max-w-none" id="fileName"></span>
                                    <button type="button" id="removeFile" class="ml-2 text-red-500 hover:text-red-700">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="flex items-end border border-gray-200 rounded-lg bg-gray-50 focus-within:bg-white focus-within:border-blue-500">
                                <button type="button" id="emojiBtn" class="p-2 sm:p-3 text-gray-400 hover:text-yellow-500 transition-colors">
                                    <i class="fas fa-smile text-sm sm:text-base"></i>
                                </button>
                                <textarea id="messageText" placeholder="Type a message..." rows="1"
                                    class="flex-1 px-2 sm:px-3 py-2 sm:py-3 bg-transparent border-0 resize-none focus:ring-0 focus:outline-none max-h-32 text-sm sm:text-base"
                                    style="min-height: 38px;"></textarea>
                                <label for="attachmentInput" class="p-2 sm:p-3 text-gray-400 hover:text-blue-600 cursor-pointer transition-colors">
                                    <i class="fas fa-paperclip text-sm sm:text-base"></i>
                                </label>
                                <input type="file" id="attachmentInput" class="hidden" accept="image/*,.pdf,.doc,.docx,.txt,.xlsx,.xls">
                            </div>
                        </div>
                        <button type="submit" class="px-4 sm:px-6 py-2 sm:py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 transition-all shadow-lg">
                            <i class="fas fa-paper-plane text-sm sm:text-base"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Profile Panel (Hidden by default) -->
        <div class="fixed lg:relative w-full lg:w-80 h-full bg-white border-l border-gray-200 transform translate-x-full transition-transform duration-300 ease-in-out z-50" id="profilePanel">
            <div class="flex flex-col h-full">
                <!-- Profile Header -->
                <div class="px-4 lg:px-6 py-4 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-800">Profile</h3>
                        <button id="closeProfileBtn" class="p-2 text-gray-500 hover:text-gray-700 rounded-lg hover:bg-gray-100">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>

                <!-- Profile Content -->
                <div class="flex-1 overflow-y-auto p-4 lg:p-6 scrollbar-thin scrollbar-thumb-slate-300" id="profileContent">
                    <!-- Profile details will be loaded here -->
                </div>
            </div>
        </div>
    </div>

    <!-- Emoji Picker Modal -->
    <div id="emojiPicker" class="fixed inset-0 bg-black bg-opacity-50 items-center justify-center z-50 p-4" style="display: none;">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full max-h-96">
            <div class="p-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold">Choose an emoji</h3>
                    <button id="closeEmojiPicker" class="text-gray-500 hover:text-gray-700">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
            <div class="p-4 max-h-80 overflow-y-auto scrollbar-thin scrollbar-thumb-slate-300">
                <div class="grid grid-cols-6 sm:grid-cols-8 gap-2" id="emojiGrid">
                    <!-- Emojis will be loaded here -->
                </div>
            </div>
        </div>
    </div>

    <!-- Templates -->
    <template id="userItemTemplate">
        <div class="user-item flex items-center p-3 rounded-xl cursor-pointer hover:bg-blue-50 transition-all duration-200 border border-transparent hover:border-blue-200 hover:shadow-sm"
            data-user-id="">
            <div class="relative flex-shrink-0">
                <div
                    class="w-12 h-12 sm:w-14 sm:h-14 rounded-full bg-gradient-to-br from-blue-400 to-purple-500 flex items-center justify-center mr-3 sm:mr-4 user-avatar">
                    <i class="fas fa-user text-white text-sm sm:text-base"></i>
                </div>
                <div class="absolute -top-1 -right-1 w-5 h-5 bg-red-500 text-white text-xs rounded-full flex items-center justify-center unread-badge shadow-lg"
                    style="display: none;">
                    <span class="unread-count font-semibold">0</span>
                </div>
                <div
                    class="absolute -bottom-1 -right-1 w-3 h-3 sm:w-4 sm:h-4 bg-green-400 border-2 border-white rounded-full online-status">
                </div>
            </div>
            <div class="flex-1 min-w-0">
                <div class="flex items-center justify-between mb-1">
                    <h4 class="font-semibold text-slate-800 truncate user-name text-sm sm:text-base"></h4>
                    <span class="text-xs text-slate-500 last-message-time flex-shrink-0"></span>
                </div>
                <p class="text-sm text-slate-600 truncate last-message-text">No messages yet</p>
                <div class="flex items-center mt-1">
                    <span class="text-xs text-green-600 message-status">
                        <i class="fas fa-check-double"></i>
                    </span>
                </div>
            </div>
        </div>
    </template>

    <template id="sentMessageTemplate">
        <div class="flex justify-end message-item animate-fadeIn" data-message-id="">
            <div class="max-w-xs sm:max-w-sm lg:max-w-md">
                <div
                    class="bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-2xl rounded-br-md px-4 sm:px-5 py-3 shadow-lg">
                    <p class="message-text leading-relaxed text-sm sm:text-base break-words"></p>
                    <div class="message-attachment mt-2" style="display: none;">
                        <div class="bg-white/20 rounded-lg p-2">
                            <a href="#"
                                class="text-blue-100 hover:text-white flex items-center attachment-link">
                                <i class="fas fa-paperclip mr-2"></i>
                                <span class="attachment-name text-sm truncate"></span>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="text-xs text-slate-500 mt-1 text-right flex items-center justify-end message-time">
                    <span class="mr-2"></span>
                    <i class="fas fa-check-double text-green-500"></i>
                </div>
            </div>
        </div>
    </template>

    <template id="receivedMessageTemplate">
        <div class="flex justify-start message-item animate-fadeIn" data-message-id="">
            <div class="max-w-xs sm:max-w-sm lg:max-w-md">
                <div
                    class="bg-white border border-slate-200 text-slate-800 rounded-2xl rounded-bl-md px-4 sm:px-5 py-3 shadow-sm">
                    <p class="message-text leading-relaxed text-sm sm:text-base break-words"></p>
                    <div class="message-attachment mt-2" style="display: none;">
                        <div class="bg-slate-50 rounded-lg p-2">
                            <a href="#"
                                class="text-blue-600 hover:text-blue-800 flex items-center attachment-link">
                                <i class="fas fa-paperclip mr-2"></i>
                                <span class="attachment-name text-sm truncate"></span>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="text-xs text-slate-500 mt-1 message-time">
                    <span></span>
                </div>
            </div>
        </div>
    </template>

    <template id="dateSeparatorTemplate">
        <div class="flex items-center justify-center my-4 sm:my-6">
            <div class="bg-slate-100 text-slate-600 px-3 sm:px-4 py-2 rounded-full text-xs sm:text-sm font-medium date-text"></div>
        </div>
    </template>

    <template id="profileTemplate">
        <div class="text-center mb-6">
            <div class="w-20 h-20 sm:w-24 sm:h-24 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center mx-auto mb-4 profile-avatar">
                <i class="fas fa-user text-2xl sm:text-3xl text-white"></i>
            </div>
            <h3 class="text-xl font-semibold text-gray-800 profile-name"></h3>
            <p class="text-gray-500 profile-role"></p>
        </div>
        <div class="space-y-4">
            <div class="profile-field">
                <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <p class="text-gray-900 profile-email break-words"></p>
            </div>
            <div class="profile-field">
                <label class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                <p class="text-gray-900 profile-phone"></p>
            </div>
            <div class="profile-field">
                <label class="block text-sm font-medium text-gray-700 mb-1">Employee ID</label>
                <p class="text-gray-900 profile-employee-id"></p>
            </div>
            <div class="profile-field">
                <label class="block text-sm font-medium text-gray-700 mb-1">Joined Date</label>
                <p class="text-gray-900 profile-joined"></p>
            </div>
        </div>
    </template>

    @push('styles')
        <style>
            /* Custom Scrollbar */
            .scrollbar-thin {
                scrollbar-width: thin;
            }

            .scrollbar-thin::-webkit-scrollbar {
                width: 6px;
                height: 6px;
            }

            .scrollbar-thumb-slate-300::-webkit-scrollbar-track {
                background: #f1f5f9;
            }

            .scrollbar-thumb-slate-300::-webkit-scrollbar-thumb {
                background: #cbd5e1;
                border-radius: 3px;
            }

            .scrollbar-thumb-slate-300::-webkit-scrollbar-thumb:hover {
                background: #94a3b8;
            }

            /* Animations */
            @keyframes fadeIn {
                from {
                    opacity: 0;
                    transform: translateY(10px);
                }

                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            .animate-fadeIn {
                animation: fadeIn 0.3s ease-out;
            }

            /* Typing indicator animation */
            .typing-dots span {
                display: inline-block;
                width: 4px;
                height: 4px;
                border-radius: 50%;
                background-color: #94a3b8;
                margin: 0 1px;
                animation: typingDots 1.4s infinite ease-in-out;
            }

            .typing-dots span:nth-child(1) {
                animation-delay: -0.32s;
            }

            .typing-dots span:nth-child(2) {
                animation-delay: -0.16s;
            }

            @keyframes typingDots {

                0%,
                80%,
                100% {
                    transform: scale(0.8);
                    opacity: 0.5;
                }

                40% {
                    transform: scale(1);
                    opacity: 1;
                }
            }

            /* Message status icons */
            .message-status {
                transition: all 0.2s ease;
            }

            /* Hover effects */
            .user-item:hover .user-name {
                color: #3b82f6;
            }

            /* Mobile specific styles */
            @media (max-width: 1024px) {
                #userSidebar {
                    width: 100%;
                    max-width: 380px;
                }
                
                #profilePanel {
                    width: 100%;
                }
            }

            /* Responsive text breaks */
            .break-words {
                word-wrap: break-word;
                word-break: break-word;
                overflow-wrap: break-word;
            }

            /* Focus visible for accessibility */
            .focus-visible:focus {
                outline: 2px solid #3b82f6;
                outline-offset: 2px;
            }
        </style>
    @endpush

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const chatApp = new ProfessionalChatApp();
                chatApp.init();
            });

            class ProfessionalChatApp {
                constructor() {
                    this.currentUserId = null;
                    this.currentUserData = null;
                    this.lastMessageId = 0;
                    this.pollInterval = null;
                    this.users = [];
                    this.typingTimer = null;
                    this.isTyping = false;
                    this.emojis = ['😀', '😃', '😄', '😁', '😆', '😅', '😂', '🤣', '😊', '😇', '🙂', '🙃', '😉', '😌', '😍', '🥰', '😘', '😗', '😙', '😚', '😋', '😛', '😝', '😜', '🤪', '🤨', '🧐', '🤓', '😎', '🤩', '🥳', '😏', '😒', '😞', '😔', '😟', '😕', '🙁', '☹️', '😣', '😖', '😫', '😩', '🥺', '😢', '😭', '😤', '😠', '😡', '🤬', '🤯', '😳', '🥵', '🥶', '😱', '😨', '😰', '😥', '😓', '🤗', '🤔', '🤭', '🤫', '🤥', '😶', '😐', '😑', '😬', '🙄', '😯', '😦', '😧', '😮', '😲', '🥱', '😴', '🤤', '😪', '😵', '🤐', '🥴', '🤢', '🤮', '🤧', '😷', '🤒', '🤕', '🤑', '🤠', '😈', '👿', '👹', '👺', '🤡', '💩', '👻', '💀', '☠️', '👽', '👾', '🤖', '🎃', '😺', '😸', '😹', '😻', '😼', '😽', '🙀', '😿', '😾', '❤️', '🧡', '💛', '💚', '💙', '💜', '🖤', '🤍', '🤎', '💔', '❣️', '💕', '💞', '💓', '💗', '💖', '💘', '💝', '💟', '👍', '👎', '👌', '🤏', '✌️', '🤞', '🤟', '🤘', '🤙', '👈', '👉', '👆', '🖕', '👇', '☝️', '👋', '🤚', '🖐️', '✋', '🖖', '👏', '🙌', '🤝', '🙏'];
                }

                init() {
                    this.setupEventListeners();
                    this.loadUsers();
                    this.setupEmojiPicker();
                    this.startPolling();
                    this.restoreSession();
                    this.setupMobileNavigation();
                }

                setupMobileNavigation() {
                    const openSidebar = document.getElementById('openSidebar');
                    const mobileOpenSidebar = document.getElementById('mobileOpenSidebar');
                    const closeSidebar = document.getElementById('closeSidebar');
                    const backToSidebar = document.getElementById('backToSidebar');
                    const mobileOverlay = document.getElementById('mobileOverlay');
                    const userSidebar = document.getElementById('userSidebar');
                    const mobileProfileBtn = document.getElementById('mobileProfileBtn');

                    // Open sidebar
                    [openSidebar, mobileOpenSidebar].forEach(btn => {
                        if (btn) {
                            btn.addEventListener('click', () => {
                                this.openMobileSidebar();
                            });
                        }
                    });

                    // Close sidebar
                    [closeSidebar, backToSidebar, mobileOverlay].forEach(btn => {
                        if (btn) {
                            btn.addEventListener('click', () => {
                                this.closeMobileSidebar();
                            });
                        }
                    });

                    // Mobile profile button
                    if (mobileProfileBtn) {
                        mobileProfileBtn.addEventListener('click', () => {
                            this.toggleProfilePanel();
                        });
                    }

                    // Handle window resize
                    window.addEventListener('resize', () => {
                        if (window.innerWidth >= 1024) {
                            this.closeMobileSidebar();
                            this.closeProfilePanel();
                        }
                    });
                }

                openMobileSidebar() {
                    const userSidebar = document.getElementById('userSidebar');
                    const mobileOverlay = document.getElementById('mobileOverlay');
                    
                    if (userSidebar) userSidebar.classList.remove('-translate-x-full');
                    if (mobileOverlay) mobileOverlay.classList.remove('hidden');
                    
                    // Prevent body scroll
                    document.body.style.overflow = 'hidden';
                }

                closeMobileSidebar() {
                    const userSidebar = document.getElementById('userSidebar');
                    const mobileOverlay = document.getElementById('mobileOverlay');
                    
                    if (userSidebar) userSidebar.classList.add('-translate-x-full');
                    if (mobileOverlay) mobileOverlay.classList.add('hidden');
                    
                    // Restore body scroll
                    document.body.style.overflow = '';
                }

                restoreSession() {
                    const savedSession = sessionStorage.getItem('chatSession');
                    if (savedSession) {
                        try {
                            const session = JSON.parse(savedSession);
                            if (session.userId) {
                                this.currentUserId = session.userId;
                                this.loadMessages();
                                this.highlightActiveUser();
                            }
                        } catch (error) {
                            console.error('Error restoring session:', error);
                        }
                    }
                }

                saveSession() {
                    if (this.currentUserId) {
                        sessionStorage.setItem('chatSession', JSON.stringify({
                            userId: this.currentUserId,
                            timestamp: Date.now()
                        }));
                    }
                }

                setupEventListeners() {
                    // Message form submission
                    document.getElementById('messageForm').addEventListener('submit', (e) => {
                        e.preventDefault();
                        this.sendMessage();
                    });

                    // User search with debounce
                    let searchTimeout;
                    document.getElementById('userSearch').addEventListener('input', (e) => {
                        clearTimeout(searchTimeout);
                        searchTimeout = setTimeout(() => {
                            this.filterUsers(e.target.value);
                        }, 300);
                    });

                    // Auto-resize textarea
                    const textarea = document.getElementById('messageText');
                    textarea.addEventListener('input', () => {
                        this.autoResize();
                        this.handleTyping();
                    });

                    // Enter key to send message (Shift+Enter for new line)
                    textarea.addEventListener('keydown', (e) => {
                        if (e.key === 'Enter' && !e.shiftKey) {
                            e.preventDefault();
                            this.sendMessage();
                        }
                    });

                    // File attachment with preview
                    document.getElementById('attachmentInput').addEventListener('change', (e) => {
                        this.handleFileSelection(e.target.files);
                    });

                    // Remove file preview
                    document.getElementById('removeFile')?.addEventListener('click', () => {
                        this.removeFilePreview();
                    });

                    // Emoji button
                    document.getElementById('emojiBtn').addEventListener('click', () => {
                        this.toggleEmojiPicker();
                    });

                    // Close emoji picker
                    document.getElementById('closeEmojiPicker')?.addEventListener('click', () => {
                        document.getElementById('emojiPicker').style.display = 'none';
                    });

                    // View profile button
                    document.getElementById('viewProfileBtn')?.addEventListener('click', () => {
                        this.toggleProfilePanel();
                    });

                    // Close profile panel
                    document.getElementById('closeProfileBtn')?.addEventListener('click', () => {
                        this.closeProfilePanel();
                    });

                    // Close emoji picker when clicking outside
                    document.addEventListener('click', (e) => {
                        const emojiPicker = document.getElementById('emojiPicker');
                        const emojiBtn = document.getElementById('emojiBtn');
                        if (emojiPicker && !emojiPicker.contains(e.target) && !emojiBtn.contains(e.target)) {
                            emojiPicker.style.display = 'none';
                        }
                    });
                }

                setupEmojiPicker() {
                    const emojiGrid = document.getElementById('emojiGrid');
                    if (!emojiGrid) return;

                    emojiGrid.innerHTML = '';
                    this.emojis.forEach(emoji => {
                        const emojiDiv = document.createElement('div');
                        emojiDiv.className = 'emoji-item text-xl sm:text-2xl p-2 hover:bg-gray-100 rounded cursor-pointer transition-colors';
                        emojiDiv.textContent = emoji;
                        emojiDiv.addEventListener('click', () => {
                            this.insertEmoji(emoji);
                        });
                        emojiGrid.appendChild(emojiDiv);
                    });
                }

                insertEmoji(emoji) {
                    const textarea = document.getElementById('messageText');
                    const start = textarea.selectionStart;
                    const end = textarea.selectionEnd;
                    const text = textarea.value;
                    
                    textarea.value = text.substring(0, start) + emoji + text.substring(end);
                    textarea.selectionStart = textarea.selectionEnd = start + emoji.length;
                    textarea.focus();
                    
                    this.autoResize();
                    document.getElementById('emojiPicker').style.display = 'none';
                }

                toggleEmojiPicker() {
                    const picker = document.getElementById('emojiPicker');
                    if (picker) {
                        picker.style.display = picker.style.display === 'none' ? 'flex' : 'none';
                    }
                }

                toggleProfilePanel() {
                    if (this.currentUserData) {
                        this.showProfilePanel(this.currentUserData);
                    }
                }

                showProfilePanel(user) {
                    const panel = document.getElementById('profilePanel');
                    const content = document.getElementById('profileContent');
                    const template = document.getElementById('profileTemplate');
                    
                    if (!panel || !content || !template) return;

                    const profileHtml = template.content.cloneNode(true);
                    profileHtml.querySelector('.profile-name').textContent = user.name || 'Unknown User';
                    profileHtml.querySelector('.profile-email').textContent = user.email || 'No email';
                    profileHtml.querySelector('.profile-phone').textContent = user.phone || 'Not provided';
                    profileHtml.querySelector('.profile-role').textContent = user.role || 'User';
                    profileHtml.querySelector('.profile-joined').textContent = this.formatDate(user.created_at || new Date());
                    
                    content.innerHTML = '';
                    content.appendChild(profileHtml);
                    
                    panel.style.transform = 'translateX(0)';
                    
                    // For mobile, add overlay
                    if (window.innerWidth < 1024) {
                        document.body.style.overflow = 'hidden';
                    }
                }

                closeProfilePanel() {
                    const panel = document.getElementById('profilePanel');
                    if (panel) {
                        panel.style.transform = 'translateX(100%)';
                        document.body.style.overflow = '';
                    }
                }

                handleFileSelection(files) {
                    if (files.length > 0) {
                        const file = files[0];
                        const maxSize = 10 * 1024 * 1024; // 10MB

                        if (file.size > maxSize) {
                            alert('File size must be less than 10MB');
                            return;
                        }

                        this.showFilePreview(file);
                    }
                }

                showFilePreview(file) {
                    const preview = document.getElementById('filePreview');
                    const fileName = document.getElementById('fileName');
                    
                    if (preview && fileName) {
                        fileName.textContent = file.name;
                        preview.style.display = 'flex';
                    }
                }

                removeFilePreview() {
                    const preview = document.getElementById('filePreview');
                    const fileInput = document.getElementById('attachmentInput');
                    
                    if (preview) preview.style.display = 'none';
                    if (fileInput) fileInput.value = '';
                }

                autoResize() {
                    const textarea = document.getElementById('messageText');
                    if (!textarea) return;
                    
                    const maxHeight = 120;
                    textarea.style.height = 'auto';
                    const newHeight = Math.min(textarea.scrollHeight, maxHeight);
                    textarea.style.height = newHeight + 'px';
                    textarea.style.overflowY = newHeight >= maxHeight ? 'auto' : 'hidden';
                }

                highlightActiveUser() {
                    document.querySelectorAll('.user-item').forEach(item => {
                        item.classList.remove('bg-blue-100', 'border-blue-300');
                    });

                    const selectedItem = document.querySelector(`[data-user-id="${this.currentUserId}"]`);
                    if (selectedItem) {
                        selectedItem.classList.add('bg-blue-100', 'border-blue-300');
                    }
                }

                formatDate(dateString) {
                    return new Date(dateString).toLocaleDateString('en-US', {
                        year: 'numeric',
                        month: 'long',
                        day: 'numeric'
                    });
                }

                handleTyping() {
                    if (!this.currentUserId) return;

                    // Send typing indicator to backend
                    if (!this.isTyping) {
                        this.isTyping = true;
                        this.sendTypingIndicator(true);
                    }

                    // Clear existing timer
                    clearTimeout(this.typingTimer);

                    // Set new timer to stop typing indicator
                    this.typingTimer = setTimeout(() => {
                        this.isTyping = false;
                        this.sendTypingIndicator(false);
                    }, 1000);
                }

                async sendTypingIndicator(isTyping) {
                    if (!this.currentUserId) return;

                    try {
                        await fetch('/ims/chat/typing', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            },
                            body: JSON.stringify({
                                receiver_id: this.currentUserId,
                                is_typing: isTyping
                            })
                        });
                    } catch (error) {
                        console.error('Error sending typing indicator:', error);
                    }
                }

                showTypingIndicator(userName) {
                    const indicator = document.getElementById('typingIndicator');
                    const userSpan = document.getElementById('typingUser');
                    if (indicator && userSpan) {
                        userSpan.textContent = `${userName} is typing...`;
                        indicator.style.display = 'block';
                    }
                }

                hideTypingIndicator() {
                    const indicator = document.getElementById('typingIndicator');
                    if (indicator) {
                        indicator.style.display = 'none';
                    }
                }

                filterUsers(query) {
                    const filteredUsers = this.users.filter(user =>
                        user.name.toLowerCase().includes(query.toLowerCase()) ||
                        user.email.toLowerCase().includes(query.toLowerCase())
                    );
                    this.renderUsers(filteredUsers);
                }

                async loadUsers() {
                    try {
                        const response = await fetch('/ims/chat/users');
                        const users = await response.json();
                        this.users = users;
                        this.renderUsers(users);
                    } catch (error) {
                        console.error('Error loading users:', error);
                    }
                }

                renderUsers(users) {
                    const container = document.getElementById('usersContainer');
                    const template = document.getElementById('userItemTemplate');

                    if (!container || !template) return;

                    container.innerHTML = '';

                    users.forEach(user => {
                        const userElement = template.content.cloneNode(true);
                        const userDiv = userElement.querySelector('.user-item');

                        userDiv.dataset.userId = user.id;
                        userDiv.querySelector('.user-name').textContent = user.name;
                        userDiv.querySelector('.user-avatar').textContent = user.name.charAt(0).toUpperCase();

                        // Handle unread count
                        const unreadBadge = userDiv.querySelector('.unread-badge');
                        const unreadCount = userDiv.querySelector('.unread-count');
                        if (user.unread_count > 0) {
                            unreadBadge.style.display = 'flex';
                            unreadCount.textContent = user.unread_count > 99 ? '99+' : user.unread_count;
                        } else {
                            unreadBadge.style.display = 'none';
                        }

                        // Handle last message
                        const lastMessageText = userDiv.querySelector('.last-message-text');
                        const lastMessageTime = userDiv.querySelector('.last-message-time');
                        if (user.last_message) {
                            lastMessageText.textContent = this.truncateMessage(user.last_message, 40);
                            lastMessageTime.textContent = this.formatTime(user.last_message_time);
                        }

                        // Add click event
                        userDiv.addEventListener('click', () => {
                            this.selectUser(user);
                        });

                        container.appendChild(userElement);
                    });
                }

                renderMessages(messages) {
                    const container = document.getElementById('chatMessages');
                    const sentTemplate = document.getElementById('sentMessageTemplate');
                    const receivedTemplate = document.getElementById('receivedMessageTemplate');
                    const dateTemplate = document.getElementById('dateSeparatorTemplate');

                    if (!container || !sentTemplate || !receivedTemplate) return;

                    container.innerHTML = '';
                    let lastDate = null;

                    messages.forEach(message => {
                        const messageDate = new Date(message.created_at).toDateString();

                        // Add date separator if date changed
                        if (messageDate !== lastDate) {
                            if (dateTemplate) {
                                const dateSeparator = dateTemplate.content.cloneNode(true);
                                dateSeparator.querySelector('.date-text').textContent = this.formatMessageDate(message.created_at);
                                container.appendChild(dateSeparator);
                            }
                            lastDate = messageDate;
                        }

                        const template = message.sender_id == {{ auth()->id() }} ? sentTemplate : receivedTemplate;
                        const messageElement = template.content.cloneNode(true);

                        messageElement.querySelector('[data-message-id]').setAttribute('data-message-id', message.id);
                        messageElement.querySelector('.message-text').textContent = message.message;
                        messageElement.querySelector('.message-time span').textContent = this.formatTime(message.created_at);

                        // Handle attachments
                        if (message.attachment_path) {
                            const attachmentDiv = messageElement.querySelector('.message-attachment');
                            const attachmentLink = messageElement.querySelector('.attachment-link');
                            const attachmentName = messageElement.querySelector('.attachment-name');

                            if (attachmentDiv && attachmentLink && attachmentName) {
                                attachmentLink.href = message.attachment_path;
                                attachmentName.textContent = message.attachment_name || 'Download File';
                                attachmentDiv.style.display = 'block';
                            }
                        }

                        container.appendChild(messageElement);
                    });

                    // Scroll to bottom
                    setTimeout(() => {
                        this.scrollToBottom();
                    }, 100);
                }

                scrollToBottom() {
                    const container = document.getElementById('chatMessages');
                    if (container) {
                        container.scrollTop = container.scrollHeight;
                    }
                }

                formatMessageDate(dateString) {
                    const date = new Date(dateString);
                    const today = new Date();
                    const yesterday = new Date(today);
                    yesterday.setDate(yesterday.getDate() - 1);

                    if (date.toDateString() === today.toDateString()) {
                        return 'Today';
                    } else if (date.toDateString() === yesterday.toDateString()) {
                        return 'Yesterday';
                    } else {
                        return date.toLocaleDateString('en-US', {
                            weekday: 'long',
                            year: 'numeric',
                            month: 'long',
                            day: 'numeric'
                        });
                    }
                }

                formatTime(dateString) {
                    return new Date(dateString).toLocaleTimeString('en-US', {
                        hour: '2-digit',
                        minute: '2-digit',
                        hour12: true
                    });
                }

                truncateMessage(message, length) {
                    return message.length > length ? message.substring(0, length) + '...' : message;
                }

                selectUser(user) {
                    this.currentUserId = user.id;
                    this.currentUserData = user;
                    this.saveSession();

                    this.highlightActiveUser();
                    this.updateChatHeader(user);
                    this.loadMessages();

                    // Close mobile sidebar when user is selected
                    if (window.innerWidth < 1024) {
                        this.closeMobileSidebar();
                    }

                    // Clear message input
                    const messageText = document.getElementById('messageText');
                    if (messageText) {
                        messageText.value = '';
                        this.autoResize();
                    }
                }

                updateChatHeader(user) {
                    const chatInterface = document.getElementById('chatInterface');
                    const welcomeScreen = document.getElementById('welcomeScreen');
                    const chatUserName = document.getElementById('chatUserName');
                    const chatUserAvatar = document.getElementById('chatUserAvatar');
                    
                    if (chatInterface) chatInterface.style.display = 'flex';
                    if (welcomeScreen) welcomeScreen.style.display = 'none';
                    
                    if (chatUserName) chatUserName.textContent = user.name;
                    if (chatUserAvatar) {
                        chatUserAvatar.innerHTML = user.name.charAt(0).toUpperCase();
                    }
                }

                async loadMessages() {
                    if (!this.currentUserId) return;

                    try {
                        const response = await fetch(`/ims/chat/messages/${this.currentUserId}`);
                        const data = await response.json();

                        if (data.messages) {
                            this.renderMessages(data.messages);
                            if (data.messages.length > 0) {
                                this.lastMessageId = Math.max(...data.messages.map(m => m.id));
                            }
                        }
                    } catch (error) {
                        console.error('Error loading messages:', error);
                    }
                }

                startPolling() {
                    this.pollInterval = setInterval(() => {
                        if (this.currentUserId) {
                            this.loadMessages();
                        }
                    }, 2000);
                }

                async sendMessage() {
                    const messageText = document.getElementById('messageText');
                    const fileInput = document.getElementById('attachmentInput');
                    const message = messageText.value.trim();

                    if (!message && !fileInput.files.length) return;
                    if (!this.currentUserId) {
                        alert('Please select a user to chat with');
                        return;
                    }

                    const formData = new FormData();
                    formData.append('receiver_id', this.currentUserId);
                    formData.append('message', message);

                    if (fileInput.files.length > 0) {
                        formData.append('attachment', fileInput.files[0]);
                    }

                    try {
                        const response = await fetch('/ims/chat/send', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            },
                            body: formData
                        });

                        if (response.ok) {
                            messageText.value = '';
                            this.autoResize();
                            this.removeFilePreview();
                            this.loadMessages();
                        } else {
                            throw new Error('Failed to send message');
                        }
                    } catch (error) {
                        console.error('Error sending message:', error);
                        alert('Failed to send message. Please try again.');
                    }
                }
            }
        </script>
    @endpush
</x-app-layout>