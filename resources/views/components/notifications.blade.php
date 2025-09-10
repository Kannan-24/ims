<div id="notification-panel" class="fixed right-0 top-16  h-full w-80 bg-slate-800 text-white transform translate-x-full transition-transform duration-300 z-40 shadow-xl">
    <div class="p-4">
        <!-- Header -->
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold">Notifications</h3>
            <button onclick="toggleNotifications()" class="text-gray-400 hover:text-white">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <!-- Notification Count -->
        <div class="text-sm text-gray-400 mb-4">1 new updates</div>
        
        <!-- System Status Notification -->
        <div class="bg-slate-700 rounded-lg p-4 mb-4 border border-blue-500">
            <div class="flex items-start space-x-3">
                <div class="w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-cog text-white"></i>
                </div>
                <div class="flex-1">
                    <div class="flex items-center justify-between mb-2">
                        <h4 class="font-medium text-white">System Status</h4>
                        <span class="px-2 py-1 bg-green-600 text-green-100 text-xs rounded-full">Online</span>
                    </div>
                    <p class="text-sm text-gray-300">Email system with PDF attachments is active</p>
                </div>
            </div>
        </div>
        
        <!-- Empty State -->
        <div class="text-center py-12">
            <div class="w-16 h-16 bg-slate-700 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-bell-slash text-gray-400 text-xl"></i>
            </div>
            <h4 class="text-white font-medium mb-2">No new notifications</h4>
            <p class="text-gray-400 text-sm">You're all caught up!</p>
        </div>
    </div>
    
    <!-- Footer Actions -->
    <div class="absolute bottom-0 left-0 right-0 p-4 border-t border-slate-700">
        <div class="flex space-x-2">
            <button class="flex-1 px-4 py-2 text-sm text-blue-400 hover:text-blue-300 transition-colors">
                View all drafts
            </button>
            <button class="flex-1 px-4 py-2 text-sm text-blue-400 hover:text-blue-300 transition-colors">
                Mark all as read
            </button>
        </div>
    </div>
</div>