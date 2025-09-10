@php
    $title = 'Hotkey Manager';
@endphp

<x-app-layout :title="$title">
    <div class="bg-gray-50 min-h-screen" x-data="hotkeyManager()" x-init="init()">
        <!-- Header -->
        <div class="bg-white shadow-sm border-b border-gray-200">
            <div class="px-6 py-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">⌨️ Hotkey Manager</h1>
                        <p class="text-gray-600 mt-1">Create and manage custom keyboard shortcuts for quick actions</p>
                    </div>
                    <div class="flex items-center space-x-3">
                        <!-- Help Button -->
                        <button @click="showHelpModal = true" 
                                class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-lg transition-colors">
                            <i class="fas fa-question-circle mr-2"></i>
                            Help
                        </button>
                    <!-- Add Hotkey Button -->
                    <button @click="openModal()" 
                            class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                        <i class="fas fa-plus w-4 h-4 mr-2"></i>
                        Add New Hotkey
                    </button>
                </div>
            </div>
        </div>

        <!-- Content -->
        <div class="p-6">
            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-keyboard text-blue-600"></i>
                        </div>
                        <div>
                            <p class="text-sm text-blue-600 font-medium">Total Hotkeys</p>
                            <p class="text-2xl font-bold text-blue-900" x-text="hotkeys.length">{{ $hotkeys->count() }}</p>
                        </div>
                    </div>
                </div>
                <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-check-circle text-green-600"></i>
                        </div>
                        <div>
                            <p class="text-sm text-green-600 font-medium">Active</p>
                            <p class="text-2xl font-bold text-green-900" x-text="hotkeys.filter(h => h.is_active).length">{{ $hotkeys->where('is_active', true)->count() }}</p>
                        </div>
                    </div>
                </div>
                <div class="bg-amber-50 border border-amber-200 rounded-lg p-4">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-amber-100 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-pause-circle text-amber-600"></i>
                        </div>
                        <div>
                            <p class="text-sm text-amber-600 font-medium">Inactive</p>
                            <p class="text-2xl font-bold text-amber-900" x-text="hotkeys.filter(h => !h.is_active).length">{{ $hotkeys->where('is_active', false)->count() }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Hotkeys Table -->
            <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h2 class="text-lg font-semibold text-gray-900">Your Hotkeys</h2>
                        <div class="flex items-center space-x-2">
                            <!-- Bulk Actions -->
                            <div x-show="selectedHotkeys.length > 0" 
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="opacity-0 scale-95"
                                 x-transition:enter-end="opacity-100 scale-100"
                                 x-transition:leave="transition ease-in duration-150"
                                 x-transition:leave-start="opacity-100 scale-100"
                                 x-transition:leave-end="opacity-0 scale-95"
                                 class="flex items-center space-x-2 mr-4">
                                <span class="text-sm text-gray-600" x-text="`${selectedHotkeys.length} selected`"></span>
                                <button @click="bulkActivate()" 
                                        class="inline-flex items-center px-3 py-1 bg-green-100 hover:bg-green-200 text-green-700 text-sm font-medium rounded-md transition-colors">
                                    <i class="fas fa-check mr-1"></i>
                                    Activate
                                </button>
                                <button @click="bulkDeactivate()" 
                                        class="inline-flex items-center px-3 py-1 bg-yellow-100 hover:bg-yellow-200 text-yellow-700 text-sm font-medium rounded-md transition-colors">
                                    <i class="fas fa-pause mr-1"></i>
                                    Deactivate
                                </button>
                                <button @click="bulkDelete()" 
                                        class="inline-flex items-center px-3 py-1 bg-red-100 hover:bg-red-200 text-red-700 text-sm font-medium rounded-md transition-colors">
                                    <i class="fas fa-trash mr-1"></i>
                                    Delete
                                </button>
                            </div>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                    <i class="fas fa-search text-gray-400"></i>
                                </div>
                                <input type="text" 
                                       x-model="searchQuery"
                                       placeholder="Search hotkeys..."
                                       class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="w-8 px-6 py-3">
                                    <input type="checkbox" 
                                           @change="toggleSelectAll($event.target.checked)"
                                           :checked="filteredHotkeys.length > 0 && selectedHotkeys.length === filteredHotkeys.length"
                                           :indeterminate="selectedHotkeys.length > 0 && selectedHotkeys.length < filteredHotkeys.length"
                                           class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hotkey</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <template x-for="hotkey in filteredHotkeys" :key="hotkey.id">
                                <tr class="hover:bg-gray-50" :class="selectedHotkeys.includes(hotkey.id) ? 'bg-blue-50' : ''">
                                    <td class="w-8 px-6 py-4">
                                        <input type="checkbox" 
                                               :value="hotkey.id"
                                               x-model="selectedHotkeys"
                                               class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                                <i class="fas fa-bolt text-blue-600 text-sm"></i>
                                            </div>
                                            <div>
                                                <div class="text-sm font-medium text-gray-900" x-text="hotkey.action_name"></div>
                                                <div class="text-xs text-gray-500" x-text="hotkey.action_type"></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="inline-flex items-center space-x-1">
                                            <template x-for="key in hotkey.hotkey_combination.split('+')" :key="key">
                                                <kbd class="px-2 py-1 text-xs font-mono text-gray-600 bg-gray-100 border border-gray-300 rounded" 
                                                     x-text="key.trim()"></kbd>
                                            </template>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-900" x-text="hotkey.description || 'No description'"></div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <button @click="toggleHotkey(hotkey)" 
                                                class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                                                :class="hotkey.is_active ? 'bg-blue-600' : 'bg-gray-300'">
                                            <span class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform"
                                                  :class="hotkey.is_active ? 'translate-x-6' : 'translate-x-1'"></span>
                                        </button>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex items-center justify-end space-x-2">
                                            <button @click="editHotkey(hotkey)" 
                                                    class="text-blue-600 hover:text-blue-900 transition-colors">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button @click="deleteHotkey(hotkey)" 
                                                    class="text-red-600 hover:text-red-900 transition-colors">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            </template>
                            
                            <!-- Empty State -->
                            <tr x-show="filteredHotkeys.length === 0">
                                <td colspan="6" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <i class="fas fa-keyboard text-gray-300 text-4xl mb-4"></i>
                                        <h3 class="text-lg font-medium text-gray-900 mb-2">No hotkeys found</h3>
                                        <p class="text-sm text-gray-500 mb-4" x-text="searchQuery ? 'Try adjusting your search terms.' : 'Get started by creating your first hotkey.'"></p>
                                        <button @click="openModal()" 
                                                class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                                            <i class="fas fa-plus w-4 h-4 mr-2"></i>
                                            Add Your First Hotkey
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Add/Edit Modal -->
        <div x-show="showModal" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-50 overflow-y-auto" 
             @keydown.escape.window="closeModal()">
            
            <!-- Backdrop -->
            <div class="fixed inset-0 bg-gray-900 bg-opacity-50" @click="closeModal()"></div>
            
            <!-- Modal -->
            <div class="relative flex min-h-screen items-center justify-center p-4">
                <div x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="opacity-100 scale-100"
                     x-transition:leave-end="opacity-0 scale-95"
                     class="w-full max-w-md bg-white rounded-lg shadow-xl">
                    
                    <!-- Header -->
                    <div class="flex items-center justify-between p-6 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900" x-text="editingHotkey ? 'Edit Hotkey' : 'Add New Hotkey'"></h3>
                        <button @click="closeModal()" class="text-gray-400 hover:text-gray-600">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    
                    <!-- Form -->
                    <form @submit.prevent="saveHotkey()" class="p-6 space-y-6">
                        
                        <!-- Step indicator -->
                        <div class="flex items-center justify-between mb-6">
                            <div class="flex items-center space-x-2">
                                <div class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-medium"
                                     :class="currentStep >= 1 ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-600'">1</div>
                                <div class="w-16 h-1 rounded-full" :class="currentStep >= 2 ? 'bg-blue-600' : 'bg-gray-200'"></div>
                                <div class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-medium"
                                     :class="currentStep >= 2 ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-600'">2</div>
                                <div class="w-16 h-1 rounded-full" :class="currentStep >= 3 ? 'bg-blue-600' : 'bg-gray-200'"></div>
                                <div class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-medium"
                                     :class="currentStep >= 3 ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-600'">3</div>
                            </div>
                            <div class="text-sm text-gray-500">
                                Step <span x-text="currentStep"></span> of 3
                            </div>
                        </div>

                        <!-- Step 1: Choose Action -->
                        <div x-show="currentStep === 1" x-transition>
                            <h4 class="text-lg font-medium text-gray-900 mb-6">What would you like to create a hotkey for?</h4>
                            
                            <!-- Quick Actions (Most Common) -->
                            <div class="mb-8">
                                <h5 class="text-sm font-medium text-gray-700 mb-3">Popular Actions</h5>
                                <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                                    <template x-for="action in popularActions" :key="action.name">
                                        <button type="button"
                                                @click="selectAction(action)"
                                                class="group relative p-4 text-left border-2 border-gray-200 rounded-xl hover:border-blue-300 hover:shadow-md transition-all duration-200"
                                                :class="form.action_name === action.name ? 'border-blue-500 bg-blue-50 ring-2 ring-blue-200' : 'bg-white hover:bg-blue-50'">
                                            <div class="flex flex-col items-center text-center space-y-3">
                                                <div class="w-12 h-12 rounded-full flex items-center justify-center transition-colors"
                                                     :class="form.action_name === action.name ? 'bg-blue-500 text-white' : 'bg-gray-100 text-gray-600 group-hover:bg-blue-500 group-hover:text-white'">
                                                    <i :class="action.icon" class="text-xl"></i>
                                                </div>
                                                <div>
                                                    <div class="font-medium text-gray-900 text-sm" x-text="action.name"></div>
                                                    <div class="text-xs text-gray-500 mt-1" x-text="action.description"></div>
                                                </div>
                                                <div x-show="form.action_name === action.name" class="absolute top-2 right-2">
                                                    <div class="w-6 h-6 bg-blue-500 rounded-full flex items-center justify-center">
                                                        <i class="fas fa-check text-white text-xs"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </button>
                                    </template>
                                </div>
                            </div>

                            <!-- More Actions (Expandable) -->
                            <div class="mb-8">
                                <button type="button" 
                                        @click="showMoreActions = !showMoreActions"
                                        class="flex items-center justify-between w-full text-left">
                                    <h5 class="text-sm font-medium text-gray-700">More Actions</h5>
                                    <i class="fas fa-chevron-down text-gray-400 transition-transform" 
                                       :class="showMoreActions ? 'rotate-180' : ''"></i>
                                </button>
                                
                                <div x-show="showMoreActions" 
                                     x-transition:enter="transition ease-out duration-300"
                                     x-transition:enter-start="opacity-0 -translate-y-2"
                                     x-transition:enter-end="opacity-100 translate-y-0"
                                     class="mt-3 grid grid-cols-2 md:grid-cols-4 gap-2">
                                    <template x-for="action in moreActions" :key="action.name">
                                        <button type="button"
                                                @click="selectAction(action)"
                                                class="p-3 text-left border border-gray-200 rounded-lg hover:border-blue-300 hover:bg-blue-50 transition-colors text-sm"
                                                :class="form.action_name === action.name ? 'border-blue-500 bg-blue-50 text-blue-700' : 'text-gray-700'">
                                            <div class="flex items-center space-x-2">
                                                <i :class="action.icon" class="text-gray-500"></i>
                                                <span class="font-medium" x-text="action.name"></span>
                                                <i x-show="form.action_name === action.name" class="fas fa-check text-blue-600 text-xs ml-auto"></i>
                                            </div>
                                        </button>
                                    </template>
                                </div>
                            </div>

                            <!-- Custom Action -->
                            <div class="border-t pt-6">
                                <h5 class="text-sm font-medium text-gray-700 mb-4">
                                    <i class="fas fa-plus-circle text-blue-500 mr-2"></i>
                                    Create Custom Action
                                </h5>
                                <div class="bg-gray-50 rounded-lg p-4 space-y-3">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                        <div>
                                            <label class="block text-xs font-medium text-gray-600 mb-1">Action Name</label>
                                            <input type="text" 
                                                   x-model="customActionName"
                                                   placeholder="e.g., My Custom Page"
                                                   class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium text-gray-600 mb-1">URL or Action</label>
                                            <input type="text" 
                                                   x-model="customActionUrl"
                                                   placeholder="e.g., /my-page or #modal"
                                                   class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                                        </div>
                                    </div>
                                    <div class="flex justify-end">
                                        <button type="button"
                                                @click="addCustomAction()"
                                                :disabled="!customActionName || !customActionUrl"
                                                class="px-4 py-2 bg-blue-600 hover:bg-blue-700 disabled:bg-gray-300 disabled:cursor-not-allowed text-white text-sm font-medium rounded-md transition-colors">
                                            <i class="fas fa-plus mr-1"></i>
                                            Add Custom Action
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Selected Action Preview -->
                            <div x-show="form.action_name" 
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="opacity-0 scale-95"
                                 x-transition:enter-end="opacity-100 scale-100"
                                 class="mt-6 p-4 bg-green-50 border border-green-200 rounded-lg">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 bg-green-500 rounded-full flex items-center justify-center">
                                        <i class="fas fa-check text-white"></i>
                                    </div>
                                    <div>
                                        <div class="text-sm font-medium text-green-900">Selected Action</div>
                                        <div class="text-lg font-semibold text-green-800" x-text="form.action_name"></div>
                                        <div class="text-sm text-green-600" x-text="form.action_url"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Step 2: Set Hotkey -->
                        <div x-show="currentStep === 2" x-transition>
                            <h4 class="text-lg font-medium text-gray-900 mb-4">Set Your Hotkey</h4>
                            
                            <div class="space-y-4">
                                <!-- Current Selection Display -->
                                <div class="p-4 bg-blue-50 border border-blue-200 rounded-lg">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <div class="text-sm text-blue-600 font-medium">Selected Action:</div>
                                            <div class="text-lg font-semibold text-blue-900" x-text="form.action_name"></div>
                                        </div>
                                        <div class="text-right">
                                            <div class="text-sm text-blue-600 font-medium">Current Hotkey:</div>
                                            <div class="flex space-x-1" x-show="form.hotkey_combination">
                                                <template x-for="key in (form.hotkey_combination || '').split('+')" :key="key">
                                                    <kbd class="px-2 py-1 text-sm font-mono text-blue-800 bg-blue-100 border border-blue-300 rounded" 
                                                         x-text="key.trim()"></kbd>
                                                </template>
                                            </div>
                                            <div x-show="!form.hotkey_combination" class="text-gray-400 text-sm">Not set</div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Hotkey Capture Area -->
                                <div class="space-y-3">
                                    <label class="block text-sm font-medium text-gray-700">Press your desired key combination</label>
                                    <div class="relative">
                                        <div class="w-full min-h-24 p-4 border-2 border-dashed border-gray-300 rounded-lg bg-gray-50 flex flex-col items-center justify-center cursor-pointer hover:border-blue-400 hover:bg-blue-50 transition-colors"
                                             @click="focusHotkeyCapture()"
                                             :class="isCapturing ? 'border-blue-500 bg-blue-50' : ''">
                                            
                                            <div x-show="!isCapturing && !form.hotkey_combination" class="text-center">
                                                <i class="fas fa-keyboard text-2xl text-gray-400 mb-2"></i>
                                                <div class="text-sm text-gray-600 font-medium">Click here and press keys</div>
                                                <div class="text-xs text-gray-500 mt-1">e.g., Ctrl + Shift + D</div>
                                            </div>
                                            
                                            <div x-show="isCapturing" class="text-center">
                                                <i class="fas fa-circle text-red-500 animate-pulse text-sm mb-2"></i>
                                                <div class="text-sm text-red-600 font-medium">Press your keys now...</div>
                                                <div class="text-xs text-gray-500 mt-1">Listening for key combination</div>
                                            </div>
                                            
                                            <div x-show="form.hotkey_combination && !isCapturing" class="text-center">
                                                <div class="flex space-x-1 justify-center mb-2">
                                                    <template x-for="key in form.hotkey_combination.split('+')" :key="key">
                                                        <kbd class="px-3 py-2 text-lg font-mono text-blue-800 bg-blue-200 border border-blue-300 rounded" 
                                                             x-text="key.trim()"></kbd>
                                                    </template>
                                                </div>
                                                <div class="text-sm text-blue-600 font-medium">Hotkey captured!</div>
                                                <button type="button" @click="clearHotkey()" class="text-xs text-gray-500 hover:text-gray-700 mt-1">
                                                    Click to change
                                                </button>
                                            </div>
                                        </div>
                                        
                                        <!-- Hidden input for key capture -->
                                        <input type="text" 
                                               x-ref="hotkeyInput"
                                               @keydown="captureHotkey($event)"
                                               @focus="isCapturing = true"
                                               @blur="isCapturing = false"
                                               class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                                               readonly>
                                    </div>
                                </div>

                                <!-- Quick Suggestions -->
                                <div class="grid grid-cols-2 md:grid-cols-4 gap-2">
                                    <template x-for="suggestion in hotkeysuggestions" :key="suggestion">
                                        <button type="button"
                                                @click="form.hotkey_combination = suggestion"
                                                class="px-3 py-2 text-xs border border-gray-200 rounded hover:border-blue-300 hover:bg-blue-50 transition-colors"
                                                :class="form.hotkey_combination === suggestion ? 'border-blue-500 bg-blue-50' : ''">
                                            <template x-for="key in suggestion.split('+')" :key="key">
                                                <kbd class="font-mono text-xs" x-text="key"></kbd>
                                            </template>
                                        </button>
                                    </template>
                                </div>
                            </div>
                        </div>

                        <!-- Step 3: Add Description -->
                        <div x-show="currentStep === 3" x-transition>
                            <h4 class="text-lg font-medium text-gray-900 mb-4">Add Description (Optional)</h4>
                            
                            <div class="space-y-4">
                                <!-- Summary -->
                                <div class="p-4 bg-green-50 border border-green-200 rounded-lg">
                                    <div class="text-sm text-green-600 font-medium mb-2">Hotkey Summary</div>
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <div class="font-semibold text-green-900" x-text="form.action_name"></div>
                                            <div class="text-sm text-green-700" x-text="form.action_url"></div>
                                        </div>
                                        <div class="flex space-x-1">
                                            <template x-for="key in (form.hotkey_combination || '').split('+')" :key="key">
                                                <kbd class="px-2 py-1 text-sm font-mono text-green-800 bg-green-100 border border-green-300 rounded" 
                                                     x-text="key.trim()"></kbd>
                                            </template>
                                        </div>
                                    </div>
                                </div>

                                <!-- Description -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                                    <textarea x-model="form.description" 
                                              rows="3"
                                              placeholder="Describe what this hotkey does... (optional)"
                                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none"></textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Navigation Buttons -->
                        <div class="flex justify-between pt-6 border-t">
                            <div>
                                <button type="button" 
                                        @click="currentStep > 1 ? currentStep-- : closeModal()"
                                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                                    <span x-text="currentStep === 1 ? 'Cancel' : 'Previous'"></span>
                                </button>
                            </div>
                            
                            <div class="space-x-3">
                                <button type="button" 
                                        x-show="currentStep < 3"
                                        @click="nextStep()"
                                        :disabled="!canProceed()"
                                        class="px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                                    Next
                                </button>
                                
                                <button type="submit" 
                                        x-show="currentStep === 3"
                                        :disabled="!form.action_name || !form.hotkey_combination"
                                        class="px-4 py-2 text-sm font-medium text-white bg-green-600 hover:bg-green-700 rounded-lg transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                                    <span x-text="editingHotkey ? 'Update Hotkey' : 'Create Hotkey'"></span>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Help Modal -->
        <div x-show="showHelpModal" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-50 overflow-y-auto" 
             @keydown.escape.window="showHelpModal = false">
            
            <!-- Backdrop -->
            <div class="fixed inset-0 bg-gray-900 bg-opacity-50" @click="showHelpModal = false"></div>
            
            <!-- Modal -->
            <div class="relative flex min-h-screen items-center justify-center p-4">
                <div x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="opacity-100 scale-100"
                     x-transition:leave-end="opacity-0 scale-95"
                     class="w-full max-w-4xl bg-white rounded-lg shadow-xl">
                    
                    <!-- Header -->
                    <div class="flex items-center justify-between p-6 border-b border-gray-200">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-question-circle text-blue-600"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">Hotkey Manager Help</h3>
                                <p class="text-sm text-gray-500">Learn how to use keyboard shortcuts effectively</p>
                            </div>
                        </div>
                        <button @click="showHelpModal = false" class="text-gray-400 hover:text-gray-600">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    
                    <!-- Content -->
                    <div class="p-6 max-h-96 overflow-y-auto">
                        <div class="space-y-6">
                            <!-- What are Hotkeys -->
                            <div>
                                <h4 class="text-lg font-semibold text-gray-900 mb-3">What are Hotkeys?</h4>
                                <p class="text-gray-700 mb-3">
                                    Hotkeys are keyboard shortcuts that allow you to quickly perform actions without using your mouse. 
                                    They combine modifier keys (Ctrl, Shift, Alt) with regular keys to trigger specific functions.
                                </p>
                                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                    <h5 class="font-medium text-blue-900 mb-2">Example:</h5>
                                    <div class="flex items-center space-x-2">
                                        <kbd class="px-2 py-1 bg-white border border-blue-300 rounded text-sm">Ctrl</kbd>
                                        <span class="text-blue-700">+</span>
                                        <kbd class="px-2 py-1 bg-white border border-blue-300 rounded text-sm">Shift</kbd>
                                        <span class="text-blue-700">+</span>
                                        <kbd class="px-2 py-1 bg-white border border-blue-300 rounded text-sm">D</kbd>
                                        <span class="text-blue-700 ml-4">→ Opens Dashboard</span>
                                    </div>
                                </div>
                            </div>

                            <!-- How to Create Hotkeys -->
                            <div>
                                <h4 class="text-lg font-semibold text-gray-900 mb-3">🚀 Quick Start Guide</h4>
                                <div class="space-y-4">
                                    <div class="flex items-start space-x-4">
                                        <div class="w-8 h-8 bg-blue-500 text-white text-sm font-bold rounded-full flex items-center justify-center flex-shrink-0">1</div>
                                        <div>
                                            <h5 class="font-medium text-gray-900">Choose Your Action</h5>
                                            <p class="text-sm text-gray-600">Pick from popular actions like Dashboard, Calendar, or create a custom one. Just click on what you want!</p>
                                        </div>
                                    </div>
                                    <div class="flex items-start space-x-4">
                                        <div class="w-8 h-8 bg-blue-500 text-white text-sm font-bold rounded-full flex items-center justify-center flex-shrink-0">2</div>
                                        <div>
                                            <h5 class="font-medium text-gray-900">Set Your Hotkey</h5>
                                            <p class="text-sm text-gray-600">Click the capture area and press your keys. For example: <kbd class="px-2 py-1 bg-gray-100 rounded text-xs">Ctrl</kbd> + <kbd class="px-2 py-1 bg-gray-100 rounded text-xs">Shift</kbd> + <kbd class="px-2 py-1 bg-gray-100 rounded text-xs">D</kbd></p>
                                        </div>
                                    </div>
                                    <div class="flex items-start space-x-4">
                                        <div class="w-8 h-8 bg-blue-500 text-white text-sm font-bold rounded-full flex items-center justify-center flex-shrink-0">3</div>
                                        <div>
                                            <h5 class="font-medium text-gray-900">Save & Use</h5>
                                            <p class="text-sm text-gray-600">Add a description (optional) and save. Your hotkey works immediately on all pages!</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Custom Actions -->
                            <div>
                                <h4 class="text-lg font-semibold text-gray-900 mb-3">🎯 Creating Custom Actions</h4>
                                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 space-y-3">
                                    <div>
                                        <h5 class="font-medium text-blue-900">For Web Pages:</h5>
                                        <p class="text-sm text-blue-700">Enter the URL like: <code class="bg-blue-100 px-2 py-1 rounded text-xs">/my-custom-page</code> or <code class="bg-blue-100 px-2 py-1 rounded text-xs">https://example.com</code></p>
                                    </div>
                                    <div>
                                        <h5 class="font-medium text-blue-900">For Modals/Popups:</h5>
                                        <p class="text-sm text-blue-700">Start with # like: <code class="bg-blue-100 px-2 py-1 rounded text-xs">#my-modal</code> or <code class="bg-blue-100 px-2 py-1 rounded text-xs">#search</code></p>
                                    </div>
                                </div>
                            </div>

                            <!-- Best Practices -->
                            <div>
                                <h4 class="text-lg font-semibold text-gray-900 mb-3">Best Practices</h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div class="space-y-3">
                                        <div class="flex items-start space-x-3">
                                            <i class="fas fa-check-circle text-green-500 mt-1"></i>
                                            <div>
                                                <h5 class="font-medium text-gray-900">Use modifier keys</h5>
                                                <p class="text-sm text-gray-600">Always include Ctrl, Shift, or Alt</p>
                                            </div>
                                        </div>
                                        <div class="flex items-start space-x-3">
                                            <i class="fas fa-check-circle text-green-500 mt-1"></i>
                                            <div>
                                                <h5 class="font-medium text-gray-900">Be memorable</h5>
                                                <p class="text-sm text-gray-600">Use logical letter associations</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="space-y-3">
                                        <div class="flex items-start space-x-3">
                                            <i class="fas fa-exclamation-triangle text-amber-500 mt-1"></i>
                                            <div>
                                                <h5 class="font-medium text-gray-900">Avoid conflicts</h5>
                                                <p class="text-sm text-gray-600">Don't override system shortcuts</p>
                                            </div>
                                        </div>
                                        <div class="flex items-start space-x-3">
                                            <i class="fas fa-exclamation-triangle text-amber-500 mt-1"></i>
                                            <div>
                                                <h5 class="font-medium text-gray-900">Test regularly</h5>
                                                <p class="text-sm text-gray-600">Make sure they work as expected</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Example Hotkeys -->
                            <div>
                                <h4 class="text-lg font-semibold text-gray-900 mb-3">💡 Example Hotkeys</h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div class="bg-gray-50 rounded-lg p-3">
                                        <div class="flex items-center justify-between mb-2">
                                            <span class="font-medium text-gray-900">Dashboard</span>
                                            <div class="flex space-x-1">
                                                <kbd class="px-2 py-1 bg-gray-200 rounded text-xs">Ctrl</kbd>
                                                <kbd class="px-2 py-1 bg-gray-200 rounded text-xs">Shift</kbd>
                                                <kbd class="px-2 py-1 bg-gray-200 rounded text-xs">D</kbd>
                                            </div>
                                        </div>
                                        <p class="text-xs text-gray-600">Quick access to home page</p>
                                    </div>
                                    
                                    <div class="bg-gray-50 rounded-lg p-3">
                                        <div class="flex items-center justify-between mb-2">
                                            <span class="font-medium text-gray-900">Search</span>
                                            <div class="flex space-x-1">
                                                <kbd class="px-2 py-1 bg-gray-200 rounded text-xs">Ctrl</kbd>
                                                <kbd class="px-2 py-1 bg-gray-200 rounded text-xs">K</kbd>
                                            </div>
                                        </div>
                                        <p class="text-xs text-gray-600">Open search modal instantly</p>
                                    </div>
                                    
                                    <div class="bg-gray-50 rounded-lg p-3">
                                        <div class="flex items-center justify-between mb-2">
                                            <span class="font-medium text-gray-900">Calendar</span>
                                            <div class="flex space-x-1">
                                                <kbd class="px-2 py-1 bg-gray-200 rounded text-xs">Ctrl</kbd>
                                                <kbd class="px-2 py-1 bg-gray-200 rounded text-xs">Shift</kbd>
                                                <kbd class="px-2 py-1 bg-gray-200 rounded text-xs">C</kbd>
                                            </div>
                                        </div>
                                        <p class="text-xs text-gray-600">Open calendar view</p>
                                    </div>
                                    
                                    <div class="bg-gray-50 rounded-lg p-3">
                                        <div class="flex items-center justify-between mb-2">
                                            <span class="font-medium text-gray-900">Settings</span>
                                            <div class="flex space-x-1">
                                                <kbd class="px-2 py-1 bg-gray-200 rounded text-xs">Ctrl</kbd>
                                                <kbd class="px-2 py-1 bg-gray-200 rounded text-xs">,</kbd>
                                            </div>
                                        </div>
                                        <p class="text-xs text-gray-600">Access app settings</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Pro Tips -->
                            <div>
                                <h4 class="text-lg font-semibold text-gray-900 mb-3">✨ Pro Tips</h4>
                                <ul class="space-y-2 text-sm text-gray-600">
                                    <li class="flex items-start space-x-2">
                                        <i class="fas fa-check-circle text-green-500 mt-0.5"></i>
                                        <span>Use logical letters (D for Dashboard, C for Calendar)</span>
                                    </li>
                                    <li class="flex items-start space-x-2">
                                        <i class="fas fa-check-circle text-green-500 mt-0.5"></i>
                                        <span>Always include Ctrl, Shift, or Alt to avoid conflicts</span>
                                    </li>
                                    <li class="flex items-start space-x-2">
                                        <i class="fas fa-check-circle text-green-500 mt-0.5"></i>
                                        <span>Keep hotkeys simple and memorable</span>
                                    </li>
                                    <li class="flex items-start space-x-2">
                                        <i class="fas fa-info-circle text-blue-500 mt-0.5"></i>
                                        <span>Hotkeys work on all pages except when typing in forms</span>
                                    </li>
                                    <li class="flex items-start space-x-2">
                                        <i class="fas fa-info-circle text-blue-500 mt-0.5"></i>
                                        <span>You can toggle hotkeys on/off anytime</span>
                                    </li>
                                </ul>
                            </div>

                            <!-- Troubleshooting -->
                            <div>
                                <h4 class="text-lg font-semibold text-gray-900 mb-3">🔧 Troubleshooting</h4>
                                <div class="bg-amber-50 border border-amber-200 rounded-lg p-4">
                                    <ul class="space-y-2 text-sm text-amber-800">
                                        <li><strong>Hotkey not working?</strong> Make sure it's active (green toggle) and try refreshing the page</li>
                                        <li><strong>Can't capture keys?</strong> Click the capture area first, then press your combination</li>
                                        <li><strong>Getting conflicts?</strong> Try a different combination or check existing hotkeys</li>
                                        <li><strong>Custom action not working?</strong> Check the URL format and make sure it starts with / or #</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Footer -->
                    <div class="flex justify-end p-6 border-t border-gray-200">
                        <button @click="showHelpModal = false" 
                                class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                            Got it, thanks!
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function hotkeyManager() {
            return {
                hotkeys: @json($hotkeys->toArray()),
                availableActions: [],
                showModal: false,
                showHelpModal: false,
                editingHotkey: null,
                searchQuery: '',
                selectedHotkeys: [],
                currentStep: 1,
                isCapturing: false,
                customActionName: '',
                customActionUrl: '',
                showMoreActions: false,
                form: {
                    action_name: '',
                    hotkey_combination: '',
                    description: '',
                    action_type: 'navigate',
                    action_url: ''
                },
                popularActions: [
                    {
                        name: 'Dashboard',
                        url: '/dashboard',
                        type: 'navigate',
                        icon: 'fas fa-home',
                        description: 'Go to main dashboard'
                    },
                    {
                        name: 'Calendar',
                        url: '/calendar',
                        type: 'navigate',
                        icon: 'fas fa-calendar-alt',
                        description: 'Open calendar'
                    },
                    {
                        name: 'Search',
                        url: '#search',
                        type: 'modal',
                        icon: 'fas fa-search',
                        description: 'Quick search'
                    },
                    {
                        name: 'New Invoice',
                        url: '/invoices/create',
                        type: 'navigate',
                        icon: 'fas fa-file-invoice',
                        description: 'Create new invoice'
                    },
                    {
                        name: 'Customers',
                        url: '/customers',
                        type: 'navigate',
                        icon: 'fas fa-users',
                        description: 'Manage customers'
                    },
                    {
                        name: 'Products',
                        url: '/products',
                        type: 'navigate',
                        icon: 'fas fa-box',
                        description: 'Manage products'
                    }
                ],
                moreActions: [
                    {
                        name: 'Reports',
                        url: '/reports',
                        type: 'navigate',
                        icon: 'fas fa-chart-bar',
                        description: 'View reports'
                    },
                    {
                        name: 'Settings',
                        url: '/settings',
                        type: 'navigate',
                        icon: 'fas fa-cog',
                        description: 'App settings'
                    },
                    {
                        name: 'Profile',
                        url: '/profile',
                        type: 'navigate',
                        icon: 'fas fa-user',
                        description: 'Edit profile'
                    },
                    {
                        name: 'Suppliers',
                        url: '/suppliers',
                        type: 'navigate',
                        icon: 'fas fa-truck',
                        description: 'Manage suppliers'
                    },
                    {
                        name: 'Stocks',
                        url: '/stocks',
                        type: 'navigate',
                        icon: 'fas fa-warehouse',
                        description: 'Stock management'
                    },
                    {
                        name: 'Payments',
                        url: '/payments',
                        type: 'navigate',
                        icon: 'fas fa-credit-card',
                        description: 'Payment records'
                    },
                    {
                        name: 'Help',
                        url: '#help',
                        type: 'modal',
                        icon: 'fas fa-question-circle',
                        description: 'Get help'
                    },
                    {
                        name: 'Logout',
                        url: '/logout',
                        type: 'function',
                        icon: 'fas fa-sign-out-alt',
                        description: 'Sign out'
                    }
                ],
                hotkeysuggestions: [
                    'Ctrl+Shift+D',
                    'Ctrl+Shift+C',
                    'Ctrl+Shift+S',
                    'Ctrl+Alt+N',
                    'Ctrl+Shift+R',
                    'Alt+Shift+H',
                    'Ctrl+Shift+P',
                    'Ctrl+Alt+M'
                ],

                get filteredHotkeys() {
                    if (!this.searchQuery) return this.hotkeys;
                    
                    const query = this.searchQuery.toLowerCase();
                    return this.hotkeys.filter(hotkey => 
                        hotkey.action_name.toLowerCase().includes(query) ||
                        hotkey.hotkey_combination.toLowerCase().includes(query) ||
                        (hotkey.description && hotkey.description.toLowerCase().includes(query))
                    );
                },

                async init() {
                    // Start with server-provided data, then optionally refresh with AJAX
                    this.hotkeys = @json($hotkeys->toArray());
                    this.initGlobalHotkeys();
                    
                    // Optionally try to refresh data via AJAX (but don't block on it)
                    setTimeout(() => {
                        this.loadHotkeys();
                    }, 100);
                },

                async loadHotkeys() {
                    try {
                        const response = await fetch('{{ route("hotkeys.index") }}', {
                            headers: {
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        });
                        
                        // Check if we got a successful response
                        if (!response.ok) {
                            console.error('HTTP error:', response.status, response.statusText);
                            throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                        }
                        
                        // Check if the response is actually JSON
                        const contentType = response.headers.get('content-type');
                        if (!contentType || !contentType.includes('application/json')) {
                            console.error('Expected JSON but got:', contentType);
                            // If we get HTML, it's likely a redirect to login
                            if (contentType && contentType.includes('text/html')) {
                                console.warn('Got HTML response - user might need to re-authenticate');
                                // Fallback to hardcoded data from server
                                this.hotkeys = @json($hotkeys->toArray());
                                return;
                            }
                            throw new Error('Response is not valid JSON');
                        }
                        
                        const data = await response.json();
                        this.hotkeys = data.hotkeys || data || @json($hotkeys->toArray());
                        
                    } catch (error) {
                        console.error('Error loading hotkeys:', error);
                        // Fallback to data passed from server
                        this.hotkeys = @json($hotkeys->toArray());
                        // Don't show error notification for initial load, just use fallback data
                    }
                },

                get availableActions() {
                    return [...this.popularActions, ...this.moreActions];
                },

                openModal() {
                    this.showModal = true;
                    this.editingHotkey = null;
                    this.resetForm();
                    this.currentStep = 1;
                },

                editHotkey(hotkey) {
                    this.editingHotkey = hotkey;
                    this.form = { ...hotkey };
                    this.showModal = true;
                    this.currentStep = 3; // Skip to description step for editing
                },

                closeModal() {
                    this.showModal = false;
                    this.editingHotkey = null;
                    this.resetForm();
                    this.currentStep = 1;
                    this.isCapturing = false;
                },

                resetForm() {
                    this.form = {
                        action_name: '',
                        hotkey_combination: '',
                        description: '',
                        action_type: 'navigate',
                        action_url: ''
                    };
                    this.customActionName = '';
                    this.customActionUrl = '';
                },

                selectAction(action) {
                    this.form.action_name = action.name;
                    this.form.action_type = action.type;
                    this.form.action_url = action.url;
                },

                addCustomAction() {
                    if (this.customActionName && this.customActionUrl) {
                        const customAction = {
                            name: this.customActionName,
                            url: this.customActionUrl,
                            type: this.customActionUrl.startsWith('#') ? 'modal' : 'navigate',
                            icon: 'fas fa-external-link-alt',
                            description: 'Custom action'
                        };
                        
                        // Add to popular actions temporarily for this session
                        this.popularActions.push(customAction);
                        this.selectAction(customAction);
                        this.customActionName = '';
                        this.customActionUrl = '';
                        
                        this.showNotification('Custom action added! You can now proceed to set your hotkey.', 'success');
                    }
                },

                nextStep() {
                    if (this.canProceed()) {
                        this.currentStep++;
                        
                        // Auto-focus hotkey capture on step 2
                        if (this.currentStep === 2) {
                            this.$nextTick(() => {
                                this.focusHotkeyCapture();
                            });
                        }
                    }
                },

                canProceed() {
                    if (this.currentStep === 1) {
                        return this.form.action_name !== '';
                    } else if (this.currentStep === 2) {
                        return this.form.hotkey_combination !== '';
                    }
                    return true;
                },

                focusHotkeyCapture() {
                    this.$refs.hotkeyInput.focus();
                },

                clearHotkey() {
                    this.form.hotkey_combination = '';
                    this.focusHotkeyCapture();
                },

                captureHotkey(event) {
                    event.preventDefault();
                    
                    // Don't capture single modifier keys
                    if (['Control', 'Shift', 'Alt', 'Meta'].includes(event.key)) {
                        return;
                    }
                    
                    const keys = [];
                    
                    // Add modifier keys
                    if (event.ctrlKey) keys.push('Ctrl');
                    if (event.shiftKey) keys.push('Shift');
                    if (event.altKey) keys.push('Alt');
                    if (event.metaKey) keys.push('Meta');
                    
                    // Add the main key
                    let mainKey = event.key;
                    
                    // Handle special keys
                    const specialKeys = {
                        ' ': 'Space',
                        'Enter': 'Enter',
                        'Escape': 'Escape',
                        'Tab': 'Tab',
                        'Backspace': 'Backspace',
                        'Delete': 'Delete',
                        'ArrowUp': 'Up',
                        'ArrowDown': 'Down',
                        'ArrowLeft': 'Left',
                        'ArrowRight': 'Right'
                    };
                    
                    if (specialKeys[mainKey]) {
                        mainKey = specialKeys[mainKey];
                    } else if (mainKey.length === 1) {
                        // Convert to uppercase for letters
                        mainKey = mainKey.toUpperCase();
                    }
                    
                    // Must have at least one modifier key for safety
                    if (keys.length > 0) {
                        keys.push(mainKey);
                        this.form.hotkey_combination = keys.join('+');
                        this.isCapturing = false;
                        this.$refs.hotkeyInput.blur();
                    } else {
                        // Show warning for single keys
                        this.showNotification('Please use at least one modifier key (Ctrl, Shift, Alt)', 'warning');
                    }
                },

                async saveHotkey() {
                    try {
                        // Check for CSRF token
                        const csrfToken = document.querySelector('meta[name="csrf-token"]');
                        if (!csrfToken) {
                            this.showNotification('CSRF token not found - please refresh the page', 'error');
                            return;
                        }

                        // Validate form
                        if (!this.form.action_name || !this.form.hotkey_combination) {
                            this.showNotification('Please complete all required fields', 'error');
                            return;
                        }

                        // Check for duplicate hotkey combinations
                        const existingHotkey = this.hotkeys.find(h => 
                            h.hotkey_combination === this.form.hotkey_combination && 
                            (!this.editingHotkey || h.id !== this.editingHotkey.id)
                        );
                        
                        if (existingHotkey) {
                            this.showNotification(`Hotkey "${this.form.hotkey_combination}" is already used for "${existingHotkey.action_name}"`, 'error');
                            return;
                        }

                        const url = this.editingHotkey 
                            ? `{{ url('ims/hotkeys') }}/${this.editingHotkey.id}`
                            : '{{ route("hotkeys.store") }}';
                            
                        const method = this.editingHotkey ? 'PUT' : 'POST';
                        
                        console.log('Saving hotkey:', {
                            url,
                            method,
                            form: this.form,
                            hasCSRF: !!csrfToken.content
                        });
                        
                        // Show loading notification
                        this.showNotification('Saving hotkey...', 'info');
                        
                        const response = await fetch(url, {
                            method: method,
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': csrfToken.content,
                                'X-Requested-With': 'XMLHttpRequest'
                            },
                            body: JSON.stringify(this.form)
                        });
                        
                        console.log('Response:', {
                            status: response.status,
                            statusText: response.statusText,
                            ok: response.ok
                        });
                        
                        if (!response.ok) {
                            let errorData;
                            try {
                                errorData = await response.json();
                            } catch (e) {
                                errorData = { message: `HTTP ${response.status}: ${response.statusText}` };
                            }
                            console.error('Server error:', errorData);
                            throw new Error(errorData.message || `HTTP error! status: ${response.status}`);
                        }
                        
                        const data = await response.json();
                        console.log('Success response:', data);
                        
                        if (data.success) {
                            if (this.editingHotkey) {
                                const index = this.hotkeys.findIndex(h => h.id === this.editingHotkey.id);
                                if (index !== -1) {
                                    this.hotkeys[index] = data.hotkey;
                                }
                                this.showNotification('Hotkey updated successfully!', 'success');
                            } else {
                                this.hotkeys.push(data.hotkey);
                                this.showNotification('Hotkey created successfully!', 'success');
                            }
                            
                            this.closeModal();
                            this.initGlobalHotkeys(); // Refresh hotkeys
                        } else {
                            this.showNotification(data.message || 'Failed to save hotkey', 'error');
                        }
                    } catch (error) {
                        console.error('Error saving hotkey:', error);
                        // More detailed error message
                        let errorMessage = 'An error occurred while saving the hotkey';
                        if (error.message.includes('422')) {
                            errorMessage = 'Validation error - please check your input';
                        } else if (error.message.includes('419')) {
                            errorMessage = 'Session expired - please refresh the page and try again';
                        } else if (error.message.includes('500')) {
                            errorMessage = 'Server error - please try again later';
                        } else if (error.message) {
                            errorMessage = error.message;
                        }
                        this.showNotification(errorMessage, 'error');
                    }
                },

                async toggleHotkey(hotkey) {
                    try {
                        const response = await fetch(`{{ url('ims/hotkeys') }}/${hotkey.id}/toggle`, {
                            method: 'PATCH',
                            headers: {
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        });
                        
                        if (!response.ok) {
                            const errorData = await response.json();
                            throw new Error(errorData.message || `HTTP error! status: ${response.status}`);
                        }
                        
                        const data = await response.json();
                        
                        if (data.success) {
                            hotkey.is_active = data.is_active;
                            this.showNotification(data.message, 'success');
                            this.initGlobalHotkeys(); // Refresh hotkeys
                        }
                    } catch (error) {
                        console.error('Error toggling hotkey:', error);
                        this.showNotification('Error toggling hotkey: ' + error.message, 'error');
                    }
                },

                async deleteHotkey(hotkey) {
                    // Show custom confirmation dialog
                    if (!await this.showConfirmDialog(
                        'Delete Hotkey',
                        `Are you sure you want to delete the hotkey "${hotkey.hotkey_combination}" for ${hotkey.action_name}?`,
                        'Delete',
                        'Cancel',
                        'danger'
                    )) return;
                    
                    try {
                        const response = await fetch(`{{ url('ims/hotkeys') }}/${hotkey.id}`, {
                            method: 'DELETE',
                            headers: {
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        });
                        
                        if (!response.ok) {
                            const errorData = await response.json();
                            throw new Error(errorData.message || `HTTP error! status: ${response.status}`);
                        }
                        
                        const data = await response.json();
                        
                        if (data.success) {
                            this.hotkeys = this.hotkeys.filter(h => h.id !== hotkey.id);
                            this.showNotification(data.message, 'success');
                            this.initGlobalHotkeys(); // Refresh hotkeys
                        }
                    } catch (error) {
                        console.error('Error deleting hotkey:', error);
                        this.showNotification('Error deleting hotkey: ' + error.message, 'error');
                    }
                },

                // Bulk operation methods
                toggleSelectAll(checked) {
                    if (checked) {
                        this.selectedHotkeys = [...this.filteredHotkeys.map(h => h.id)];
                    } else {
                        this.selectedHotkeys = [];
                    }
                },

                async bulkDelete() {
                    if (this.selectedHotkeys.length === 0) {
                        this.showNotification('No hotkeys selected', 'warning');
                        return;
                    }

                    // Show custom confirmation dialog
                    if (!await this.showConfirmDialog(
                        'Delete Selected Hotkeys',
                        `Are you sure you want to delete ${this.selectedHotkeys.length} selected hotkey(s)? This action cannot be undone.`,
                        'Delete',
                        'Cancel',
                        'danger'
                    )) return;
                    
                    try {
                        const response = await fetch('/ims/hotkeys/bulk/delete', {
                            method: 'DELETE',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'X-Requested-With': 'XMLHttpRequest'
                            },
                            body: JSON.stringify({
                                ids: this.selectedHotkeys
                            })
                        });
                        
                        if (!response.ok) {
                            const errorData = await response.json();
                            throw new Error(errorData.message || `HTTP error! status: ${response.status}`);
                        }
                        
                        const data = await response.json();
                        
                        if (data.success) {
                            // Remove deleted hotkeys from the list
                            this.hotkeys = this.hotkeys.filter(h => !this.selectedHotkeys.includes(h.id));
                            this.selectedHotkeys = [];
                            this.showNotification(data.message, 'success');
                            this.initGlobalHotkeys(); // Refresh hotkeys
                        }
                    } catch (error) {
                        console.error('Error deleting hotkeys:', error);
                        this.showNotification('Error deleting hotkeys: ' + error.message, 'error');
                    }
                },

                async bulkActivate() {
                    if (this.selectedHotkeys.length === 0) {
                        this.showNotification('No hotkeys selected', 'warning');
                        return;
                    }
                    
                    try {
                        const response = await fetch('/ims/hotkeys/bulk/activate', {
                            method: 'PATCH',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'X-Requested-With': 'XMLHttpRequest'
                            },
                            body: JSON.stringify({
                                ids: this.selectedHotkeys
                            })
                        });
                        
                        if (!response.ok) {
                            const errorData = await response.json();
                            throw new Error(errorData.message || `HTTP error! status: ${response.status}`);
                        }
                        
                        const data = await response.json();
                        
                        if (data.success) {
                            // Update hotkeys status
                            this.hotkeys = this.hotkeys.map(h => {
                                if (this.selectedHotkeys.includes(h.id)) {
                                    h.is_active = true;
                                }
                                return h;
                            });
                            this.selectedHotkeys = [];
                            this.showNotification(data.message, 'success');
                            this.initGlobalHotkeys(); // Refresh hotkeys
                        }
                    } catch (error) {
                        console.error('Error activating hotkeys:', error);
                        this.showNotification('Error activating hotkeys: ' + error.message, 'error');
                    }
                },

                async bulkDeactivate() {
                    if (this.selectedHotkeys.length === 0) {
                        this.showNotification('No hotkeys selected', 'warning');
                        return;
                    }
                    
                    try {
                        const response = await fetch('/ims/hotkeys/bulk/deactivate', {
                            method: 'PATCH',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'X-Requested-With': 'XMLHttpRequest'
                            },
                            body: JSON.stringify({
                                ids: this.selectedHotkeys
                            })
                        });
                        
                        if (!response.ok) {
                            const errorData = await response.json();
                            throw new Error(errorData.message || `HTTP error! status: ${response.status}`);
                        }
                        
                        const data = await response.json();
                        
                        if (data.success) {
                            // Update hotkeys status
                            this.hotkeys = this.hotkeys.map(h => {
                                if (this.selectedHotkeys.includes(h.id)) {
                                    h.is_active = false;
                                }
                                return h;
                            });
                            this.selectedHotkeys = [];
                            this.showNotification(data.message, 'success');
                            this.initGlobalHotkeys(); // Refresh hotkeys
                        }
                    } catch (error) {
                        console.error('Error deactivating hotkeys:', error);
                        this.showNotification('Error deactivating hotkeys: ' + error.message, 'error');
                    }
                },

                async initGlobalHotkeys() {
                    try {
                        const response = await fetch('{{ route("hotkeys.active") }}', {
                            headers: {
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        });
                        
                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }
                        
                        const activeHotkeys = await response.json();
                        
                        // Remove existing hotkey listeners
                        document.removeEventListener('keydown', this.globalHotkeyHandler);
                        
                        // Add new handler
                        this.activeHotkeys = activeHotkeys;
                        document.addEventListener('keydown', this.globalHotkeyHandler.bind(this));
                    } catch (error) {
                        console.error('Error loading active hotkeys:', error);
                    }
                },

                globalHotkeyHandler(event) {
                    // Don't trigger hotkeys when typing in inputs
                    if (['INPUT', 'TEXTAREA', 'SELECT'].includes(event.target.tagName)) {
                        return;
                    }
                    
                    const keys = [];
                    
                    if (event.ctrlKey) keys.push('Ctrl');
                    if (event.shiftKey) keys.push('Shift');
                    if (event.altKey) keys.push('Alt');
                    if (event.metaKey) keys.push('Meta');
                    
                    if (event.key && event.key.length === 1) {
                        keys.push(event.key.toUpperCase());
                    } else if (['Enter', 'Space', 'Tab', 'Escape'].includes(event.key)) {
                        keys.push(event.key);
                    }
                    
                    const combination = keys.join('+');
                    const hotkey = this.activeHotkeys[combination];
                    
                    if (hotkey) {
                        event.preventDefault();
                        
                        if (hotkey.action_type === 'navigate' && hotkey.action_url) {
                            window.location.href = hotkey.action_url;
                        } else if (hotkey.action_type === 'modal') {
                            // Handle modal opening
                            console.log('Open modal for:', combination);
                        }
                    }
                },

                showNotification(message, type = 'info') {
                    // Create notification element
                    const notification = document.createElement('div');
                    notification.className = `fixed top-4 right-4 z-50 max-w-sm w-full ${this.getNotificationColors(type)} shadow-lg rounded-lg border p-4 transform transition-all duration-500 translate-x-full`;
                    
                    notification.innerHTML = `
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <i class="fas ${this.getNotificationIcon(type)} text-lg"></i>
                            </div>
                            <div class="ml-3 w-0 flex-1 pt-0.5">
                                <p class="text-sm font-medium">${message}</p>
                            </div>
                            <div class="ml-4 flex-shrink-0 flex">
                                <button class="rounded-md inline-flex text-gray-400 hover:text-gray-500 focus:outline-none" onclick="this.parentNode.parentNode.parentNode.remove()">
                                    <span class="sr-only">Close</span>
                                    <i class="fas fa-times text-sm"></i>
                                </button>
                            </div>
                        </div>
                    `;
                    
                    document.body.appendChild(notification);
                    
                    // Animate in
                    setTimeout(() => {
                        notification.style.transform = 'translateX(0)';
                    }, 10);
                    
                    // Auto remove after 5 seconds
                    setTimeout(() => {
                        notification.style.transform = 'translateX(100%)';
                        setTimeout(() => {
                            if (notification.parentNode) {
                                notification.parentNode.removeChild(notification);
                            }
                        }, 300);
                    }, 5000);
                },

                getNotificationColors(type) {
                    const colors = {
                        success: 'bg-green-50 border-green-200 text-green-800',
                        error: 'bg-red-50 border-red-200 text-red-800',
                        warning: 'bg-yellow-50 border-yellow-200 text-yellow-800',
                        info: 'bg-blue-50 border-blue-200 text-blue-800'
                    };
                    return colors[type] || colors.info;
                },

                getNotificationIcon(type) {
                    const icons = {
                        success: 'fa-check-circle text-green-400',
                        error: 'fa-exclamation-circle text-red-400',
                        warning: 'fa-exclamation-triangle text-yellow-400',
                        info: 'fa-info-circle text-blue-400'
                    };
                    return icons[type] || icons.info;
                },

                async showConfirmDialog(title, message, confirmText, cancelText, type = 'info') {
                    return new Promise((resolve) => {
                        const modal = document.createElement('div');
                        modal.className = 'fixed inset-0 z-50 overflow-y-auto';
                        modal.innerHTML = `
                            <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" onclick="reject()"></div>
                                <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
                                <div class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
                                    <div class="flex items-center">
                                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full ${type === 'danger' ? 'bg-red-100' : 'bg-blue-100'} sm:mx-0 sm:h-10 sm:w-10">
                                            <i class="fas ${type === 'danger' ? 'fa-exclamation-triangle text-red-600' : 'fa-question-circle text-blue-600'}"></i>
                                        </div>
                                        <div class="ml-4 text-left">
                                            <h3 class="text-lg leading-6 font-medium text-gray-900">${title}</h3>
                                            <div class="mt-2">
                                                <p class="text-sm text-gray-500">${message}</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
                                        <button type="button" onclick="resolve(true)" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 ${type === 'danger' ? 'bg-red-600 hover:bg-red-700' : 'bg-blue-600 hover:bg-blue-700'} text-base font-medium text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                                            ${confirmText}
                                        </button>
                                        <button type="button" onclick="resolve(false)" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:w-auto sm:text-sm">
                                            ${cancelText}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        `;
                        
                        // Add resolve function to window temporarily
                        window.resolve = (result) => {
                            document.body.removeChild(modal);
                            delete window.resolve;
                            resolve(result);
                        };
                        
                        document.body.appendChild(modal);
                    });
                },

                showNotification(message, type = 'info') {
                    // Create notification element
                    const notification = document.createElement('div');
                    notification.className = `fixed top-4 right-4 z-50 px-6 py-4 rounded-lg shadow-lg text-white font-medium transform transition-all duration-300 translate-x-full max-w-sm`;
                    
                    // Set colors based on type
                    const colors = {
                        success: 'bg-green-600',
                        error: 'bg-red-600',
                        warning: 'bg-amber-600',
                        info: 'bg-blue-600'
                    };
                    
                    const icons = {
                        success: 'fas fa-check-circle',
                        error: 'fas fa-exclamation-circle',
                        warning: 'fas fa-exclamation-triangle',
                        info: 'fas fa-info-circle'
                    };
                    
                    notification.classList.add(colors[type]);
                    notification.innerHTML = `
                        <div class="flex items-start space-x-3">
                            <i class="${icons[type]} flex-shrink-0 mt-0.5"></i>
                            <div class="flex-1">
                                <p class="text-sm">${message}</p>
                            </div>
                            <button onclick="this.parentElement.parentElement.remove()" class="flex-shrink-0 ml-2 text-white hover:text-gray-200">
                                <i class="fas fa-times text-xs"></i>
                            </button>
                        </div>
                    `;
                    
                    // Add to page
                    document.body.appendChild(notification);
                    
                    // Animate in
                    setTimeout(() => {
                        notification.classList.remove('translate-x-full');
                    }, 100);
                    
                    // Remove after 5 seconds
                    setTimeout(() => {
                        if (notification.parentElement) {
                            notification.classList.add('translate-x-full');
                            setTimeout(() => {
                                if (notification.parentElement) {
                                    notification.remove();
                                }
                            }, 300);
                        }
                    }, 5000);
                }
            }
        }
    </script>
</x-app-layout>
