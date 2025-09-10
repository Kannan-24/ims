<!-- Fixed Bottom-Right Hotkey Indicator -->
<div x-data="hotkeyIndicator()" x-init="init()" 
     class="fixed bottom-6 right-6 z-50" 
     x-show="isActive">
    
    <!-- Floating Hotkey Button -->
    <button @click="toggleDisplay()" 
            class="bg-blue-600 hover:bg-blue-700 text-white rounded-full w-12 h-12 flex items-center justify-center shadow-lg hover:shadow-xl transition-all duration-300 relative group">
        <i class="fas fa-question-circle text-2xl"></i>
        
        <!-- Tooltip -->
        <div class="absolute bottom-full right-0 mb-3 px-3 py-2 bg-gray-900 text-white text-sm rounded-lg whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none">
            Active Hotkeys
            <div class="absolute top-full right-3 w-0 h-0 border-l-4 border-r-4 border-t-4 border-transparent border-t-gray-900"></div>
        </div>
    </button>

    <!-- Help Modal -->
    <div x-show="showHelp" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 transform scale-95 translate-y-4"
         x-transition:enter-end="opacity-100 transform scale-100 translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 transform scale-100 translate-y-0"
         x-transition:leave-end="opacity-0 transform scale-95 translate-y-4"
         class="absolute bottom-full right-0 mb-4 w-96 bg-white rounded-xl shadow-2xl border border-gray-200 overflow-hidden"
         @click.away="showHelp = false">
        
        <!-- Modal Header -->
        <div class="p-6 bg-gradient-to-r from-blue-50 to-indigo-50 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-keyboard text-blue-600"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Active Hotkeys</h3>
                        <p class="text-sm text-gray-500" x-text="`${hotkeyCount} shortcuts available`"></p>
                    </div>
                </div>
                <button @click="showHelp = false" 
                        class="text-gray-400 hover:text-gray-600 p-2 rounded-lg hover:bg-gray-100 transition-colors">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>

        <!-- Hotkey List -->
        <div class="p-4 max-h-80 overflow-y-auto no-scrollbar">
            <template x-if="hotkeyCount === 0">
                <div class="text-center py-8 text-gray-500">
                    <div class="w-16 h-16 mx-auto mb-4 bg-gray-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-keyboard text-2xl text-gray-400"></i>
                    </div>
                    <h4 class="text-lg font-medium mb-2 text-gray-700">No active hotkeys</h4>
                    <p class="text-sm mb-6">Configure hotkeys to speed up your workflow</p>
                    <a href="/ims/hotkeys" 
                       class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                        <i class="fas fa-plus mr-2"></i>
                        Configure Hotkeys
                    </a>
                </div>
            </template>
            
            <template x-if="hotkeyCount > 0">
                <div class="space-y-3">
                    <template x-for="(hotkey, combination) in hotkeys" :key="combination">
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg border border-gray-200 hover:bg-gray-100 transition-colors cursor-pointer group">
                            <div class="flex-1 min-w-0">
                                <div class="text-sm font-medium text-gray-900 truncate" 
                                     x-text="getActionName(combination)"></div>
                                <div class="text-xs text-gray-500 mt-1" 
                                     x-text="hotkey.description || 'No description'"></div>
                            </div>
                            <div class="ml-4 flex-shrink-0">
                                <span class="inline-flex items-center px-3 py-1 text-xs font-mono font-medium bg-white text-gray-800 border border-gray-200 rounded-md shadow-sm group-hover:shadow-md transition-shadow"
                                      x-text="combination"></span>
                            </div>
                        </div>
                    </template>
                </div>
            </template>
        </div>

        <!-- Modal Footer -->
        <div class="p-4 bg-gray-50 border-t border-gray-200">
            <div class="flex items-center justify-between">
                <div class="flex items-center text-xs text-gray-500">
                    <i class="fas fa-info-circle mr-2"></i>
                    Press hotkeys anywhere on the page
                </div>
                <a href="/ims/hotkeys" 
                   class="inline-flex items-center text-sm text-blue-600 hover:text-blue-700 font-medium transition-colors">
                    <i class="fas fa-cog mr-2"></i>
                    Manage Hotkeys
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Hide scrollbar style -->
<style>
    .no-scrollbar::-webkit-scrollbar {
        display: none;
    }
    .no-scrollbar {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }
</style>

<script>
    function hotkeyIndicator() {
        return {
            hotkeys: {},
            hotkeyCount: 0,
            isActive: false,
            showHelp: false,

            async init() {
                await this.loadHotkeys();
            },

            async loadHotkeys() {
                try {
                    const response = await fetch('/ims/hotkeys/active');
                    if (response.ok) {
                        this.hotkeys = await response.json();
                        this.hotkeyCount = Object.keys(this.hotkeys).length;
                        this.isActive = this.hotkeyCount > 0;
                    }
                } catch (error) {
                    console.warn('Could not load hotkey indicator data:', error);
                    this.isActive = true;
                    this.hotkeyCount = 0;
                }
            },

            toggleDisplay() {
                this.showHelp = !this.showHelp;
            },

            getActionName(combination) {
                const hotkey = this.hotkeys[combination];
                return hotkey ? hotkey.action_name : 'Unknown Action';
            }
        }
    }
</script>
