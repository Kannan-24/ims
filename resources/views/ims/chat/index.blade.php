<x-app-layout>
    <x-slot name="title">
        {{ __('Chat') }} - {{ config('app.name', 'IMS') }}
    </x-slot>

    <!-- Add Alpine.js CDN for testing -->
    </head>

    <body class="bg-gray-50 dark:bg-gray-900">
        <div class="container mx-auto p-4 h-screen flex" x-data="whatsappChat">

            <div class="flex h-screen bg-gray-100 overflow-hidden" x-data="whatsappChat()" x-init="init()">
                <!-- Mobile Overlay -->
                <div x-show="showMobileSidebar" x-transition:enter="transition-opacity ease-out duration-300"
                    x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                    x-transition:leave="transition-opacity ease-in duration-200" x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0" @click="showMobileSidebar = false"
                    class="fixed inset-0 bg-black bg-opacity-50 z-30 lg:hidden"></div>

                <!-- Contacts Sidebar -->
                <div class="flex flex-col w-full max-w-sm lg:w-80 bg-white border-r border-gray-300 lg:relative transition-transform duration-300 ease-in-out z-40"
                    :class="{ '-translate-x-full': !showMobileSidebar && window.innerWidth < 1024, 'fixed': window.innerWidth <
                            1024 }">

                    <!-- Header -->
                    <div class="flex items-center justify-between p-4 bg-gray-50 border-b border-gray-200">
                        <div class="flex items-center">
                            <div
                                class="w-10 h-10 bg-green-500 rounded-full flex items-center justify-center text-white font-bold">
                                {{ substr(auth()->user()->name, 0, 1) }}
                            </div>
                            <div class="ml-3">
                                <h2 class="text-lg font-semibold text-gray-900">Chats</h2>
                            </div>
                        </div>
                        <div class="flex items-center space-x-2">
                            <button @click="showMobileSidebar = false"
                                class="lg:hidden p-2 text-gray-500 hover:bg-gray-200 rounded-full">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Search -->
                    <div class="p-3 bg-white border-b border-gray-200">
                        <div class="relative">
                            <input type="text" x-model="searchQuery" @input="filterUsers()"
                                placeholder="Search or start new chat"
                                class="w-full pl-10 pr-4 py-2 bg-gray-100 border-0 rounded-lg focus:ring-2 focus:ring-green-500 focus:bg-white transition-all">
                            <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                        </div>
                    </div>

                    <!-- Contacts List -->
                    <div class="flex-1 overflow-y-auto">
                        <template x-for="user in filteredUsers" :key="user.id">
                            <div class="flex items-center p-3 hover:bg-gray-50 cursor-pointer border-b border-gray-100"
                                :class="{ 'bg-gray-100': selectedUserId === user.id }" @click="selectUser(user)">
                                <div class="relative">
                                    <div
                                        class="w-12 h-12 bg-green-500 rounded-full flex items-center justify-center text-white font-bold">
                                        <span x-text="user.name.charAt(0).toUpperCase()"></span>
                                    </div>
                                    <div class="absolute bottom-0 right-0 w-3 h-3 bg-green-400 border-2 border-white rounded-full"
                                        x-show="user.online"></div>
                                </div>
                                <div class="ml-3 flex-1 min-w-0">
                                    <div class="flex justify-between items-start">
                                        <h4 class="text-sm font-semibold text-gray-900 truncate" x-text="user.name">
                                        </h4>
                                        <span class="text-xs text-gray-500" x-text="formatTime(user.last_message_time)"
                                            x-show="user.last_message_time"></span>
                                    </div>
                                    <p class="text-sm text-gray-600 truncate"
                                        x-text="user.last_message || 'No messages yet'"></p>
                                </div>
                                <div x-show="user.unread_count > 0"
                                    class="bg-green-500 text-white text-xs rounded-full px-2 py-1 min-w-[20px] text-center">
                                    <span x-text="user.unread_count"></span>
                                </div>
                            </div>
                        </template>

                        <!-- Loading state -->
                        <div x-show="users.length === 0 && filteredUsers.length === 0" class="p-8 text-center">
                            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-green-500 mx-auto mb-4">
                            </div>
                            <p class="text-gray-500">Loading contacts...</p>
                        </div>

                        <!-- No users found -->
                        <div x-show="users.length > 0 && filteredUsers.length === 0" class="p-8 text-center">
                            <i class="fas fa-users text-gray-300 text-4xl mb-4"></i>
                            <p class="text-gray-500">No contacts found</p>
                        </div>
                    </div>
                </div>

                <!-- Chat Area -->
                <div class="flex-1 flex flex-col bg-white">
                    <!-- Welcome Screen -->
                    <div x-show="!selectedUser" class="flex-1 flex items-center justify-center bg-gray-50">
                        <div class="text-center">
                            <div
                                class="w-64 h-64 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-8">
                                <i class="fas fa-comments text-green-500 text-6xl"></i>
                            </div>
                            <h3 class="text-2xl font-semibold text-gray-700 mb-4">WhatsApp Web</h3>
                            <p class="text-gray-500 max-w-md">
                                Send and receive messages without keeping your phone online.<br>
                                Use WhatsApp Web up to 4 linked devices and 1 phone at the same time.
                            </p>
                            <button @click="showMobileSidebar = true"
                                class="lg:hidden mt-6 px-6 py-2 bg-green-500 text-white rounded-full hover:bg-green-600 transition-colors">
                                Start Chat
                            </button>
                        </div>
                    </div>

                    <!-- Chat Interface -->
                    <template x-if="selectedUser">
                        <div class="flex-1 flex flex-col">
                            <!-- Chat Header -->
                            <div class="flex items-center justify-between p-4 bg-gray-50 border-b border-gray-200">
                                <div class="flex items-center">
                                    <button @click="showMobileSidebar = true"
                                        class="lg:hidden mr-3 p-2 text-gray-500 hover:bg-gray-200 rounded-full">
                                        <i class="fas fa-arrow-left"></i>
                                    </button>
                                    <div
                                        class="w-10 h-10 bg-green-500 rounded-full flex items-center justify-center text-white font-bold">
                                        <span x-text="selectedUser.name.charAt(0).toUpperCase()"></span>
                                    </div>
                                    <div class="ml-3">
                                        <h3 class="text-lg font-semibold text-gray-900" x-text="selectedUser.name"></h3>
                                        <p class="text-sm text-gray-500"
                                            x-text="selectedUser.online ? 'online' : 'offline'"></p>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <button class="p-2 text-gray-500 hover:bg-gray-200 rounded-full">
                                        <i class="fas fa-search"></i>
                                    </button>
                                    <button class="p-2 text-gray-500 hover:bg-gray-200 rounded-full">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Messages Area -->
                            <div class="flex-1 overflow-y-auto p-4 space-y-2 bg-gray-50"
                                style="background-image: url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAwIiBoZWlnaHQ9IjIwMCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48ZGVmcz48cGF0dGVybiBpZD0iYSIgcGF0dGVyblVuaXRzPSJ1c2VyU3BhY2VPblVzZSIgd2lkdGg9IjIwIiBoZWlnaHQ9IjIwIiBwYXR0ZXJuVHJhbnNmb3JtPSJyb3RhdGUoNDUpIj48cGF0aCBkPSJNMTAgMGwxMCAyME0wIDEwbDIwIDEwIiBzdHJva2U9IiNmNmY2ZjYiIHN0cm9rZS13aWR0aD0iMC41Ii8+PC9wYXR0ZXJuPjwvZGVmcz48cmVjdCB3aWR0aD0iMTAwJSIgaGVpZ2h0PSIxMDAlIiBmaWxsPSJ1cmwoI2EpIi8+PC9zdmc+');"
                                x-ref="messagesContainer">

                                <template x-for="message in messages" :key="message.id">
                                    <div class="flex"
                                        :class="message.sender_id === currentUserId ? 'justify-end' : 'justify-start'">
                                        <div class="max-w-xs lg:max-w-md px-4 py-2 rounded-lg shadow-sm"
                                            :class="message.sender_id === currentUserId ?
                                                'bg-green-500 text-white' :
                                                'bg-white text-gray-800 border border-gray-200'">
                                            <p class="text-sm" x-text="message.message"></p>
                                            <div class="flex items-center justify-end mt-1 space-x-1">
                                                <span class="text-xs opacity-75"
                                                    x-text="formatMessageTime(message.created_at)"></span>
                                                <template x-if="message.sender_id === currentUserId">
                                                    <template x-if="message.temp">
                                                        <i class="fas fa-clock text-xs opacity-50"></i>
                                                    </template>
                                                    <template x-if="!message.temp">
                                                        <i class="fas fa-check-double text-xs opacity-75"
                                                            :class="message.read_at ? 'text-blue-400' : ''"></i>
                                                    </template>
                                                </template>
                                            </div>
                                        </div>
                                    </div>
                                </template>

                                <!-- Typing indicator -->
                                <div x-show="isTyping" class="flex justify-start">
                                    <div class="bg-white rounded-lg px-4 py-2 shadow-sm border border-gray-200">
                                        <div class="flex space-x-1">
                                            <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce"></div>
                                            <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce"
                                                style="animation-delay: 0.1s"></div>
                                            <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce"
                                                style="animation-delay: 0.2s"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Message Input -->
                            <div class="p-4 bg-white border-t border-gray-200">
                                <div class="flex items-end space-x-3">
                                    <button class="p-2 text-gray-500 hover:text-gray-700">
                                        <i class="fas fa-smile text-xl"></i>
                                    </button>
                                    <button class="p-2 text-gray-500 hover:text-gray-700">
                                        <i class="fas fa-paperclip text-xl"></i>
                                    </button>
                                    <div class="flex-1 max-h-20 overflow-y-auto">
                                        <textarea x-model="newMessage" @keydown.enter.prevent="!$event.shiftKey && sendMessage()"
                                            @keydown.enter.shift="newMessage += '\n'" @input="handleTyping()" placeholder="Type a message" rows="1"
                                            x-ref="messageInput"
                                            class="w-full px-4 py-2 border border-gray-300 rounded-full focus:ring-2 focus:ring-green-500 focus:border-green-500 resize-none"></textarea>
                                    </div>
                                    <button @click="sendMessage()" :disabled="!newMessage.trim()"
                                        class="p-2 bg-green-500 text-white rounded-full hover:bg-green-600 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
                                        <i class="fas fa-paper-plane"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            <script>
                document.addEventListener('alpine:init', () => {
                    Alpine.data('whatsappChat', () => ({
                        // State
                        showMobileSidebar: window.innerWidth >= 1024,
                        selectedUser: null,
                        selectedUserId: null,
                        currentUserId: {{ auth()->id() }},
                        users: [],
                        filteredUsers: [],
                        messages: [],
                        newMessage: '',
                        searchQuery: '',
                        isTyping: false,
                        typingTimer: null,
                        pollInterval: null,
                        lastMessageId: 0,

                        // Initialize
                        init() {
                            console.log('Chat initialized');
                            this.loadUsers();
                            this.startPolling();
                            this.setupEventListeners();
                            this.handleResize();
                        },

                        // Setup event listeners
                        setupEventListeners() {
                            window.addEventListener('resize', () => {
                                this.handleResize();
                            });
                        },

                        // Handle window resize
                        handleResize() {
                            if (window.innerWidth >= 1024) {
                                this.showMobileSidebar = true;
                            } else {
                                this.showMobileSidebar = false;
                            }
                        },

                        // Load users
                        async loadUsers() {
                            try {
                                console.log('Loading users...');
                                const response = await fetch('/api/chat/users', {
                                    headers: {
                                        'X-CSRF-TOKEN': document.querySelector(
                                            'meta[name="csrf-token"]').getAttribute('content'),
                                        'Accept': 'application/json',
                                    }
                                });

                                if (response.ok) {
                                    const data = await response.json();
                                    this.users = data.users || [];
                                    this.filteredUsers = this.users;
                                    console.log('Users loaded:', this.users);
                                } else {
                                    console.error('Failed to load users:', response.status);
                                    this.loadDemoUsers();
                                }
                            } catch (error) {
                                console.error('Error loading users:', error);
                                this.loadDemoUsers();
                            }
                        },

                        // Load demo users for testing
                        loadDemoUsers() {
                            this.users = [{
                                    id: 2,
                                    name: 'John Doe',
                                    email: 'john@example.com',
                                    online: true,
                                    last_message: 'Hello there!',
                                    last_message_time: new Date().toISOString(),
                                    unread_count: 2
                                },
                                {
                                    id: 3,
                                    name: 'Jane Smith',
                                    email: 'jane@example.com',
                                    online: false,
                                    last_message: 'How are you?',
                                    last_message_time: new Date(Date.now() - 3600000).toISOString(),
                                    unread_count: 0
                                }
                            ];
                            this.filteredUsers = this.users;
                            console.log('Demo users loaded');
                        },

                        // Filter users based on search
                        filterUsers() {
                            if (!this.searchQuery.trim()) {
                                this.filteredUsers = this.users;
                                return;
                            }

                            const query = this.searchQuery.toLowerCase();
                            this.filteredUsers = this.users.filter(user =>
                                user.name.toLowerCase().includes(query) ||
                                user.email.toLowerCase().includes(query)
                            );
                        },

                        // Select user to chat with
                        async selectUser(user) {
                            console.log('Selecting user:', user.name);
                            this.selectedUser = user;
                            this.selectedUserId = user.id;
                            this.messages = [];

                            // Mark messages as read
                            user.unread_count = 0;

                            // Load messages for this user
                            await this.loadMessages();

                            // Hide mobile sidebar on mobile devices
                            if (window.innerWidth < 1024) {
                                this.showMobileSidebar = false;
                            }

                            // Focus message input
                            this.$nextTick(() => {
                                const messageInput = document.querySelector(
                                    'textarea[x-model="newMessage"]');
                                if (messageInput) {
                                    messageInput.focus();
                                }
                            });
                        },

                        // Load messages for selected user
                        async loadMessages() {
                            if (!this.selectedUserId) return;

                            try {
                                const response = await fetch(`/api/chat/messages/${this.selectedUserId}`, {
                                    headers: {
                                        'X-CSRF-TOKEN': document.querySelector(
                                            'meta[name="csrf-token"]').getAttribute('content'),
                                        'Accept': 'application/json',
                                    }
                                });

                                if (response.ok) {
                                    const data = await response.json();
                                    this.messages = data.messages || [];
                                    this.scrollToBottom();

                                    if (this.messages.length > 0) {
                                        this.lastMessageId = Math.max(...this.messages.map(m => m.id));
                                    }
                                } else {
                                    console.error('Failed to load messages');
                                    this.loadDemoMessages();
                                }
                            } catch (error) {
                                console.error('Error loading messages:', error);
                                this.loadDemoMessages();
                            }
                        },

                        // Load demo messages
                        loadDemoMessages() {
                            this.messages = [{
                                    id: 1,
                                    message: 'Hello! How are you today?',
                                    sender_id: this.selectedUserId,
                                    receiver_id: this.currentUserId,
                                    created_at: new Date(Date.now() - 3600000).toISOString(),
                                    read_at: null
                                },
                                {
                                    id: 2,
                                    message: 'I\'m doing great, thanks for asking!',
                                    sender_id: this.currentUserId,
                                    receiver_id: this.selectedUserId,
                                    created_at: new Date(Date.now() - 3000000).toISOString(),
                                    read_at: new Date(Date.now() - 2900000).toISOString()
                                }
                            ];
                            this.scrollToBottom();
                        },

                        // Send message
                        async sendMessage() {
                            if (!this.newMessage.trim() || !this.selectedUserId) return;

                            const messageText = this.newMessage.trim();
                            this.newMessage = '';

                            // Optimistically add message to UI
                            const tempMessage = {
                                id: Date.now(),
                                message: messageText,
                                sender_id: this.currentUserId,
                                receiver_id: this.selectedUserId,
                                created_at: new Date().toISOString(),
                                read_at: null,
                                temp: true
                            };

                            this.messages.push(tempMessage);
                            this.scrollToBottom();

                            try {
                                const response = await fetch('/api/chat/send', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': document.querySelector(
                                            'meta[name="csrf-token"]').getAttribute('content'),
                                        'Accept': 'application/json',
                                    },
                                    body: JSON.stringify({
                                        receiver_id: this.selectedUserId,
                                        message: messageText
                                    })
                                });

                                if (response.ok) {
                                    const result = await response.json();
                                    if (result.success) {
                                        // Replace temp message with real one
                                        const tempIndex = this.messages.findIndex(m => m.temp && m.id ===
                                            tempMessage.id);
                                        if (tempIndex !== -1) {
                                            this.messages[tempIndex] = result.message;
                                        }

                                        // Update last message in users list
                                        const userIndex = this.users.findIndex(u => u.id === this
                                            .selectedUserId);
                                        if (userIndex !== -1) {
                                            this.users[userIndex].last_message = messageText;
                                            this.users[userIndex].last_message_time = result.message
                                                .created_at;
                                        }
                                    }
                                } else {
                                    // Remove temp message on error
                                    this.messages = this.messages.filter(m => m.id !== tempMessage.id);
                                    console.error('Failed to send message');
                                    this.showToast('Failed to send message. Please try again.', 'error');
                                }
                            } catch (error) {
                                // Remove temp message on error
                                this.messages = this.messages.filter(m => m.id !== tempMessage.id);
                                console.error('Error sending message:', error);
                                this.showToast('Network error. Please check your connection.', 'error');
                            }
                        },

                        // Handle typing indicator
                        handleTyping() {
                            // Auto-resize textarea
                            const textarea = document.querySelector('textarea[x-model="newMessage"]');
                            if (textarea) {
                                textarea.style.height = 'auto';
                                textarea.style.height = Math.min(textarea.scrollHeight, 80) + 'px';
                            }

                            // Send typing indicator (implementation depends on your backend)
                            clearTimeout(this.typingTimer);
                            this.typingTimer = setTimeout(() => {
                                // Stop typing indicator
                            }, 1000);
                        },

                        // Start polling for new messages
                        startPolling() {
                            this.pollInterval = setInterval(async () => {
                                if (this.selectedUserId) {
                                    await this.checkNewMessages();
                                }
                                await this.updateUsersList();
                            }, 3000); // Poll every 3 seconds
                        },

                        // Check for new messages
                        async checkNewMessages() {
                            if (!this.selectedUserId) return;

                            try {
                                const response = await fetch(
                                    `/api/chat/messages/${this.selectedUserId}?after=${this.lastMessageId}`, {
                                        headers: {
                                            'X-CSRF-TOKEN': document.querySelector(
                                                'meta[name="csrf-token"]').getAttribute('content'),
                                            'Accept': 'application/json',
                                        }
                                    });

                                if (response.ok) {
                                    const data = await response.json();
                                    const newMessages = data.messages || [];

                                    if (newMessages.length > 0) {
                                        this.messages = [...this.messages, ...newMessages];
                                        this.lastMessageId = Math.max(...newMessages.map(m => m.id));
                                        this.scrollToBottom();
                                        this.playNotificationSound();
                                    }
                                }
                            } catch (error) {
                                console.error('Error checking new messages:', error);
                            }
                        },

                        // Update users list
                        async updateUsersList() {
                            try {
                                const response = await fetch('/api/chat/users', {
                                    headers: {
                                        'X-CSRF-TOKEN': document.querySelector(
                                            'meta[name="csrf-token"]').getAttribute('content'),
                                        'Accept': 'application/json',
                                    }
                                });

                                if (response.ok) {
                                    const data = await response.json();
                                    this.users = data.users || [];
                                    this.filteredUsers = this.users;
                                }
                            } catch (error) {
                                console.error('Error updating users list:', error);
                            }
                        },

                        // Scroll to bottom of messages
                        scrollToBottom() {
                            this.$nextTick(() => {
                                const container = document.getElementById('messages-container');
                                if (container) {
                                    container.scrollTop = container.scrollHeight;
                                }
                            });
                        },

                        // Format time for display
                        formatTime(dateString) {
                            if (!dateString) return '';
                            const date = new Date(dateString);
                            const now = new Date();
                            const diff = now - date;

                            if (diff < 24 * 60 * 60 * 1000) {
                                return date.toLocaleTimeString([], {
                                    hour: '2-digit',
                                    minute: '2-digit'
                                });
                            } else if (diff < 7 * 24 * 60 * 60 * 1000) {
                                return date.toLocaleDateString([], {
                                    weekday: 'short'
                                });
                            } else {
                                return date.toLocaleDateString([], {
                                    month: 'short',
                                    day: 'numeric'
                                });
                            }
                        },

                        // Format message time
                        formatMessageTime(dateString) {
                            const date = new Date(dateString);
                            return date.toLocaleTimeString([], {
                                hour: '2-digit',
                                minute: '2-digit'
                            });
                        },

                        // Play notification sound
                        playNotificationSound() {
                            try {
                                // Create a simple beep sound using Web Audio API
                                const audioContext = new(window.AudioContext || window.webkitAudioContext)();
                                const oscillator = audioContext.createOscillator();
                                const gainNode = audioContext.createGain();

                                oscillator.connect(gainNode);
                                gainNode.connect(audioContext.destination);

                                oscillator.frequency.value = 800;
                                oscillator.type = 'sine';

                                gainNode.gain.setValueAtTime(0.3, audioContext.currentTime);
                                gainNode.gain.exponentialRampToValueAtTime(0.01, audioContext.currentTime +
                                0.1);

                                oscillator.start(audioContext.currentTime);
                                oscillator.stop(audioContext.currentTime + 0.1);
                            } catch (error) {
                                console.log('Could not play notification sound:', error);
                            }
                        },

                        // Show toast notification
                        showToast(message, type = 'info') {
                            // Create toast element
                            const toast = document.createElement('div');
                            toast.className = `fixed top-4 right-4 z-50 p-3 rounded-lg shadow-lg text-white text-sm transform translate-x-full transition-transform duration-300 ${
                        type === 'error' ? 'bg-red-500' : type === 'success' ? 'bg-green-500' : 'bg-blue-500'
                    }`;
                            toast.textContent = message;

                            document.body.appendChild(toast);

                            // Animate in
                            setTimeout(() => {
                                toast.classList.remove('translate-x-full');
                            }, 100);

                            // Remove after 3 seconds
                            setTimeout(() => {
                                toast.classList.add('translate-x-full');
                                setTimeout(() => {
                                    if (toast.parentNode) {
                                        toast.parentNode.removeChild(toast);
                                    }
                                }, 300);
                            }, 3000);
                        },

                        // Cleanup
                        destroy() {
                            if (this.pollInterval) {
                                clearInterval(this.pollInterval);
                            }
                            if (this.typingTimer) {
                                clearTimeout(this.typingTimer);
                            }
                        }
                    }));
                });

                // Cleanup on page unload
                window.addEventListener('beforeunload', () => {
                    // Any cleanup needed
                });
            </script>
            /* Custom scrollbar */
            ::-webkit-scrollbar {
            width: 6px;
            }

            ::-webkit-scrollbar-track {
            background: #f1f1f1;
            }

            ::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 3px;
            }

            ::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
            }

            /* Animation for bounce */
            @keyframes bounce {
            0%, 80%, 100% {
            transform: scale(0);
            }
            40% {
            transform: scale(1);
            }
            }

            .animate-bounce {
            animation: bounce 1.4s infinite ease-in-out both;
            }
            </style>
</x-app-layout>
