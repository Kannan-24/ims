<x-app-layout>
    <x-slot name="title">
        {{ __('Chat') }} - {{ config('app.name', 'IMS') }}
    </x-slot>

    <div class="flex h-[calc(100vh-4rem)] min-h-0 bg-gradient-to-br from-slate-50 to-blue-50 overflow-hidden">
        <!-- Mobile Menu Overlay -->
        <div id="mobileOverlay"
            class="fixed inset-0 bg-black bg-opacity-50 z-30 lg:hidden hidden transition-opacity duration-300"></div>

        <!-- User List Sidebar -->
        <div id="userSidebar"
            class="fixed lg:relative w-full sm:w-96 lg:w-80 xl:w-96 h-full bg-white border-r border-gray-200 flex flex-col transform -translate-x-full lg:translate-x-0 transition-all duration-300 ease-in-out z-40 lg:z-auto shadow-2xl lg:shadow-none min-h-0 overflow-hidden">

            <!-- Sidebar Header (light theme) -->
            <div class="relative p-4 bg-gradient-to-r from-emerald-100 via-teal-100 to-cyan-100 text-gray-900">
                <div class="absolute inset-0 bg-white/40"></div>
                <div class="relative flex items-center justify-between">
                    <div class="flex items-center">
                        <div
                            class="w-12 h-12 bg-white/60 rounded-full flex items-center justify-center mr-3 backdrop-blur-sm border border-gray-200">
                            <i class="fas fa-user-friends text-xl"></i>
                        </div>
                        <div>
                            <h2 class="text-xl font-bold tracking-wide">Contacts</h2>
                            <p class="text-gray-600 text-sm font-medium">{{ Auth::user()->name }}</p>
                        </div>
                    </div>
                    <!-- Close button for mobile -->
                    <button id="closeSidebar"
                        class="lg:hidden p-2 text-gray-700 hover:bg-gray-100 rounded-lg transition-colors">
                        <i class="fas fa-times text-lg"></i>
                    </button>
                </div>
            </div>

            <!-- Search and Actions Bar -->
            <div class="p-4 bg-gray-50 border-b border-gray-200">
                <div class="relative mb-3">
                    <input type="text" id="userSearch" placeholder="Search contacts..."
                        class="w-full pl-12 pr-4 py-3 bg-white border border-gray-300 rounded-full focus:ring-2 focus:ring-emerald-300 focus:border-emerald-300 text-sm transition-all duration-200 shadow-sm hover:shadow-md">
                    <i class="fas fa-search absolute left-4 top-4 text-gray-400"></i>
                    <button id="clearSearch" class="absolute right-3 top-3 text-gray-400 hover:text-gray-600 hidden">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>

            <!-- Contacts List -->
            <div class="flex-1 overflow-y-auto scrollbar-thin scrollbar-thumb-slate-300 min-h-0" id="usersList">
                <div id="usersContainer" class="divide-y divide-gray-100">
                    <!-- Loading state -->
                    <div id="loadingState" class="p-8 text-center">
                        <div
                            class="animate-spin w-8 h-8 border-4 border-emerald-300 border-t-transparent rounded-full mx-auto mb-4">
                        </div>
                        <p class="text-gray-500 text-sm">Loading contacts...</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Chat Area -->
        <div class="flex-1 flex flex-col min-w-0 min-h-0 bg-white" id="chatWindow">
            <!-- Mobile Header -->
            <div class="lg:hidden flex items-center justify-between p-4 bg-white border-b border-gray-200 shadow-sm">
                <button id="openSidebar" class="p-2 text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
                    <i class="fas fa-bars text-lg"></i>
                </button>
                <h1 class="text-lg font-semibold text-gray-800">Messages</h1>
                <button class="p-2 text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
                    <i class="fas fa-search"></i>
                </button>
            </div>

            <!-- Welcome Screen -->
            <div class="flex-1 flex items-center justify-center bg-gradient-to-br from-gray-50 to-blue-50"
                id="welcomeScreen">
                <div class="text-center max-w-md px-6">
                    <div class="relative mb-8">
                        <div
                            class="w-24 h-24 sm:w-32 sm:h-32 bg-gradient-to-br from-emerald-200 via-teal-200 to-cyan-200 rounded-full flex items-center justify-center mx-auto shadow-2xl">
                            <i class="fas fa-comments text-3xl sm:text-4xl text-emerald-800"></i>
                        </div>
                        <div
                            class="absolute -top-2 -right-2 w-8 h-8 bg-pink-200 rounded-full flex items-center justify-center">
                            <i class="fas fa-heart text-emerald-800 text-sm"></i>
                        </div>
                    </div>
                    <h3 class="text-2xl sm:text-3xl font-bold text-gray-800 mb-4">Welcome to Chat</h3>
                    <p class="text-gray-600 text-base sm:text-lg mb-6 leading-relaxed">
                        Select a contact from the sidebar to start your conversation.
                        <span class="block mt-2 text-sm text-gray-500">Stay connected with your team!</span>
                    </p>
                    <button id="mobileOpenSidebar"
                        class="lg:hidden inline-flex items-center px-6 py-3 bg-gradient-to-r from-emerald-300 to-teal-300 text-emerald-900 rounded-full hover:from-emerald-400 hover:to-teal-400 transition-all duration-200 transform hover:scale-105 shadow-lg">
                        <i class="fas fa-users mr-2"></i>Browse Contacts
                    </button>
                </div>
            </div>

            <!-- Chat Interface -->
            <div class="flex-1 flex flex-col bg-white min-h-0" id="chatInterface" style="display: none;">
                <!-- Chat Header -->
                <div class="px-4 lg:px-6 py-3 border-b border-gray-200 bg-white shadow-sm">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center min-w-0 flex-1">
                            <!-- Back button for mobile -->
                            <button id="backToSidebar"
                                class="lg:hidden mr-3 p-2 text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
                                <i class="fas fa-arrow-left"></i>
                            </button>

                            <!-- User Avatar and Info -->
                            <div class="relative flex-shrink-0 mr-4">
                                <div class="w-12 h-12 rounded-full bg-gradient-to-br from-emerald-300 to-teal-300 flex items-center justify-center shadow-lg border-2 border-white"
                                    id="chatUserAvatar">
                                    <i class="fas fa-user text-white"></i>
                                </div>
                                <div class="absolute -bottom-1 -right-1 w-4 h-4 bg-green-400 border-2 border-white rounded-full shadow-sm"
                                    id="onlineStatus"></div>
                            </div>

                            <div class="min-w-0 flex-1">
                                <h3 class="font-bold text-gray-900 text-base truncate" id="chatUserName">User Name</h3>
                                <p class="text-sm text-gray-500 flex items-center" id="chatUserStatus">
                                    <span class="w-2 h-2 bg-green-400 rounded-full mr-2"></span>
                                    Online now
                                </p>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex items-center space-x-1">
                            <button id="mobileProfileBtn"
                                class="sm:hidden p-2 text-gray-500 hover:text-emerald-600 rounded-lg hover:bg-emerald-50 transition-colors">
                                <i class="fas fa-user"></i>
                            </button>
                            <button id="viewProfileBtn"
                                class="p-2 text-gray-500 hover:text-gray-700 rounded-lg hover:bg-gray-100 transition-colors">
                                <i class="fas fa-ellipsis-v"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Messages Area -->
                <div class="flex-1 overflow-y-auto p-4 space-y-4 bg-gradient-to-b from-gray-50 to-white scrollbar-thin scrollbar-thumb-slate-300 min-h-0 chat-messages-container"
                    id="chatMessages">
                    <!-- Messages will be loaded here -->
                </div>

                <!-- Typing Indicator -->
                <div id="typingIndicator" class="px-6 py-2 text-sm text-gray-500 bg-gray-50 border-t border-gray-100"
                    style="display: none;">
                    <div class="flex items-center">
                        <div class="typing-dots mr-2">
                            <span></span>
                            <span></span>
                            <span></span>
                        </div>
                        <span id="typingUser" class="font-medium">Someone is typing...</span>
                    </div>
                </div>

                <!-- Message Input -->
                <div class="px-4 lg:px-6 py-4 bg-white border-t border-gray-200">
                    <form id="messageForm" class="flex items-end space-x-3">
                        <div class="flex-1 relative">
                            <!-- File Preview -->
                            <div class="flex items-center space-x-2 mb-3" id="filePreview" style="display: none;">
                                <div
                                    class="flex items-center bg-gradient-to-r from-emerald-50 to-teal-50 border border-emerald-200 px-4 py-3 rounded-xl shadow-sm">
                                    <div
                                        class="w-10 h-10 bg-emerald-100 rounded-xl flex items-center justify-center mr-3">
                                        <i class="fas fa-file text-emerald-600"></i>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <span class="text-sm text-emerald-800 font-semibold truncate block"
                                            id="fileName"></span>
                                        <span class="text-xs text-emerald-600" id="fileSize"></span>
                                    </div>
                                    <button type="button" id="removeFile"
                                        class="ml-3 p-2 text-red-500 hover:text-red-700 hover:bg-red-50 rounded-full transition-colors">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Message Input Container -->
                            <div
                                class="flex items-end border border-gray-300 rounded-3xl bg-white shadow-sm focus-within:ring-2 focus-within:ring-emerald-300 focus-within:border-emerald-300 transition-all duration-200 overflow-hidden">
                                <button type="button" id="emojiBtn"
                                    class="p-3 text-gray-400 hover:text-yellow-500 transition-colors hover:bg-yellow-50">
                                    <i class="fas fa-smile text-xl"></i>
                                </button>

                                <textarea id="messageText" placeholder="Type a message..." rows="1"
                                    class="flex-1 px-4 py-3 bg-transparent border-0 resize-none focus:ring-0 focus:outline-none max-h-32 text-base placeholder-gray-400"
                                    style="min-height: 44px;"></textarea>

                                <label for="attachmentInput"
                                    class="p-3 text-gray-400 hover:text-emerald-600 cursor-pointer transition-colors hover:bg-emerald-50">
                                    <i class="fas fa-paperclip text-xl"></i>
                                </label>
                                <input type="file" id="attachmentInput" class="hidden"
                                    accept="image/*,.pdf,.doc,.docx,.txt,.xlsx,.xls,.ppt,.pptx,.zip,.rar">
                            </div>
                        </div>

                        <!-- Send Button -->
                        <button type="submit"
                            class="p-3 bg-gradient-to-r from-emerald-300 to-teal-300 text-emerald-900 rounded-full hover:from-emerald-400 hover:to-teal-400 focus:ring-2 focus:ring-emerald-300 transition-all shadow-lg hover:shadow-xl transform hover:scale-105 disabled:opacity-50 disabled:cursor-not-allowed">
                            <i class="fas fa-paper-plane text-xl"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Enhanced Profile Panel (closed by default on all screen sizes; opens only when button triggers) -->
        <div class="fixed lg:relative w-full lg:w-80 xl:w-96 h-full bg-white border-l border-gray-200 transform translate-x-full transition-transform duration-300 ease-in-out z-50 min-h-0 shadow-2xl lg:shadow-none"
            id="profilePanel" aria-hidden="true">
            <div class="flex flex-col h-full">
                <!-- Profile Header (light) -->
                <div class="px-6 py-4 bg-gradient-to-r from-emerald-100 to-teal-100 text-gray-900">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-bold">Contact Info</h3>
                        <button id="closeProfileBtn"
                            class="p-2 text-gray-700 hover:bg-gray-100 rounded-lg transition-colors">
                            <i class="fas fa-times text-lg"></i>
                        </button>
                    </div>
                </div>

                <!-- Profile Content -->
                <div class="flex-1 overflow-y-auto scrollbar-thin scrollbar-thumb-slate-300 min-h-0"
                    id="profileContent">
                    <!-- Profile details will be loaded here -->
                </div>
            </div>
        </div>
    </div>

    <!-- Enhanced Emoji Picker Modal -->
    <div id="emojiPicker" class="fixed inset-0 bg-black bg-opacity-50 items-center justify-center z-50 p-4"
        style="display: none;">
        <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full max-h-96 overflow-hidden">
            <div class="p-4 border-b border-gray-200 bg-gradient-to-r from-yellow-400 to-orange-400 text-white">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-bold flex items-center">
                        <i class="fas fa-smile mr-2"></i>Choose Emoji
                    </h3>
                    <button id="closeEmojiPicker" class="text-white hover:bg-white/20 rounded-lg p-1">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
            <div class="p-4 max-h-80 overflow-y-auto scrollbar-thin scrollbar-thumb-slate-300">
                <div class="grid grid-cols-8 gap-2" id="emojiGrid">
                    <!-- Emojis will be loaded here -->
                </div>
            </div>
        </div>
    </div>

    <!-- Enhanced Templates -->
    <template id="userItemTemplate">
        <div class="user-item flex items-center p-4 cursor-pointer hover:bg-emerald-50 transition-all duration-200 border-l-4 border-transparent hover:border-emerald-500 group"
            data-user-id="">
            <div class="relative flex-shrink-0 mr-4">
                <div
                    class="w-14 h-14 rounded-full bg-gradient-to-br from-emerald-200 to-teal-300 flex items-center justify-center shadow-lg user-avatar group-hover:scale-105 transition-transform duration-200">
                    <i class="fas fa-user text-white text-lg"></i>
                </div>
                <!-- Unread Badge -->
                <div class="absolute -top-1 -right-1 w-6 h-6 bg-red-500 text-white text-xs rounded-full flex items-center justify-center unread-badge shadow-lg transform scale-0 transition-transform duration-200"
                    style="display: none;">
                    <span class="unread-count font-bold">0</span>
                </div>
                <!-- Online Status -->
                <div
                    class="absolute -bottom-1 -right-1 w-5 h-5 bg-green-400 border-3 border-white rounded-full online-status shadow-sm">
                </div>
            </div>

            <div class="flex-1 min-w-0">
                <div class="flex items-center justify-between mb-1">
                    <h4
                        class="font-bold text-gray-900 truncate user-name text-base group-hover:text-emerald-700 transition-colors">
                    </h4>
                    <span class="text-xs text-gray-500 last-message-time flex-shrink-0 font-medium"></span>
                </div>
                <p class="text-sm text-gray-600 truncate last-message-text font-medium">No messages yet</p>
                <div class="flex items-center justify-between mt-2">
                    <span class="text-xs text-emerald-600 message-status">
                        <i class="fas fa-check-double"></i>
                    </span>
                    <div class="flex space-x-1">
                        <div class="w-1 h-1 bg-gray-300 rounded-full"></div>
                        <div class="w-1 h-1 bg-gray-300 rounded-full"></div>
                        <div class="w-1 h-1 bg-gray-300 rounded-full"></div>
                    </div>
                </div>
            </div>
        </div>
    </template>

    <template id="sentMessageTemplate">
        <div class="flex justify-end message-item animate-fadeIn group" data-message-id="">
            <div class="max-w-xs sm:max-w-sm lg:max-w-md">
                <div
                    class="bg-gradient-to-r from-emerald-300 via-teal-300 to-cyan-300 text-emerald-900 rounded-2xl rounded-br-md px-5 py-3 shadow-lg group-hover:shadow-xl transition-shadow duration-200">
                    <p class="message-text leading-relaxed text-sm break-words"></p>
                    <div class="message-attachment mt-3" style="display: none;">
                        <div class="bg-white/20 backdrop-blur-sm rounded-xl p-3 border border-white/30">
                            <a href="#"
                                class="text-emerald-900 hover:text-emerald-800 flex items-center attachment-link group">
                                <div
                                    class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center mr-3 group-hover:bg-white/30 transition-colors">
                                    <i class="fas fa-paperclip"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <span class="attachment-name text-sm font-semibold truncate block"></span>
                                    <span class="text-xs text-emerald-700">Click to download</span>
                                </div>
                                <i
                                    class="fas fa-download ml-2 text-xs opacity-70 group-hover:opacity-100 transition-opacity"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="text-xs text-gray-500 mt-2 text-right flex items-center justify-end message-time">
                    <span class="mr-2 font-medium"></span>
                    <i class="fas fa-check-double text-emerald-500"></i>
                </div>
            </div>
        </div>
    </template>

    <template id="receivedMessageTemplate">
        <div class="flex justify-start message-item animate-fadeIn group" data-message-id="">
            <div class="max-w-xs sm:max-w-sm lg:max-w-md">
                <div
                    class="bg-white border border-gray-200 text-gray-900 rounded-2xl rounded-bl-md px-5 py-3 shadow-sm hover:shadow-md transition-shadow duration-200">
                    <p class="message-text leading-relaxed text-sm break-words"></p>
                    <div class="message-attachment mt-3" style="display: none;">
                        <div class="bg-gradient-to-r from-gray-50 to-emerald-50 border border-gray-200 rounded-xl p-3">
                            <a href="#"
                                class="text-emerald-700 hover:text-emerald-900 flex items-center attachment-link group">
                                <div
                                    class="w-10 h-10 bg-emerald-100 rounded-xl flex items-center justify-center mr-3 group-hover:bg-emerald-200 transition-colors">
                                    <i class="fas fa-paperclip text-emerald-600"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <span class="attachment-name text-sm font-semibold truncate block"></span>
                                    <span class="text-xs text-emerald-600">Click to download</span>
                                </div>
                                <i
                                    class="fas fa-download ml-2 text-xs opacity-70 group-hover:opacity-100 transition-opacity text-emerald-600"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="text-xs text-gray-500 mt-2 message-time">
                    <span class="font-medium"></span>
                </div>
            </div>
        </div>
    </template>

    <template id="dateSeparatorTemplate">
        <div class="flex items-center justify-center my-6">
            <div
                class="bg-emerald-100 text-emerald-700 px-4 py-2 rounded-full text-xs font-bold shadow-sm date-text border border-emerald-200">
            </div>
        </div>
    </template>

    <template id="profileTemplate">
        <!-- Profile Header -->
        <div class="text-center p-6 bg-gradient-to-b from-emerald-50 to-white border-b border-gray-200">
            <div class="relative inline-block mb-4">
                <div
                    class="w-24 h-24 rounded-full bg-gradient-to-br from-emerald-300 to-teal-300 flex items-center justify-center mx-auto shadow-2xl profile-avatar">
                    <i class="fas fa-user text-3xl text-white"></i>
                </div>
                <div
                    class="absolute -bottom-2 -right-2 w-8 h-8 bg-green-400 border-4 border-white rounded-full shadow-lg">
                </div>
            </div>
            <h3 class="text-xl font-bold text-gray-900 profile-name mb-1"></h3>
            <p class="text-emerald-600 font-semibold profile-role mb-3"></p>
            <div class="flex justify-center space-x-4">
            </div>
        </div>

        <!-- Profile Details -->
        <div class="p-6 space-y-6">
            <div class="profile-field">
                <div class="flex items-center mb-2">
                    <i class="fas fa-envelope w-5 h-5 text-emerald-600 mr-3"></i>
                    <label class="block text-sm font-bold text-gray-700">Email Address</label>
                </div>
                <p class="text-gray-900 profile-email break-words bg-gray-50 p-3 rounded-lg"></p>
            </div>

            <div class="profile-field">
                <div class="flex items-center mb-2">
                    <i class="fas fa-phone w-5 h-5 text-emerald-600 mr-3"></i>
                    <label class="block text-sm font-bold text-gray-700">Phone Number</label>
                </div>
                <p class="text-gray-900 profile-phone bg-gray-50 p-3 rounded-lg"></p>
            </div>

            <div class="profile-field">
                <div class="flex items-center mb-2">
                    <i class="fas fa-id-badge w-5 h-5 text-emerald-600 mr-3"></i>
                    <label class="block text-sm font-bold text-gray-700">Employee ID</label>
                </div>
                <p class="text-gray-900 profile-employee-id bg-gray-50 p-3 rounded-lg"></p>
            </div>

            <div class="profile-field">
                <div class="flex items-center mb-2">
                    <i class="fas fa-calendar w-5 h-5 text-emerald-600 mr-3"></i>
                    <label class="block text-sm font-bold text-gray-700">Joined Date</label>
                </div>
                <p class="text-gray-900 profile-joined bg-gray-50 p-3 rounded-lg"></p>
            </div>
        </div>
    </template>

    @push('styles')
        <style>
            /* Enhanced Scrollbar Styling */
            .scrollbar-thin {
                scrollbar-width: thin;
                scrollbar-color: #cbd5e1 #f1f5f9;
            }

            .scrollbar-thin::-webkit-scrollbar {
                width: 6px;
                height: 6px;
            }

            .scrollbar-thumb-slate-300::-webkit-scrollbar-track {
                background: #f1f5f9;
                border-radius: 10px;
            }

            .scrollbar-thumb-slate-300::-webkit-scrollbar-thumb {
                background: linear-gradient(180deg, #cbd5e1, #94a3b8);
                border-radius: 10px;
                border: 1px solid #e2e8f0;
            }

            .scrollbar-thumb-slate-300::-webkit-scrollbar-thumb:hover {
                background: linear-gradient(180deg, #94a3b8, #64748b);
            }

            /* Enhanced Animations */
            @keyframes fadeIn {
                from {
                    opacity: 0;
                    transform: translateY(15px) scale(0.95);
                }

                to {
                    opacity: 1;
                    transform: translateY(0) scale(1);
                }
            }

            .animate-fadeIn {
                animation: fadeIn 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            }

            /* Enhanced Typing Indicator */
            .typing-dots {
                display: flex;
                align-items: center;
            }

            .typing-dots span {
                display: inline-block;
                width: 6px;
                height: 6px;
                border-radius: 50%;
                background: linear-gradient(45deg, #10b981, #06b6d4);
                margin: 0 2px;
                animation: typingDots 1.5s infinite ease-in-out;
            }

            .typing-dots span:nth-child(1) {
                animation-delay: -0.32s;
            }

            .typing-dots span:nth-child(2) {
                animation-delay: -0.16s;
            }

            .typing-dots span:nth-child(3) {
                animation-delay: 0s;
            }

            @keyframes typingDots {

                0%,
                80%,
                100% {
                    transform: scale(0.8);
                    opacity: 0.5;
                }

                40% {
                    transform: scale(1.2);
                    opacity: 1;
                }
            }

            /* Message Container Styling */
            .chat-messages-container {
                background-image:
                    radial-gradient(circle at 20% 50%, rgba(120, 119, 198, 0.3) 0%, transparent 50%),
                    radial-gradient(circle at 80% 20%, rgba(255, 119, 198, 0.3) 0%, transparent 50%),
                    radial-gradient(circle at 40% 80%, rgba(120, 219, 255, 0.3) 0%, transparent 50%);
                background-size: 500px 500px, 400px 400px, 300px 300px;
                background-position: 0% 50%, 100% 20%, 40% 80%;
            }

            /* Enhanced User Item Hover Effects */
            .user-item:hover .user-avatar {
                box-shadow: 0 8px 25px rgba(16, 185, 129, 0.3);
            }

            .user-item:hover .unread-badge {
                transform: scale(1.1);
            }

            /* Message Bubble Enhancements */
            .message-item:hover .bg-gradient-to-r {
                transform: scale(1.02);
                transition: transform 0.2s ease;
            }

            /* Mobile Responsive Improvements */
            @media (max-width: 1024px) {
                #userSidebar {
                    width: 100%;
                    max-width: 400px;
                }

                #profilePanel {
                    width: 100%;
                }

                .chat-messages-container {
                    background-size: 300px 300px, 250px 250px, 200px 200px;
                }
            }

            @media (max-width: 640px) {
                .chat-messages-container {
                    background-size: 200px 200px, 150px 150px, 100px 100px;
                }
            }

            /* Custom Focus States for Accessibility */
            .focus-visible:focus {
                outline: 3px solid #10b981;
                outline-offset: 2px;
                border-radius: 8px;
            }

            /* Loading Animation */
            @keyframes spin {
                to {
                    transform: rotate(360deg);
                }
            }

            .animate-spin {
                animation: spin 1s linear infinite;
            }

            /* Enhanced Gradient Backgrounds */
            .bg-gradient-emerald {
                background: linear-gradient(135deg, #059669 0%, #0d9488 50%, #0891b2 100%);
            }

            /* Smooth Transitions */
            * {
                transition-property: color, background-color, border-color, text-decoration-color, fill, stroke, opacity, box-shadow, transform, filter, backdrop-filter;
                transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
                transition-duration: 200ms;
            }

            /* Backdrop Blur Support */
            .backdrop-blur-sm {
                backdrop-filter: blur(4px);
            }

            /* Enhanced Border Styles */
            .border-3 {
                border-width: 3px;
            }
        </style>
    @endpush

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
                this.searchTimeout = null;
                this.emojis = [
                    '😀', '😃', '😄', '😁', '😆', '😅', '😂', '🤣', '😊', '😇', '🙂', '🙃', '😉', '😌', '😍',
                    '🥰', '😘', '😗', '😙', '😚', '😋', '😛', '😝', '😜', '🤪', '🤨', '🧐', '🤓', '😎', '🤩',
                    '🥳',
                    '😏', '😒', '😞', '😔', '😟', '😕', '🙁', '☹️', '😣', '😖', '😫', '😩', '🥺', '😢', '😭',
                    '😤',
                    '😠', '😡', '🤬', '🤯', '😳', '🥵', '🥶', '😱', '😨', '😰', '😥', '😓', '🤗', '🤔', '🤭',
                    '🤫',
                    '🤥', '😶', '😐', '😑', '😬', '🙄', '😯', '😦', '😧', '😮', '😲', '🥱', '😴', '🤤', '😪',
                    '😵',
                    '🤐', '🥴', '🤢', '🤮', '🤧', '😷', '🤒', '🤕', '🤑', '🤠', '😈', '👿', '👹', '👺', '🤡',
                    '💩',
                    '👻', '💀', '☠️', '👽', '👾', '🤖', '🎃', '😺', '😸', '😹', '😻', '😼', '😽', '🙀', '😿',
                    '😾',
                    '❤️', '🧡', '💛', '💚', '💙', '💜', '🖤', '🤍', '🤎', '💔', '❣️', '💕', '💞', '💓', '💗',
                    '💖',
                    '💘', '💝', '💟', '👍', '👎', '👌', '🤏', '✌️', '🤞', '🤟', '🤘', '🤙', '👈', '👉', '👆',
                    '🖕',
                    '👇', '☝️', '👋', '🤚', '🖐️', '✋', '🖖', '👏', '🙌', '🤝', '🙏', '✨', '🌟', '💫', '⭐',
                    '🌠',
                    '🎉', '🎊', '🎈', '🎁', '🏆', '🥇', '🥈', '🥉', '🎯', '🎪', '🎭', '🎨', '🎬', '🎤', '🎧',
                    '🎵',
                    '🎶', '🎸', '🥁', '🎹', '🎺', '🎷', '📱', '💻', '⌨️', '🖥️', '🖱️', '💾', '💿', '📀', '☕',
                    '🍕'
                ];
            }

            init() {
                this.setupEventListeners();
                this.loadUsers();
                this.setupEmojiPicker();
                this.startPolling();
                this.restoreSession();
                this.setupMobileNavigation();
                this.setupSearchFunctionality();
                this.setupNotificationSystem();
            }

            setupNotificationSystem() {
                // Request notification permission
                if ('Notification' in window && Notification.permission === 'default') {
                    Notification.requestPermission();
                }
            }

            showNotification(message, type = 'info') {
                // Create toast notification
                const toast = document.createElement('div');
                toast.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg transition-all duration-300 transform translate-x-full ${
                type === 'error' ? 'bg-red-500 text-white' :
                type === 'success' ? 'bg-green-500 text-white' :
                'bg-blue-500 text-white'
            }`;
                toast.innerHTML = `
                <div class="flex items-center">
                    <i class="fas fa-${type === 'error' ? 'exclamation-triangle' : type === 'success' ? 'check-circle' : 'info-circle'} mr-2"></i>
                    <span>${message}</span>
                    <button class="ml-4 text-white hover:text-gray-200" onclick="this.parentElement.parentElement.remove()">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            `;

                document.body.appendChild(toast);

                // Animate in
                setTimeout(() => {
                    toast.classList.remove('translate-x-full');
                }, 100);

                // Auto remove after 5 seconds
                setTimeout(() => {
                    toast.classList.add('translate-x-full');
                    setTimeout(() => {
                        if (toast.parentNode) {
                            toast.parentNode.removeChild(toast);
                        }
                    }, 300);
                }, 5000);
            }

            setupSearchFunctionality() {
                const searchInput = document.getElementById('userSearch');
                const clearButton = document.getElementById('clearSearch');

                if (!searchInput || !clearButton) return;

                searchInput.addEventListener('input', (e) => {
                    const query = e.target.value.trim();
                    if (query.length > 0) {
                        clearButton.classList.remove('hidden');
                    } else {
                        clearButton.classList.add('hidden');
                    }

                    clearTimeout(this.searchTimeout);
                    this.searchTimeout = setTimeout(() => {
                        this.filterUsers(query);
                    }, 300);
                });

                clearButton.addEventListener('click', () => {
                    searchInput.value = '';
                    clearButton.classList.add('hidden');
                    this.renderUsers(this.users);
                    searchInput.focus();
                });
            }

            setupMobileNavigation() {
                const openSidebar = document.getElementById('openSidebar');
                const mobileOpenSidebar = document.getElementById('mobileOpenSidebar');
                const closeSidebar = document.getElementById('closeSidebar');
                const backToSidebar = document.getElementById('backToSidebar');
                const mobileOverlay = document.getElementById('mobileOverlay');
                const mobileProfileBtn = document.getElementById('mobileProfileBtn');

                // Open sidebar
                [openSidebar, mobileOpenSidebar].forEach(btn => {
                    if (btn) {
                        btn.addEventListener('click', (e) => {
                            e.preventDefault();
                            this.openMobileSidebar();
                        });
                    }
                });

                // Close sidebar
                [closeSidebar, backToSidebar, mobileOverlay].forEach(btn => {
                    if (btn) {
                        btn.addEventListener('click', (e) => {
                            e.preventDefault();
                            this.closeMobileSidebar();
                        });
                    }
                });

                // Mobile profile button
                if (mobileProfileBtn) {
                    mobileProfileBtn.addEventListener('click', (e) => {
                        e.preventDefault();
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

                // Handle touch swipe gestures
                this.setupSwipeGestures();
            }

            setupSwipeGestures() {
                let startX = null;
                let startY = null;

                document.addEventListener('touchstart', (e) => {
                    startX = e.touches[0].clientX;
                    startY = e.touches[0].clientY;
                });

                document.addEventListener('touchmove', (e) => {
                    if (!startX || !startY) return;

                    const currentX = e.touches[0].clientX;
                    const currentY = e.touches[0].clientY;
                    const diffX = startX - currentX;
                    const diffY = startY - currentY;

                    if (Math.abs(diffX) > Math.abs(diffY)) {
                        if (diffX > 50 && window.innerWidth < 1024) {
                            // Swipe left - close sidebar if open
                            this.closeMobileSidebar();
                        } else if (diffX < -50 && window.innerWidth < 1024) {
                            // Swipe right - open sidebar if closed
                            this.openMobileSidebar();
                        }
                    }

                    startX = null;
                    startY = null;
                });
            }

            openMobileSidebar() {
                const userSidebar = document.getElementById('userSidebar');
                const mobileOverlay = document.getElementById('mobileOverlay');

                if (userSidebar) userSidebar.classList.remove('-translate-x-full');
                if (mobileOverlay) mobileOverlay.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            }

            closeMobileSidebar() {
                const userSidebar = document.getElementById('userSidebar');
                const mobileOverlay = document.getElementById('mobileOverlay');

                if (userSidebar) userSidebar.classList.add('-translate-x-full');
                if (mobileOverlay) mobileOverlay.classList.add('hidden');
                document.body.style.overflow = '';
            }

            restoreSession() {
                const savedSession = sessionStorage.getItem('chatSession');
                if (savedSession) {
                    try {
                        const session = JSON.parse(savedSession);
                        if (session.userId && Date.now() - session.timestamp < 24 * 60 * 60 * 1000) { // 24 hours
                            this.currentUserId = session.userId;
                            setTimeout(() => {
                                this.loadMessages();
                                this.highlightActiveUser();
                            }, 500);
                        } else {
                            sessionStorage.removeItem('chatSession');
                        }
                    } catch (error) {
                        console.error('Error restoring session:', error);
                        sessionStorage.removeItem('chatSession');
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
                const messageForm = document.getElementById('messageForm');
                if (messageForm) {
                    messageForm.addEventListener('submit', (e) => {
                        e.preventDefault();
                        this.sendMessage();
                    });
                }

                // Auto-resize textarea
                const textarea = document.getElementById('messageText');
                if (textarea) {
                    textarea.addEventListener('input', () => {
                        this.autoResize();
                        this.handleTyping();
                    });

                    // Enter key to send message
                    textarea.addEventListener('keydown', (e) => {
                        if (e.key === 'Enter' && !e.shiftKey) {
                            e.preventDefault();
                            this.sendMessage();
                        } else if (e.key === 'Escape') {
                            textarea.blur();
                        }
                    });
                }

                // File attachment
                const attachmentInput = document.getElementById('attachmentInput');
                if (attachmentInput) {
                    attachmentInput.addEventListener('change', (e) => {
                        this.handleFileSelection(e.target.files);
                    });
                }

                // Remove file preview
                const removeFileBtn = document.getElementById('removeFile');
                if (removeFileBtn) {
                    removeFileBtn.addEventListener('click', () => {
                        this.removeFilePreview();
                    });
                }

                // Emoji button
                const emojiBtn = document.getElementById('emojiBtn');
                if (emojiBtn) {
                    emojiBtn.addEventListener('click', (e) => {
                        e.preventDefault();
                        this.toggleEmojiPicker();
                    });
                }

                // Close emoji picker
                const closeEmojiPicker = document.getElementById('closeEmojiPicker');
                if (closeEmojiPicker) {
                    closeEmojiPicker.addEventListener('click', () => {
                        document.getElementById('emojiPicker').style.display = 'none';
                    });
                }

                // View profile buttons
                const viewProfileBtn = document.getElementById('viewProfileBtn');
                if (viewProfileBtn) {
                    viewProfileBtn.addEventListener('click', (e) => {
                        e.preventDefault();
                        this.toggleProfilePanel();
                    });
                }

                // Close profile panel
                const closeProfileBtn = document.getElementById('closeProfileBtn');
                if (closeProfileBtn) {
                    closeProfileBtn.addEventListener('click', (e) => {
                        e.preventDefault();
                        this.closeProfilePanel();
                    });
                }

                // Close modals when clicking outside
                document.addEventListener('click', (e) => {
                    const emojiPicker = document.getElementById('emojiPicker');
                    const emojiBtn = document.getElementById('emojiBtn');
                    if (emojiPicker && !emojiPicker.contains(e.target) && !emojiBtn?.contains(e.target)) {
                        emojiPicker.style.display = 'none';
                    }
                });

                // Keyboard shortcuts
                document.addEventListener('keydown', (e) => {
                    if (e.ctrlKey || e.metaKey) {
                        switch (e.key) {
                            case 'k':
                                e.preventDefault();
                                document.getElementById('userSearch')?.focus();
                                break;
                            case 'Enter':
                                if (this.currentUserId) {
                                    e.preventDefault();
                                    document.getElementById('messageText')?.focus();
                                }
                                break;
                        }
                    }
                });
            }

            setupEmojiPicker() {
                const emojiGrid = document.getElementById('emojiGrid');
                if (!emojiGrid) return;

                emojiGrid.innerHTML = '';
                this.emojis.forEach(emoji => {
                    const emojiDiv = document.createElement('div');
                    emojiDiv.className =
                        'emoji-item text-2xl p-3 hover:bg-yellow-100 rounded-lg cursor-pointer transition-all duration-200 hover:scale-110 active:scale-95';
                    emojiDiv.textContent = emoji;
                    emojiDiv.addEventListener('click', () => {
                        this.insertEmoji(emoji);
                    });
                    emojiGrid.appendChild(emojiDiv);
                });
            }

            insertEmoji(emoji) {
                const textarea = document.getElementById('messageText');
                if (!textarea) return;

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
                    const isVisible = picker.style.display !== 'none';
                    picker.style.display = isVisible ? 'none' : 'flex';
                }
            }

            toggleProfilePanel() {
                if (this.currentUserData) {
                    this.showProfilePanel(this.currentUserData);
                } else {
                    this.showNotification('Please select a contact first', 'error');
                }
            }

            showProfilePanel(user) {
                const panel = document.getElementById('profilePanel');
                const content = document.getElementById('profileContent');
                const template = document.getElementById('profileTemplate');

                if (!panel || !content || !template) return;

                const profileHtml = template.content.cloneNode(true);

                // Populate profile data
                this.populateProfileData(profileHtml, user);

                content.innerHTML = '';
                content.appendChild(profileHtml);

                panel.style.transform = 'translateX(0)';

                if (window.innerWidth < 1024) {
                    document.body.style.overflow = 'hidden';
                }
            }

            populateProfileData(profileHtml, user) {
                const elements = {
                    '.profile-avatar': user.profile_photo ?
                        `<img src="${user.profile_photo}" alt="${user.name}" />` : '',
                    '.profile-name': user.name || 'Unknown User',
                    '.profile-email': user.email || 'No email provided',
                    '.profile-phone': user.phone || 'No phone number',
                    '.profile-role': user.role || 'Team Member',
                    '.profile-employee-id': user.employee_id || 'Not available',
                    '.profile-joined': this.formatDate(user.created_at || new Date())
                };

                Object.entries(elements).forEach(([selector, value]) => {
                    const element = profileHtml.querySelector(selector);
                    if (element) {
                        element.textContent = value;
                    }
                });

                // Update avatar
                const avatar = profileHtml.querySelector('.profile-avatar');
                if (avatar && user.name) {
                    avatar.innerHTML =
                        `<span class="text-3xl font-bold">${user.name.charAt(0).toUpperCase()}</span>`;
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
                        this.showNotification('File size must be less than 10MB', 'error');
                        this.removeFilePreview();
                        return;
                    }

                    const allowedTypes = [
                        'image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp',
                        'application/pdf', 'application/msword',
                        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                        'text/plain', 'application/vnd.ms-excel',
                        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                        'application/vnd.ms-powerpoint',
                        'application/vnd.openxmlformats-officedocument.presentationml.presentation',
                        'application/zip', 'application/x-rar-compressed',
                        'application/x-zip-compressed', 'application/octet-stream'
                    ];

                    if (!allowedTypes.includes(file.type)) {
                        this.showNotification(
                            'File type not supported. Please select an image, document, or archive file.',
                            'error');
                        this.removeFilePreview();
                        return;
                    }

                    this.showFilePreview(file);
                    this.showNotification(`File "${file.name}" selected`, 'success');
                }
            }

            showFilePreview(file) {
                const preview = document.getElementById('filePreview');
                const fileName = document.getElementById('fileName');
                const fileSize = document.getElementById('fileSize');

                if (preview && fileName && fileSize) {
                    fileName.textContent = file.name;
                    fileSize.textContent = this.formatFileSize(file.size);
                    preview.style.display = 'flex';

                    // Add file type icon
                    const fileIcon = preview.querySelector('.fas.fa-file');
                    if (fileIcon) {
                        fileIcon.className = `fas ${this.getFileIcon(file.type)}`;
                    }
                }
            }

            getFileIcon(mimeType) {
                if (mimeType.startsWith('image/')) return 'fa-file-image';
                if (mimeType === 'application/pdf') return 'fa-file-pdf';
                if (mimeType.includes('word')) return 'fa-file-word';
                if (mimeType.includes('excel') || mimeType.includes('spreadsheet')) return 'fa-file-excel';
                if (mimeType.includes('powerpoint') || mimeType.includes('presentation'))
                    return 'fa-file-powerpoint';
                if (mimeType.includes('zip') || mimeType.includes('rar')) return 'fa-file-archive';
                if (mimeType === 'text/plain') return 'fa-file-text';
                return 'fa-file';
            }

            formatFileSize(bytes) {
                if (bytes === 0) return '0 Bytes';
                const k = 1024;
                const sizes = ['Bytes', 'KB', 'MB', 'GB'];
                const i = Math.floor(Math.log(bytes) / Math.log(k));
                return parseFloat((bytes / Math.pow(k, i)).toFixed(1)) + ' ' + sizes[i];
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
                    item.classList.remove('bg-emerald-100', 'border-emerald-500');
                    item.classList.add('border-transparent');
                });

                const selectedItem = document.querySelector(`[data-user-id="${this.currentUserId}"] `);
                if (selectedItem) {
                    selectedItem.classList.add('bg-emerald-100', 'border-emerald-500');
                    selectedItem.classList.remove('border-transparent');
                }
            }

            formatDate(dateString) {
                const date = new Date(dateString);
                return date.toLocaleDateString('en-US', {
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric'
                });
            }

            handleTyping() {
                if (!this.currentUserId) return;

                if (!this.isTyping) {
                    this.isTyping = true;
                    this.sendTypingIndicator(true);
                }

                clearTimeout(this.typingTimer);
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
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
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
                    userSpan.textContent = `
                $ {
                    userName
                }
                is typing...`;
                    indicator.style.display = 'block';

                    // Auto hide after 3 seconds
                    setTimeout(() => {
                        this.hideTypingIndicator();
                    }, 3000);
                }
            }

            hideTypingIndicator() {
                const indicator = document.getElementById('typingIndicator');
                if (indicator) {
                    indicator.style.display = 'none';
                }
            }

            filterUsers(query) {
                if (!query.trim()) {
                    this.renderUsers(this.users);
                    return;
                }

                const filteredUsers = this.users.filter(user =>
                    user.name.toLowerCase().includes(query.toLowerCase()) ||
                    user.email.toLowerCase().includes(query.toLowerCase()) ||
                    (user.role && user.role.toLowerCase().includes(query.toLowerCase()))
                );

                this.renderUsers(filteredUsers);
            }

            async loadUsers() {
                try {
                    const response = await fetch('/ims/chat/users', {
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
                        }
                    });

                    if (!response.ok) {
                        throw new Error(`
                HTTP error!status: $ {
                    response.status
                }
                `);
                    }

                    const users = await response.json();
                    this.users = Array.isArray(users) ? users : [];
                    this.renderUsers(this.users);
                    this.updateOnlineCount(this.users);
                    this.hideLoadingState();
                } catch (error) {
                    console.error('Error loading users:', error);
                    this.hideLoadingState();
                    this.showNotification('Failed to load contacts', 'error');
                }
            }

            hideLoadingState() {
                const loadingState = document.getElementById('loadingState');
                if (loadingState) {
                    loadingState.style.display = 'none';
                }
            }

            updateOnlineCount(users) {
                const onlineCount = document.getElementById('onlineCount');
                if (onlineCount) {
                    const count = users.length;
                    onlineCount.textContent = `
                $ {
                    count
                }
                contact$ {
                    count !== 1 ? 's' : ''
                }
                `;
                }
            }

            renderUsers(users) {
                const container = document.getElementById('usersContainer');
                const template = document.getElementById('userItemTemplate');

                if (!container || !template) return;

                // Clear existing content except loading state
                const loadingState = container.querySelector('#loadingState');
                container.innerHTML = '';
                if (loadingState) {
                    container.appendChild(loadingState);
                }

                if (users.length === 0) {
                    container.innerHTML = ` <
                div class="p-8 text-center" >
                    <
                    div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4" >
                    <
                    i class="fas fa-user-friends text-2xl text-gray-400" > < /i> <
                    /div> <
                    p class="text-gray-500 font-medium" > No contacts found < /p> <
                    p class="text-gray-400 text-sm mt-1" > Try adjusting your search < /p> <
                    /div>
                `;
                    return;
                }

                users.forEach((user, index) => {
                    const userElement = template.content.cloneNode(true);
                    const userDiv = userElement.querySelector('.user-item');

                    if (!userDiv) return;

                    userDiv.dataset.userId = user.id;

                    // Set user name and avatar
                    const nameElement = userDiv.querySelector('.user-name');
                    const avatarElement = userDiv.querySelector('.user-avatar');

                    if (nameElement) nameElement.textContent = user.name || 'Unknown User';
                    if (avatarElement) {
                        avatarElement.textContent = (user.name || 'U').charAt(0).toUpperCase();
                    }

                    // Handle unread count
                    const unreadBadge = userDiv.querySelector('.unread-badge');
                    const unreadCount = userDiv.querySelector('.unread-count');
                    if (user.unread_count > 0 && unreadBadge && unreadCount) {
                        unreadBadge.style.display = 'flex';
                        unreadBadge.classList.add('scale-100');
                        unreadCount.textContent = user.unread_count > 99 ? '99+' : user.unread_count;
                    }

                    // Handle last message
                    const lastMessageText = userDiv.querySelector('.last-message-text');
                    const lastMessageTime = userDiv.querySelector('.last-message-time');

                    if (lastMessageText) {
                        if (user.last_message) {
                            lastMessageText.textContent = this.truncateMessage(user.last_message, 35);
                            lastMessageText.classList.remove('text-gray-600');
                            lastMessageText.classList.add('text-gray-700');
                        } else {
                            lastMessageText.textContent = 'No messages yet';
                            lastMessageText.classList.add('text-gray-500');
                        }
                    }

                    if (lastMessageTime && user.last_message_time) {
                        lastMessageTime.textContent = this.formatTime(user.last_message_time);
                    }

                    // Add online status
                    const onlineStatus = userDiv.querySelector('.online-status');
                    if (onlineStatus) {
                        // Simulate online status - you can replace this with real data
                        const isOnline = Math.random() > 0.3; // 70% chance of being online
                        onlineStatus.style.backgroundColor = isOnline ? '#34d399' : '#9ca3af';
                    }

                    // Add click event with debounce
                    let clickTimeout;
                    userDiv.addEventListener('click', () => {
                        clearTimeout(clickTimeout);
                        clickTimeout = setTimeout(() => {
                            this.selectUser(user);
                        }, 150);
                    });

                    // Add animation delay for staggered loading
                    userDiv.style.animationDelay = `
                $ {
                    index * 50
                }
                ms`;

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
                const currentUserId = parseInt(document.querySelector('meta[name="user-id"]')?.content) || 1;

                if (!Array.isArray(messages) || messages.length === 0) {
                    container.innerHTML = ` <
                div class="flex-1 flex items-center justify-center" >
                    <
                    div class="text-center" >
                    <
                    div class="w-16 h-16 bg-emerald-100 rounded-full flex items-center justify-center mx-auto mb-4" >
                    <
                    i class="fas fa-comments text-2xl text-emerald-600" > < /i> <
                    /div> <
                    p class="text-gray-500 font-medium" > No messages yet < /p> <
                    p class="text-gray-400 text-sm mt-1" > Start a conversation! < /p> <
                    /div> <
                    /div>
                `;
                    return;
                }

                messages.forEach((message, index) => {
                    const messageDate = new Date(message.created_at).toDateString();

                    // Add date separator
                    if (messageDate !== lastDate && dateTemplate) {
                        const dateSeparator = dateTemplate.content.cloneNode(true);
                        const dateText = dateSeparator.querySelector('.date-text');
                        if (dateText) {
                            dateText.textContent = this.formatMessageDate(message.created_at);
                        }
                        container.appendChild(dateSeparator);
                        lastDate = messageDate;
                    }

                    const isSent = message.sender_id === currentUserId;
                    const template = isSent ? sentTemplate : receivedTemplate;
                    const messageElement = template.content.cloneNode(true);

                    // Set message data
                    const messageDiv = messageElement.querySelector('[data-message-id]');
                    const messageText = messageElement.querySelector('.message-text');
                    const messageTime = messageElement.querySelector('.message-time span');

                    if (messageDiv) messageDiv.setAttribute('data-message-id', message.id);
                    if (messageText) messageText.textContent = message.message || '';
                    if (messageTime) messageTime.textContent = this.formatTime(message.created_at);

                    // Handle attachments
                    if (message.attachment && message.attachment_name) {
                        const attachmentDiv = messageElement.querySelector('.message-attachment');
                        const attachmentLink = messageElement.querySelector('.attachment-link');
                        const attachmentName = messageElement.querySelector('.attachment-name');

                        if (attachmentDiv && attachmentLink && attachmentName) {
                            attachmentLink.href = ` / ims / chat / download / $ {
                    message.id
                }
                `;
                            attachmentLink.target = '_blank';
                            attachmentName.textContent = message.attachment_name;
                            attachmentDiv.style.display = 'block';

                            // Update file icon based on attachment type
                            const fileIcon = attachmentDiv.querySelector('.fas.fa-paperclip');
                            if (fileIcon && message.attachment_type) {
                                fileIcon.className = `
                fas $ {
                    this.getFileIcon(message.attachment_type)
                }
                `;
                            }
                        }
                    }

                    // Add animation delay
                    const messageItem = messageElement.querySelector('.message-item');
                    if (messageItem) {
                        messageItem.style.animationDelay = `
                $ {
                    index * 100
                }
                ms`;
                    }

                    container.appendChild(messageElement);
                });

                // Scroll to bottom after rendering
                setTimeout(() => {
                    this.scrollToBottom();
                }, 100);
            }

            scrollToBottom() {
                const container = document.getElementById('chatMessages');
                if (container) {
                    container.scrollTo({
                        top: container.scrollHeight,
                        behavior: 'smooth'
                    });
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
                        month: 'short',
                        day: 'numeric',
                        year: date.getFullYear() !== today.getFullYear() ? 'numeric' : undefined
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

            truncateMessage(message, length = 35) {
                if (!message) return '';
                return message.length > length ? message.substring(0, length) + '...' : message;
            }

            selectUser(user) {
                if (!user || !user.id) return;

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
                    messageText.focus();
                    this.autoResize();
                }

                // Mark messages as read
                this.markMessagesAsRead(user.id);
            }

            async markMessagesAsRead(userId) {
                try {
                    await fetch('/ims/chat/mark-read', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
                        },
                        body: JSON.stringify({
                            sender_id: userId
                        })
                    });

                    // Update UI to remove unread badge
                    const userItem = document.querySelector(`[data-user-id="${userId}"]`);
                    if (userItem) {
                        const unreadBadge = userItem.querySelector('.unread-badge');
                        if (unreadBadge) {
                            unreadBadge.style.display = 'none';
                        }
                    }
                } catch (error) {
                    console.error('Error marking messages as read:', error);
                }
            }

            updateChatHeader(user) {
                const chatInterface = document.getElementById('chatInterface');
                const welcomeScreen = document.getElementById('welcomeScreen');
                const chatUserName = document.getElementById('chatUserName');
                const chatUserAvatar = document.getElementById('chatUserAvatar');
                const chatUserStatus = document.getElementById('chatUserStatus');

                if (chatInterface) chatInterface.style.display = 'flex';
                if (welcomeScreen) welcomeScreen.style.display = 'none';

                if (chatUserName) chatUserName.textContent = user.name || 'Unknown User';
                if (chatUserAvatar) {
                    chatUserAvatar.innerHTML =
                        `<span class="font-bold">${(user.name || 'U').charAt(0).toUpperCase()}</span>`;
                }
                if (chatUserStatus) {
                    // Simulate online status
                    const isOnline = Math.random() > 0.3;
                    chatUserStatus.innerHTML = `
                    <span class="w-2 h-2 ${isOnline ? 'bg-green-400' : 'bg-gray-400'} rounded-full mr-2"></span>
                    ${isOnline ? 'Online now' : 'Last seen recently'}
                `;
                }
            }

            async loadMessages() {
                if (!this.currentUserId) return;

                try {
                    const response = await fetch(`/ims/chat/messages/${this.currentUserId}`, {
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
                        }
                    });

                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }

                    const data = await response.json();

                    if (data.messages && Array.isArray(data.messages)) {
                        this.renderMessages(data.messages);
                        if (data.messages.length > 0) {
                            this.lastMessageId = Math.max(...data.messages.map(m => m.id || 0));
                        }
                    } else {
                        this.renderMessages([]);
                    }
                } catch (error) {
                    console.error('Error loading messages:', error);
                    this.showNotification('Failed to load messages', 'error');
                }
            }

            startPolling() {
                // Clear existing interval
                if (this.pollInterval) {
                    clearInterval(this.pollInterval);
                }

                this.pollInterval = setInterval(() => {
                    if (this.currentUserId) {
                        this.loadMessages();
                    }
                    // Also refresh user list periodically
                    this.loadUsers();
                }, 3000); // Poll every 3 seconds
            }

            stopPolling() {
                if (this.pollInterval) {
                    clearInterval(this.pollInterval);
                    this.pollInterval = null;
                }
            }

            async sendMessage() {
                const messageText = document.getElementById('messageText');
                const fileInput = document.getElementById('attachmentInput');
                const submitButton = document.querySelector('button[type="submit"]');

                if (!messageText || !fileInput) return;

                const message = messageText.value.trim();

                if (!message && !fileInput.files.length) {
                    messageText.focus();
                    return;
                }

                if (!this.currentUserId) {
                    this.showNotification('Please select a contact to chat with', 'error');
                    return;
                }

                // Disable submit button to prevent double submission
                if (submitButton) {
                    submitButton.disabled = true;
                    submitButton.innerHTML = '<i class="fas fa-spinner fa-spin text-xl"></i>';
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
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
                        },
                        body: formData
                    });

                    const responseData = await response.json();

                    if (response.ok && responseData.success) {
                        messageText.value = '';
                        this.autoResize();
                        this.removeFilePreview();
                        this.loadMessages();
                        this.showNotification('Message sent', 'success');

                        // Focus back to input
                        messageText.focus();
                    } else {
                        throw new Error(responseData.error || 'Failed to send message');
                    }
                } catch (error) {
                    console.error('Error sending message:', error);
                    this.showNotification('Failed to send message: ' + error.message, 'error');
                } finally {
                    // Re-enable submit button
                    if (submitButton) {
                        submitButton.disabled = false;
                        submitButton.innerHTML = '<i class="fas fa-paper-plane text-xl"></i>';
                    }
                }
            }

            // Cleanup method
            destroy() {
                this.stopPolling();

                // Clear timeouts
                if (this.typingTimer) clearTimeout(this.typingTimer);
                if (this.searchTimeout) clearTimeout(this.searchTimeout);

                // Remove event listeners
                document.removeEventListener('keydown', this.handleKeyboardShortcuts);

                // Clear session if needed
                sessionStorage.removeItem('chatSession');
            }

            // Handle page visibility change to pause/resume polling
            setupVisibilityHandling() {
                document.addEventListener('visibilitychange', () => {
                    if (document.hidden) {
                        this.stopPolling();
                    } else {
                        this.startPolling();
                        if (this.currentUserId) {
                            this.loadMessages();
                        }
                    }
                });
            }
        }

        // Initialize visibility handling
        document.addEventListener('DOMContentLoaded', function() {
            const chatApp = new ProfessionalChatApp();
            chatApp.setupVisibilityHandling();
        });

        // Handle page unload
        window.addEventListener('beforeunload', function() {
            // Save any pending data or cleanup
            const chatApp = window.chatApp;
            if (chatApp) {
                chatApp.destroy();
            }
        });
    </script>
</x-app-layout>
