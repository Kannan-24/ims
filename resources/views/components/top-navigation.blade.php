<header class="fixed top-0 right-0 z-50 bg-white border-b border-gray-200 h-16 transition-all duration-300 ease-in-out lg:left-64 left-0"
        :class="{ 'lg:left-20': sidebarCollapsed, 'lg:left-64': !sidebarCollapsed }">
    <div class="flex items-center justify-between h-full px-4">
        <!-- Left side: Menu toggle and search -->
        <div class="flex items-center space-x-4">
            <!-- Mobile menu button -->
            <button @click="sidebarOpen = !sidebarOpen" class="p-2 text-gray-500 hover:text-gray-700 lg:hidden">
                <i class="fas fa-bars text-lg"></i>
            </button>

            <!-- Desktop sidebar toggle -->
            <button @click="sidebarCollapsed = !sidebarCollapsed"
                class="hidden lg:block p-2 text-gray-500 hover:text-gray-700">
                <i class="fas fa-bars text-lg"></i>
            </button>

            <!-- Search bar -->
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fas fa-search text-gray-400"></i>
                </div>
                <input type="text" placeholder="Search or type command..."
                    class="w-96 pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent hidden md:block">
                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                    <kbd
                        class="inline-flex items-center px-2 py-1 text-xs font-semibold text-gray-500 bg-gray-100 border border-gray-200 rounded">
                        ⌘K
                    </kbd>
                </div>
            </div>
        </div>

        <!-- Right side: Theme toggle, notifications, profile -->
        <div class="flex items-center space-x-4">
            <!-- Theme toggle -->
            <button class="p-2 text-gray-500 hover:text-gray-700">
                <i class="fas fa-moon text-lg"></i>
            </button>

            <!-- Notifications -->
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" class="relative p-2 text-gray-500 hover:text-gray-700">
                    <i class="fas fa-bell text-lg"></i>
                    <span
                        class="absolute -top-1 -right-1 h-4 w-4 bg-orange-500 text-white text-xs rounded-full flex items-center justify-center">
                        1
                    </span>
                </button>

                <!-- Notifications dropdown -->
                <div x-show="open" x-transition @click.away="open = false"
                    class="absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-lg border border-gray-200 z-50">
                    <div class="p-4 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-gray-900">Notifications</h3>
                            <button @click="open = false" class="text-gray-400 hover:text-gray-600">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        <p class="text-sm text-gray-500">1 new updates</p>
                    </div>

                    <div class="p-4">
                        <div class="flex items-center p-3 bg-blue-50 rounded-lg border border-blue-200">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center">
                                    <i class="fas fa-cog text-white text-sm"></i>
                                </div>
                            </div>
                            <div class="ml-3 flex-1">
                                <div class="flex items-center justify-between">
                                    <p class="text-sm font-medium text-gray-900">System Status</p>
                                    <span
                                        class="text-xs bg-green-100 text-green-800 px-2 py-1 rounded-full">Online</span>
                                </div>
                                <p class="text-sm text-gray-600">Email system with PDF attachments is active</p>
                            </div>
                        </div>

                        <div class="text-center py-8">
                            <i class="fas fa-bell-slash text-4xl text-gray-300 mb-2"></i>
                            <p class="text-gray-500 text-sm">No new notifications</p>
                            <p class="text-gray-400 text-xs">You're all caught up!</p>
                        </div>
                    </div>

                    <div class="p-4 border-t border-gray-200 flex justify-between">
                        <button class="text-sm text-blue-600 hover:text-blue-800">View all drafts</button>
                        <button class="text-sm text-gray-600 hover:text-gray-800">Mark all as read</button>
                    </div>
                </div>
            </div>

            <!-- User profile dropdown -->
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" class="flex items-center space-x-2 text-gray-700 hover:text-gray-900">
                    <img src="https://ui-avatars.com/api/?name={{ auth()->user()->name ?? 'User' }}&background=3b82f6&color=fff"
                        alt="Profile" class="w-8 h-8 rounded-full">
                    <span class="hidden md:block text-sm font-medium">{{ auth()->user()->name ?? 'User' }}</span>
                    <i class="fas fa-chevron-down text-xs"></i>
                </button>

                <!-- Profile dropdown -->
                <div x-show="open" x-transition @click.away="open = false"
                    class="absolute right-0 mt-2 w-64 bg-white rounded-lg shadow-lg border border-gray-200 z-50">
                    <div class="p-4 border-b border-gray-200">
                        <p class="font-medium text-gray-900">{{ auth()->user()->name ?? 'User' }}</p>
                        <p class="text-sm text-gray-500">{{ auth()->user()->email ?? 'user@example.com' }}</p>
                    </div>

                    <div class="py-2">
                        <a href="#" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                            <i class="fas fa-user mr-3 text-gray-400"></i>
                            Edit profile
                        </a>
                        <a href="#" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                            <i class="fas fa-cog mr-3 text-gray-400"></i>
                            Account settings
                        </a>
                        <a href="#" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                            <i class="fas fa-question-circle mr-3 text-gray-400"></i>
                            Support
                        </a>
                        <hr class="my-2">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                class="flex items-center w-full px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                <i class="fas fa-sign-out-alt mr-3 text-gray-400"></i>
                                Sign out
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
