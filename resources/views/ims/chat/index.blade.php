<x-app-layout>
    <x-slot name="title">
        {{ __('Chat') }} - {{ config('app.name', 'IMS') }}
    </x-slot>

    <div class="max-w-full mx-auto p-4">
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden h-[calc(100vh-120px)]">
            <div class="flex h-full">
                <!-- Enhanced User List Sidebar -->
                <div class="w-80 bg-gradient-to-b from-slate-50 to-white border-r border-slate-200 flex flex-col">
                    <!-- Modern Chat Header -->
                    <div class="p-6 bg-gradient-to-r from-blue-600 to-purple-600 text-white">
                        <h2 class="text-2xl font-bold flex items-center">
                            <div class="w-8 h-8 bg-white/20 rounded-full flex items-center justify-center mr-3">
                                <i class="fas fa-comments text-sm"></i>
                            </div>
                            Messages
                        </h2>
                        <p class="text-blue-100 mt-1 text-sm">{{ Auth::user()->name }}</p>
                    </div>

                    <!-- Enhanced Search -->
                    <div class="p-4 bg-white border-b border-slate-200">
                        <div class="relative">
                            <input type="text" id="userSearch" placeholder="Search conversations..."
                                class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent focus:bg-white transition-all duration-200">
                            <i class="fas fa-search absolute left-4 top-4 text-slate-400"></i>
                        </div>
                    </div>

                    <!-- Enhanced Users List -->
                    <div class="flex-1 overflow-y-auto scrollbar-thin scrollbar-thumb-slate-300" id="usersList">
                        <div class="p-2">
                            <div class="space-y-1" id="usersContainer">
                                <!-- Users will be loaded here -->
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Enhanced Chat Window -->
                <div class="flex-1 flex flex-col bg-gradient-to-b from-slate-50 to-white">
                    <!-- Enhanced Chat Header -->
                    <div class="p-4 bg-white border-b border-slate-200 shadow-sm" id="chatHeader"
                        style="display: none;">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="relative">
                                    <div class="w-12 h-12 rounded-full bg-gradient-to-br from-blue-400 to-purple-500 flex items-center justify-center mr-4"
                                        id="selectedUserAvatar">
                                        <i class="fas fa-user text-white"></i>
                                    </div>
                                    <div class="absolute -bottom-1 -right-1 w-4 h-4 bg-green-400 border-2 border-white rounded-full"
                                        id="onlineIndicator"></div>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-slate-800 text-lg" id="selectedUserName">Select a user
                                    </h3>
                                    <p class="text-sm text-slate-500 flex items-center" id="selectedUserStatus">
                                        <span class="w-2 h-2 bg-green-400 rounded-full mr-2"></span>
                                        Online
                                    </p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-2">
                                <button
                                    class="p-2 text-slate-500 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Enhanced Welcome Message -->
                    <div class="flex-1 flex items-center justify-center" id="welcomeMessage">
                        <div class="text-center max-w-md">
                            <div
                                class="w-24 h-24 bg-gradient-to-br from-blue-400 to-purple-500 rounded-full flex items-center justify-center mx-auto mb-6">
                                <i class="fas fa-comments text-3xl text-white"></i>
                            </div>
                            <h3 class="text-2xl font-bold text-slate-700 mb-3">Start a Conversation</h3>
                            <p class="text-slate-500 leading-relaxed">Select a contact from the left panel to begin
                                chatting. You can send messages, share files, and collaborate in real-time.</p>
                        </div>
                    </div>

                    <!-- Enhanced Chat Messages -->
                    <div class="flex-1 overflow-y-auto p-6 space-y-4 scrollbar-thin scrollbar-thumb-slate-300"
                        id="chatMessages" style="display: none;">
                        <!-- Messages will be loaded here -->
                    </div>

                    <!-- Enhanced Message Input -->
                    <div class="p-4 bg-white border-t border-slate-200" id="messageInput" style="display: none;">
                        <form id="messageForm" class="flex items-end space-x-3">
                            <div class="flex-1 relative">
                                <textarea id="messageText" placeholder="Type your message..." rows="1"
                                    class="w-full p-4 pr-12 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none bg-slate-50 focus:bg-white transition-all duration-200"
                                    style="min-height: 52px; max-height: 120px;"></textarea>
                                <button type="button" id="emojiBtn"
                                    class="absolute right-3 top-1/2 transform -translate-y-1/2 text-slate-400 hover:text-yellow-500 transition-colors">
                                    <i class="fas fa-smile"></i>
                                </button>
                            </div>
                            <div class="flex space-x-2">
                                <label for="attachmentInput"
                                    class="cursor-pointer p-3 text-slate-500 hover:text-blue-600 hover:bg-blue-50 rounded-xl transition-all duration-200">
                                    <i class="fas fa-paperclip"></i>
                                </label>
                                <input type="file" id="attachmentInput" class="hidden"
                                    accept="image/*,.pdf,.doc,.docx,.txt">
                                <button type="submit"
                                    class="px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-xl hover:from-blue-700 hover:to-purple-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-200 shadow-lg">
                                    <i class="fas fa-paper-plane"></i>
                                </button>
                            </div>
                        </form>
                        <!-- Typing Indicator -->
                        <div id="typingIndicator" class="mt-2 text-sm text-slate-500 italic" style="display: none;">
                            <span class="typing-dots">
                                <span></span>
                                <span></span>
                                <span></span>
                            </span>
                            <span class="ml-2" id="typingUser">Someone is typing...</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Enhanced User Item Template -->
    <template id="userItemTemplate">
        <div class="user-item flex items-center p-3 rounded-xl cursor-pointer hover:bg-blue-50 transition-all duration-200 border border-transparent hover:border-blue-200 hover:shadow-sm"
            data-user-id="">
            <div class="relative">
                <div
                    class="w-14 h-14 rounded-full bg-gradient-to-br from-blue-400 to-purple-500 flex items-center justify-center mr-4 user-avatar">
                    <i class="fas fa-user text-white"></i>
                </div>
                <div class="absolute -top-1 -right-1 w-5 h-5 bg-red-500 text-white text-xs rounded-full flex items-center justify-center unread-badge shadow-lg"
                    style="display: none;">
                    <span class="unread-count font-semibold">0</span>
                </div>
                <div
                    class="absolute -bottom-1 -right-1 w-4 h-4 bg-green-400 border-2 border-white rounded-full online-status">
                </div>
            </div>
            <div class="flex-1 min-w-0">
                <div class="flex items-center justify-between mb-1">
                    <h4 class="font-semibold text-slate-800 truncate user-name"></h4>
                    <span class="text-xs text-slate-500 last-message-time"></span>
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

    <!-- Enhanced Message Templates -->
    <template id="sentMessageTemplate">
        <div class="flex justify-end message-item animate-fadeIn" data-message-id="">
            <div class="max-w-sm lg:max-w-md">
                <div
                    class="bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-2xl rounded-br-md px-5 py-3 shadow-lg">
                    <p class="message-text leading-relaxed"></p>
                    <div class="message-attachment mt-2" style="display: none;">
                        <div class="bg-white/20 rounded-lg p-2">
                            <a href="#"
                                class="text-blue-100 hover:text-white flex items-center attachment-link">
                                <i class="fas fa-paperclip mr-2"></i>
                                <span class="attachment-name text-sm"></span>
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
            <div class="max-w-sm lg:max-w-md">
                <div
                    class="bg-white border border-slate-200 text-slate-800 rounded-2xl rounded-bl-md px-5 py-3 shadow-sm">
                    <p class="message-text leading-relaxed"></p>
                    <div class="message-attachment mt-2" style="display: none;">
                        <div class="bg-slate-50 rounded-lg p-2">
                            <a href="#"
                                class="text-blue-600 hover:text-blue-800 flex items-center attachment-link">
                                <i class="fas fa-paperclip mr-2"></i>
                                <span class="attachment-name text-sm"></span>
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

    <!-- Date Separator Template -->
    <template id="dateSeparatorTemplate">
        <div class="flex items-center justify-center my-6">
            <div class="bg-slate-100 text-slate-600 px-4 py-2 rounded-full text-sm font-medium date-text"></div>
        </div>
    </template>

    @push('styles')
        <style>
            /* Custom Scrollbar */
            .scrollbar-thin {
                scrollbar-width: thin;
            }

            .scrollbar-thumb-slate-300::-webkit-scrollbar {
                width: 6px;
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
        </style>
    @endpush

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const chatApp = new EnhancedChatApplication();
                chatApp.init();
            });

            class EnhancedChatApplication {
                constructor() {
                    this.currentUserId = null;
                    this.lastMessageId = 0;
                    this.pollInterval = null;
                    this.users = [];
                    this.typingTimer = null;
                    this.isTyping = false;
                }

                init() {
                    this.loadUsers();
                    this.setupEventListeners();
                    this.setupAutoResize();
                    this.startPolling();
                    this.initializeTypingIndicator();
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
                    textarea.addEventListener('input', this.autoResize);

                    // Typing indicator
                    textarea.addEventListener('input', () => {
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
                        const file = e.target.files[0];
                        if (file) {
                            this.showFilePreview(file);
                        }
                    });

                    // Emoji button (placeholder for emoji picker)
                    document.getElementById('emojiBtn').addEventListener('click', () => {
                        this.toggleEmojiPicker();
                    });
                }

                setupAutoResize() {
                    const textarea = document.getElementById('messageText');
                    this.autoResize.call(textarea);
                }

                autoResize() {
                    this.style.height = 'auto';
                    this.style.height = Math.min(this.scrollHeight, 120) + 'px';
                }

                initializeTypingIndicator() {
                    // Initialize typing indicator functionality
                    this.typingUsers = new Set();
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
                    }, 2000);
                }

                async sendTypingIndicator(isTyping) {
                    try {
                        await fetch('/ims/chat/typing', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                    'content')
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
                    userSpan.textContent = `${userName} is typing...`;
                    indicator.style.display = 'block';
                }

                hideTypingIndicator() {
                    const indicator = document.getElementById('typingIndicator');
                    indicator.style.display = 'none';
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

                    container.innerHTML = '';

                    users.forEach(user => {
                        const userElement = template.content.cloneNode(true);
                        const userDiv = userElement.querySelector('.user-item');

                        userDiv.dataset.userId = user.id;
                        userDiv.querySelector('.user-name').textContent = user.name;

                        // Enhanced avatar with gradient fallback
                        if (user.profile_photo) {
                            const avatar = userDiv.querySelector('.user-avatar');
                            avatar.innerHTML =
                                `<img src="/storage/${user.profile_photo}" alt="${user.name}" class="w-full h-full object-cover rounded-full">`;
                        }

                        // Enhanced unread badge
                        if (user.unread_count > 0) {
                            const badge = userDiv.querySelector('.unread-badge');
                            badge.style.display = 'flex';
                            badge.querySelector('.unread-count').textContent = user.unread_count;
                        }

                        // Online status (you can integrate with real-time presence)
                        const onlineStatus = userDiv.querySelector('.online-status');
                        if (user.is_online) {
                            onlineStatus.classList.remove('bg-gray-300');
                            onlineStatus.classList.add('bg-green-400');
                        } else {
                            onlineStatus.classList.remove('bg-green-400');
                            onlineStatus.classList.add('bg-gray-300');
                        }

                        // Last message with enhanced formatting
                        if (user.last_message) {
                            userDiv.querySelector('.last-message-text').textContent = this.truncateMessage(user
                                .last_message.message, 40);
                            userDiv.querySelector('.last-message-time').textContent = this.formatTime(user
                                .last_message.created_at);
                        }

                        userDiv.addEventListener('click', () => {
                            this.selectUser(user.id, user.name, user.profile_photo);
                            this.markUserAsActive(userDiv);
                        });

                        container.appendChild(userElement);
                    });
                }

                markUserAsActive(userDiv) {
                    // Remove active state from all users
                    document.querySelectorAll('.user-item').forEach(item => {
                        item.classList.remove('bg-blue-100', 'border-blue-300');
                    });

                    // Add active state to selected user
                    userDiv.classList.add('bg-blue-100', 'border-blue-300');
                }

                filterUsers(query) {
                    const filteredUsers = this.users.filter(user =>
                        user.name.toLowerCase().includes(query.toLowerCase()) ||
                        user.email.toLowerCase().includes(query.toLowerCase())
                    );
                    this.renderUsers(filteredUsers);
                }

                async selectUser(userId, userName, userPhoto) {
                    this.currentUserId = userId;

                    // Smooth transition
                    document.getElementById('welcomeMessage').style.display = 'none';
                    document.getElementById('chatHeader').style.display = 'block';
                    document.getElementById('chatMessages').style.display = 'block';
                    document.getElementById('messageInput').style.display = 'block';

                    // Update selected user info with enhanced styling
                    document.getElementById('selectedUserName').textContent = userName;

                    const avatar = document.getElementById('selectedUserAvatar');
                    if (userPhoto) {
                        avatar.innerHTML =
                            `<img src="/storage/${userPhoto}" alt="${userName}" class="w-full h-full object-cover rounded-full">`;
                    } else {
                        avatar.innerHTML = '<i class="fas fa-user text-white"></i>';
                    }

                    // Load chat history
                    await this.loadChatHistory(userId);

                    // Update user item to remove unread badge
                    this.updateUserUnreadCount(userId, 0);

                    // Focus message input
                    document.getElementById('messageText').focus();
                }

                async loadChatHistory(userId) {
                    try {
                        const response = await fetch(`/ims/chat/history/${userId}`);
                        const data = await response.json();

                        this.renderMessages(data.messages);
                        this.scrollToBottom();

                        if (data.messages.length > 0) {
                            this.lastMessageId = Math.max(...data.messages.map(m => m.id));
                        }
                    } catch (error) {
                        console.error('Error loading chat history:', error);
                    }
                }

                renderMessages(messages) {
                    const container = document.getElementById('chatMessages');
                    container.innerHTML = '';

                    let currentDate = null;

                    messages.forEach(message => {
                        const messageDate = new Date(message.created_at).toDateString();

                        // Add date separator if date changed
                        if (currentDate !== messageDate) {
                            this.renderDateSeparator(messageDate);
                            currentDate = messageDate;
                        }

                        this.renderMessage(message);
                    });
                }

                renderDateSeparator(dateString) {
                    const container = document.getElementById('chatMessages');
                    const template = document.getElementById('dateSeparatorTemplate');
                    const dateElement = template.content.cloneNode(true);

                    const date = new Date(dateString);
                    const today = new Date().toDateString();
                    const yesterday = new Date(Date.now() - 86400000).toDateString();

                    let displayText;
                    if (dateString === today) {
                        displayText = 'Today';
                    } else if (dateString === yesterday) {
                        displayText = 'Yesterday';
                    } else {
                        displayText = date.toLocaleDateString('en-US', {
                            weekday: 'long',
                            year: 'numeric',
                            month: 'long',
                            day: 'numeric'
                        });
                    }

                    dateElement.querySelector('.date-text').textContent = displayText;
                    container.appendChild(dateElement);
                }

                renderMessage(message) {
                    const container = document.getElementById('chatMessages');
                    const isSent = message.sender_id == {{ Auth::id() }};
                    const template = document.getElementById(isSent ? 'sentMessageTemplate' : 'receivedMessageTemplate');

                    const messageElement = template.content.cloneNode(true);
                    const messageDiv = messageElement.querySelector('.message-item');

                    messageDiv.dataset.messageId = message.id;
                    messageDiv.querySelector('.message-text').textContent = message.message;
                    messageDiv.querySelector('.message-time span').textContent = this.formatTime(message.created_at);

                    if (message.attachment) {
                        const attachmentDiv = messageDiv.querySelector('.message-attachment');
                        const attachmentLink = attachmentDiv.querySelector('.attachment-link');
                        attachmentDiv.style.display = 'block';
                        attachmentLink.href = `/ims/chat/download/${message.id}`;
                        attachmentLink.querySelector('.attachment-name').textContent = this.getFileName(message.attachment);
                    }

                    container.appendChild(messageElement);
                }

                async sendMessage() {
                    const messageText = document.getElementById('messageText').value.trim();
                    const attachmentInput = document.getElementById('attachmentInput');

                    if (!messageText && !attachmentInput.files[0]) return;
                    if (!this.currentUserId) return;

                    const formData = new FormData();
                    formData.append('receiver_id', this.currentUserId);
                    formData.append('message', messageText);

                    if (attachmentInput.files[0]) {
                        formData.append('attachment', attachmentInput.files[0]);
                    }

                    try {
                        const response = await fetch('/ims/chat/send', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                    'content')
                            },
                            body: formData
                        });

                        const data = await response.json();

                        if (data.success) {
                            this.renderMessage(data.message);
                            this.scrollToBottom();

                            // Clear form with animation
                            document.getElementById('messageText').value = '';
                            attachmentInput.value = '';
                            this.autoResize.call(document.getElementById('messageText'));

                            this.lastMessageId = Math.max(this.lastMessageId, data.message.id);

                            // Stop typing indicator
                            this.isTyping = false;
                            this.sendTypingIndicator(false);
                        }
                    } catch (error) {
                        console.error('Error sending message:', error);
                    }
                }

                startPolling() {
                    this.pollInterval = setInterval(() => {
                        this.pollNewMessages();
                    }, 2000); // Poll every 2 seconds for better responsiveness
                }

                async pollNewMessages() {
                    if (!this.currentUserId) return;

                    try {
                        const response = await fetch(
                            `/ims/chat/new-messages?last_message_id=${this.lastMessageId}&with_user_id=${this.currentUserId}`
                        );
                        const data = await response.json();

                        // Handle new messages
                        if (data.messages && data.messages.length > 0) {
                            data.messages.forEach(message => {
                                this.renderMessage(message);
                                this.lastMessageId = Math.max(this.lastMessageId, message.id);
                            });
                            this.scrollToBottom();
                        }

                        // Handle typing indicators
                        if (data.typing_users) {
                            if (data.typing_users.length > 0) {
                                this.showTypingIndicator(data.typing_users[0].name);
                            } else {
                                this.hideTypingIndicator();
                            }
                        }
                    } catch (error) {
                        console.error('Error polling messages:', error);
                    }
                }

                updateUserUnreadCount(userId, count) {
                    const userElement = document.querySelector(`[data-user-id="${userId}"]`);
                    if (userElement) {
                        const badge = userElement.querySelector('.unread-badge');
                        if (count > 0) {
                            badge.style.display = 'flex';
                            badge.querySelector('.unread-count').textContent = count;
                        } else {
                            badge.style.display = 'none';
                        }
                    }
                }

                scrollToBottom() {
                    const container = document.getElementById('chatMessages');
                    container.scrollTo({
                        top: container.scrollHeight,
                        behavior: 'smooth'
                    });
                }

                formatTime(timestamp) {
                    const date = new Date(timestamp);
                    const now = new Date();
                    const diff = now - date;

                    if (diff < 24 * 60 * 60 * 1000) {
                        return date.toLocaleTimeString([], {
                            hour: '2-digit',
                            minute: '2-digit'
                        });
                    } else if (diff < 7 * 24 * 60 * 60 * 1000) {
                        return date.toLocaleDateString([], {
                            weekday: 'short',
                            hour: '2-digit',
                            minute: '2-digit'
                        });
                    } else {
                        return date.toLocaleDateString();
                    }
                }

                truncateMessage(message, length) {
                    return message.length > length ? message.substring(0, length) + '...' : message;
                }

                getFileName(path) {
                    return path.split('/').pop() || 'Attachment';
                }

                showFilePreview(file) {
                    // Add file preview functionality here
                    console.log('File selected:', file.name);
                }

                toggleEmojiPicker() {
                    // Add emoji picker functionality here
                    console.log('Emoji picker toggled');
                }
            }
        </script>
    @endpush
</x-app-layout>