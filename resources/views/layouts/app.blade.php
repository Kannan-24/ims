<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? config('app.name', 'SKM&Co.') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased bg-gray-50" 
      x-data="{ 
          sidebarOpen: false, 
          sidebarCollapsed: localStorage.getItem('sidebarCollapsed') === 'true', 
          sidebarHovering: false,
          darkMode: localStorage.getItem('darkMode') === 'true'
      }" 
      x-init="
          $watch('sidebarCollapsed', value => localStorage.setItem('sidebarCollapsed', value));
          $watch('darkMode', value => localStorage.setItem('darkMode', value));
      "
      :class="darkMode ? 'dark' : ''"
      @toggle-dark-mode.window="darkMode = !darkMode">
    
    <!-- Top Navigation -->
    @include('components.top-navigation')
    
    <!-- Sidebar -->
    @include('components.sidebar')
    
    <!-- Main Content -->
    <div class="transition-all duration-300 ease-in-out" 
         :class="[
             (sidebarCollapsed && !sidebarHovering) ? 'lg:ml-16' : 'lg:ml-64',
             $store.notifications && $store.notifications.panelOpen ? 'blur-sm' : ''
         ]">
        
        <!-- Password Expiry Banner -->
        @php
            $authUser = auth()->user();
            $routeName = optional(request()->route())->getName();
        @endphp
        @if ($authUser && $routeName !== 'password.force.show')
            @php
                $expiresAt = $authUser->password_expires_at;
                $mustChange = $authUser->must_change_password;
                $daysLeft = $expiresAt ? now()->diffInDays($expiresAt, false) : null;
                $reminderOffsets = collect(config('password_policy.reminder_offsets', []));
                $showBanner =
                    $mustChange ||
                    ($daysLeft !== null && ($daysLeft <= ($reminderOffsets->max() ?? 14) || $daysLeft < 0));
            @endphp
            @if ($showBanner)
                <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 mt-20" data-password-expiry-banner>
                    <div class="relative rounded-lg border p-4 flex items-start gap-4 shadow-sm {{ $mustChange || ($daysLeft !== null && $daysLeft < 0) ? 'bg-red-50 border-red-200 text-red-800' : 'bg-amber-50 border-amber-200 text-amber-800' }}">
                        <div class="shrink-0">
                            @if ($mustChange || ($daysLeft !== null && $daysLeft < 0))
                                <i class="fas fa-exclamation-triangle text-red-500"></i>
                            @else
                                <i class="fas fa-clock text-amber-500"></i>
                            @endif
                        </div>
                        <div class="flex-1 text-sm leading-6">
                            @if ($mustChange)
                                <strong>Password Change Required:</strong> Your password must be changed before you can continue using the system.
                            @elseif($daysLeft !== null && $daysLeft < 0)
                                <strong>Password Expired:</strong> Your password expired {{ abs($daysLeft) }} day{{ abs($daysLeft) === 1 ? '' : 's' }} ago. Please update it now.
                            @elseif($daysLeft !== null && $daysLeft === 0)
                                <strong>Password Expires Today:</strong> Your password expires today. Please update it now.
                            @elseif($daysLeft !== null)
                                <strong>Password Expiring Soon:</strong> Your password will expire in <strong>{{ $daysLeft }}</strong> day{{ $daysLeft === 1 ? '' : 's' }}. Update it now to avoid interruption.
                            @else
                                <strong>Password Required:</strong> Please set your password now.
                            @endif
                        </div>
                        <div class="flex items-center gap-3">
                            <a href="{{ route('password.force.show') }}"
                                class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-semibold text-white transition-colors {{ $mustChange || ($daysLeft !== null && $daysLeft < 0) ? 'bg-red-600 hover:bg-red-700' : 'bg-amber-600 hover:bg-amber-700' }}">
                                <i class="fas fa-key mr-2"></i>
                                Update Password
                            </a>
                            <button type="button" 
                                    onclick="this.closest('[data-password-expiry-banner]').remove()"
                                    class="p-2 text-gray-400 hover:text-gray-600 transition-colors" 
                                    aria-label="Dismiss"
                                    title="Dismiss">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                </div>
            @endif
        @endif

        <!-- Enhanced Flash Messages -->
        <div id="flash-messages" class="fixed top-20 right-4 z-50 space-y-3 max-w-sm" x-data="flashMessageManager()">
            @if (session()->has('response'))
                <?php
                $message = session()->get('response') ?? [];
                $status = $message['status'];
                $iconMap = [
                    'success' => 'fa-check-circle',
                    'error' => 'fa-exclamation-circle',
                    'warning' => 'fa-exclamation-triangle',
                    'info' => 'fa-info-circle'
                ];
                $colorMap = [
                    'success' => 'bg-green-50 border-green-200 text-green-800',
                    'error' => 'bg-red-50 border-red-200 text-red-800',
                    'warning' => 'bg-amber-50 border-amber-200 text-amber-800',
                    'info' => 'bg-blue-50 border-blue-200 text-blue-800'
                ];
                ?>
                <div class="flash-message {{ $colorMap[$status] ?? $colorMap['info'] }} border rounded-lg p-4 shadow-lg transform transition-all duration-300"
                     x-data="{ show: true }"
                     x-show="show"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 transform scale-95"
                     x-transition:enter-end="opacity-100 transform scale-100"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="opacity-100 transform scale-100"
                     x-transition:leave-end="opacity-0 transform scale-95">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <i class="fas {{ $iconMap[$status] ?? $iconMap['info'] }} text-lg"></i>
                        </div>
                        <div class="ml-3 flex-1">
                            <p class="text-sm font-medium">{{ $message['message'] }}</p>
                        </div>
                        <button @click="show = false" class="ml-3 text-lg hover:opacity-70 transition-opacity">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            @endif

            @if ($errors->any())
                @foreach ($errors->all() as $error)
                    <div class="flash-message bg-red-50 border-red-200 text-red-800 border rounded-lg p-4 shadow-lg transform transition-all duration-300"
                         x-data="{ show: true }"
                         x-show="show"
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 transform scale-95"
                         x-transition:enter-end="opacity-100 transform scale-100"
                         x-transition:leave="transition ease-in duration-200"
                         x-transition:leave-start="opacity-100 transform scale-100"
                         x-transition:leave-end="opacity-0 transform scale-95">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <i class="fas fa-exclamation-circle text-lg"></i>
                            </div>
                            <div class="ml-3 flex-1">
                                <p class="text-sm font-medium">{{ $error }}</p>
                            </div>
                            <button @click="show = false" class="ml-3 text-lg hover:opacity-70 transition-opacity">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                @endforeach
            @endif

            @if (session()->has('success'))
                <div class="flash-message bg-green-50 border-green-200 text-green-800 border rounded-lg p-4 shadow-lg transform transition-all duration-300"
                     x-data="{ show: true }"
                     x-show="show"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 transform scale-95"
                     x-transition:enter-end="opacity-100 transform scale-100"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="opacity-100 transform scale-100"
                     x-transition:leave-end="opacity-0 transform scale-95">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <i class="fas fa-check-circle text-lg"></i>
                        </div>
                        <div class="ml-3 flex-1">
                            <p class="text-sm font-medium">{{ session('success') }}</p>
                        </div>
                        <button @click="show = false" class="ml-3 text-lg hover:opacity-70 transition-opacity">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            @endif

            @if (session()->has('error'))
                <div class="flash-message bg-red-50 border-red-200 text-red-800 border rounded-lg p-4 shadow-lg transform transition-all duration-300"
                     x-data="{ show: true }"
                     x-show="show"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 transform scale-95"
                     x-transition:enter-end="opacity-100 transform scale-100"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="opacity-100 transform scale-100"
                     x-transition:leave-end="opacity-0 transform scale-95">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-circle text-lg"></i>
                        </div>
                        <div class="ml-3 flex-1">
                            <p class="text-sm font-medium">{{ session('error') }}</p>
                        </div>
                        <button @click="show = false" class="ml-3 text-lg hover:opacity-70 transition-opacity">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            @endif
        </div>

        @isset($header)
            <header class="bg-white shadow-sm mt-16 border-b border-gray-200">
                <div class="flex items-center justify-between px-4 py-6 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
            <main>
                {{ $slot }}
            </main>
        @else
            <main class="pt-16">
                {{ $slot }}
            </main>
        @endisset
    </div>

    <!-- Global Search Modal -->
    <div x-data="{ searchOpen: false }" 
         @keydown.ctrl.k.window.prevent="searchOpen = true"
         @keydown.escape.window="searchOpen = false">
        <div x-show="searchOpen" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-50 bg-black bg-opacity-50 flex items-start justify-center pt-20">
            <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full mx-4 max-h-96 overflow-hidden"
                 @click.away="searchOpen = false">
                <div class="p-4 border-b border-gray-200">
                    <div class="flex items-center space-x-3">
                        <i class="fas fa-search text-gray-400"></i>
                        <input type="text" 
                               placeholder="Search pages, features, or content..."
                               class="flex-1 outline-none text-lg"
                               x-ref="searchInput"
                               @keydown.escape="searchOpen = false">
                        <span class="text-xs text-gray-400 bg-gray-100 px-2 py-1 rounded">ESC</span>
                    </div>
                </div>
                <div class="p-4 text-center text-gray-500">
                    <p>Start typing to search...</p>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript Enhancements -->
    <script>
        // Flash Message Manager
        function flashMessageManager() {
            return {
                init() {
                    // Auto-hide flash messages after 5 seconds
                    setTimeout(() => {
                        this.$el.querySelectorAll('.flash-message').forEach(message => {
                            const alpineData = Alpine.$data(message);
                            if (alpineData && alpineData.show) {
                                alpineData.show = false;
                            }
                        });
                    }, 5000);
                }
            }
        }

        // Enhanced Unsaved Changes Warning
        (function() {
            const WARNING_MESSAGE = 'You have unsaved changes. Are you sure you want to leave this page?';
            const FORM_SELECTOR = 'form:not([data-no-unsaved-warning])';
            const formStates = new WeakMap();
            let globalDirty = false;

            function serializeForm(form) {
                const data = [];
                Array.from(form.elements).forEach(el => {
                    if (!el.name) return;
                    if (el.type === 'checkbox' || el.type === 'radio') {
                        data.push([el.name, el.checked]);
                    } else if (el.type === 'file') {
                        data.push([el.name, el.files && el.files.length]);
                    } else {
                        data.push([el.name, el.value]);
                    }
                });
                return JSON.stringify(data);
            }

            function initForm(form) {
                try {
                    formStates.set(form, serializeForm(form));
                } catch (e) {
                    console.warn('Failed to initialize form state:', e);
                }
            }

            function isFormDirty(form) {
                if (!formStates.has(form)) return false;
                return formStates.get(form) !== serializeForm(form);
            }

            function updateGlobalState() {
                globalDirty = Array.from(document.querySelectorAll(FORM_SELECTOR)).some(isFormDirty);
                window.onbeforeunload = globalDirty ? function(e) {
                    e.preventDefault();
                    e.returnValue = WARNING_MESSAGE;
                    return WARNING_MESSAGE;
                } : null;
            }

            function handleFormChange(e) {
                const form = e.target && e.target.closest(FORM_SELECTOR);
                if (!form) return;
                updateGlobalState();
            }

            function handleFormSubmit(e) {
                const form = e.target;
                if (!form.matches(FORM_SELECTOR)) return;
                formStates.set(form, serializeForm(form));
                updateGlobalState();
            }

            function handleLinkClick(e) {
                const link = e.target.closest('a[href]');
                if (!link) return;
                
                const href = link.getAttribute('href');
                if (!href || href.startsWith('#') || href.startsWith('javascript:')) return;
                if (link.target === '_blank' || link.hasAttribute('download')) return;
                
                if (globalDirty && !confirm(WARNING_MESSAGE)) {
                    e.preventDefault();
                    e.stopImmediatePropagation();
                } else if (globalDirty) {
                    globalDirty = false;
                    window.onbeforeunload = null;
                }
            }

            function init() {
                document.querySelectorAll(FORM_SELECTOR).forEach(initForm);
                document.addEventListener('input', handleFormChange, true);
                document.addEventListener('change', handleFormChange, true);
                document.addEventListener('submit', handleFormSubmit, true);
                document.addEventListener('click', handleLinkClick, true);
                
                // Global utilities
                window.UnsavedChanges = {
                    refreshInitialStates() {
                        document.querySelectorAll(FORM_SELECTOR).forEach(initForm);
                        updateGlobalState();
                    },
                    clear() {
                        document.querySelectorAll(FORM_SELECTOR).forEach(initForm);
                        updateGlobalState();
                    },
                    isDirty() {
                        return globalDirty;
                    }
                };
            }

            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', init);
            } else {
                init();
            }
        })();
    </script>

    <!-- Enhanced Global Hotkey Manager -->
    <script>
        (function() {
            let activeHotkeys = {};
            let hotkeyManagerReady = false;

            async function loadActiveHotkeys() {
                try {
                    const response = await fetch('/ims/hotkeys/active', {
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    });
                    
                    if (response.ok) {
                        activeHotkeys = await response.json();
                        console.log('Loaded active hotkeys:', Object.keys(activeHotkeys));
                        hotkeyManagerReady = true;
                    }
                } catch (error) {
                    console.warn('Could not load hotkeys:', error.message);
                }
            }

            function handleGlobalHotkeys(event) {
                if (!hotkeyManagerReady) return;
                
                const activeElement = document.activeElement;
                const isInputFocused = ['INPUT', 'TEXTAREA', 'SELECT'].includes(activeElement.tagName) || 
                                     activeElement.contentEditable === 'true';
                
                if (isInputFocused && !(event.ctrlKey && event.key === 'k')) return;

                const keys = [];
                
                if (event.ctrlKey) keys.push('Ctrl');
                if (event.shiftKey) keys.push('Shift');
                if (event.altKey) keys.push('Alt');
                if (event.metaKey) keys.push('Meta');
                
                if (event.key && event.key.length === 1) {
                    keys.push(event.key.toUpperCase());
                } else if (['Enter', 'Space', 'Tab', 'Escape'].includes(event.key)) {
                    keys.push(event.key);
                } else if (event.key === ',') {
                    keys.push('Comma');
                }
                
                const combination = keys.join('+');
                const hotkey = activeHotkeys[combination];
                
                if (hotkey) {
                    event.preventDefault();
                    event.stopPropagation();
                    
                    console.log('Triggered hotkey:', combination, hotkey);
                    
                    if (hotkey.action_type === 'navigate' && hotkey.action_url && hotkey.action_url !== '#') {
                        window.location.href = hotkey.action_url;
                    } else if (hotkey.action_type === 'modal') {
                        if (combination === 'Ctrl+K') {
                            Alpine.store('globalSearch').open();
                        }
                    } else if (hotkey.action_type === 'function') {
                        executeHotkeyFunction(hotkey.action_url);
                    }
                }
            }

            function executeHotkeyFunction(actionUrl) {
                if (actionUrl === '/logout') {
                    if (confirm('Are you sure you want to logout?')) {
                        const form = document.createElement('form');
                        form.method = 'POST';
                        form.action = '/logout';
                        
                        const token = document.createElement('input');
                        token.type = 'hidden';
                        token.name = '_token';
                        token.value = document.querySelector('meta[name="csrf-token"]').content;
                        form.appendChild(token);
                        
                        document.body.appendChild(form);
                        form.submit();
                    }
                }
            }

            function initHotkeyManager() {
                loadActiveHotkeys();
                document.addEventListener('keydown', handleGlobalHotkeys);
                
                document.addEventListener('visibilitychange', function() {
                    if (!document.hidden) {
                        loadActiveHotkeys();
                    }
                });

                window.refreshHotkeys = loadActiveHotkeys;
            }

            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', initHotkeyManager);
            } else {
                initHotkeyManager();
            }
        })();
    </script>
    
    <!-- Additional Scripts -->
    @stack('scripts')
    
    <!-- Fixed Bottom-Right Hotkey Indicator -->
    @include('components.hotkey-indicator')

    <!-- Page Loading Indicator -->
    <div id="page-loading" class="fixed top-0 left-0 w-full h-1 bg-blue-600 transform scale-x-0 transition-transform duration-300 z-50"></div>
    
    <script>
        // Page loading indicator
        document.addEventListener('DOMContentLoaded', function() {
            const loadingBar = document.getElementById('page-loading');
            
            // Show loading bar on navigation
            document.addEventListener('click', function(e) {
                const link = e.target.closest('a[href]');
                if (link && !link.target && !link.href.startsWith('#') && !link.href.startsWith('javascript:')) {
                    loadingBar.style.transform = 'scaleX(0.3)';
                }
            });
            
            // Show loading bar on form submission
            document.addEventListener('submit', function(e) {
                loadingBar.style.transform = 'scaleX(0.6)';
            });
            
            // Complete loading bar
            window.addEventListener('load', function() {
                loadingBar.style.transform = 'scaleX(1)';
                setTimeout(() => {
                    loadingBar.style.opacity = '0';
                }, 200);
            });
        });
    </script>
</body>

</html>