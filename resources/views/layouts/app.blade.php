<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? config('app.name', 'SKM&Co.') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased bg-gray-900 text-white">
    <div class="min-h-[calc(100vh-80px)] mt-20">
        @include('layouts.navigation')
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
                <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 mt-4" data-password-expiry-banner>
                    <div
                        class="relative rounded-md border p-4 flex items-start gap-4 {{ $mustChange || ($daysLeft !== null && $daysLeft < 0) ? 'bg-red-900/40 border-red-600 text-red-200' : 'bg-amber-900/40 border-amber-600 text-amber-200' }}">
                        <div class="shrink-0">
                            @if ($mustChange || ($daysLeft !== null && $daysLeft < 0))
                                <i class="fas fa-exclamation-triangle text-red-400"></i>
                            @else
                                <i class="fas fa-clock text-amber-400"></i>
                            @endif
                        </div>
                        <div class="flex-1 text-sm leading-5">
                            @if ($mustChange)
                                Your password must be changed before you can continue using the system.
                            @elseif($daysLeft !== null && $daysLeft < 0)
                                Your password expired {{ abs($daysLeft) }} day{{ abs($daysLeft) === 1 ? '' : 's' }} ago.
                                Please update it now.
                            @elseif($daysLeft !== null && $daysLeft === 0)
                                Your password expires today. Please update it.
                            @elseif($daysLeft !== null)
                                Your password will expire in <strong>{{ $daysLeft }}</strong>
                                day{{ $daysLeft === 1 ? '' : 's' }}. Update it now to avoid interruption.
                            @else
                                Please set your password now.
                            @endif
                        </div>
                        <div class="flex items-center gap-2">
                            <a href="{{ route('password.force.show') }}"
                                class="px-3 py-1.5 rounded text-xs font-semibold {{ $mustChange || ($daysLeft !== null && $daysLeft < 0) ? 'bg-red-600 hover:bg-red-500' : 'bg-amber-600 hover:bg-amber-500' }} text-white transition">Update
                                Password</a>
                            <button type="button" onclick="this.closest('[data-password-expiry-banner]').remove()"
                                class="text-xs text-gray-400 hover:text-gray-200" aria-label="Dismiss"
                                title="Dismiss"><i class="fas fa-times"></i></button>
                        </div>
                    </div>
                </div>
            @endif
        @endif

        <div id="message-alert"
            class="fixed inset-x-0 bottom-5 right-5 z-50 transition-all ease-in-out duration-300 message-alert">
            @if (session()->has('response'))
                <?php
                $message = session()->get('response') ?? [];
                $status = $message['status'];
                switch ($status) {
                    case 'success':
                        $status = 'green';
                        break;
                    case 'error':
                        $status = 'red';
                        break;
                    case 'warning':
                        $status = 'yellow';
                        break;
                    case 'info':
                        $status = 'blue';
                        break;
                    default:
                        $status = 'gray';
                        break;
                }
                ?>
                <div class="bg-{{ $status }}-100 border border-{{ $status }}-400 text-{{ $status }}-700 px-3 py-2 rounded relative w-72 ms-auto my-1 flex items-center"
                    role="alert">
                    <span class="block sm:inline">{{ $message['message'] }}</span>
                </div>
            @endif
            @if ($errors->any())
                @foreach ($errors->all() as $error)
                    <div class="bg-red-100 border border-red-400 text-red-700 px-3 py-2 rounded relative w-72 ms-auto my-1 flex items-center"
                        role="alert"><span class="block sm:inline text-sm">{{ $error }}</span></div>
                @endforeach
            @endif
            @if (session()->has('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-3 py-2 rounded relative w-72 ms-auto my-1 flex items-center"
                    role="alert"><span class="block sm:inline">{{ session('success') }}</span></div>
            @endif
            @if (session()->has('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-3 py-2 rounded relative w-72 ms-auto my-1 flex items-center"
                    role="alert"><span class="block sm:inline">{{ session('error') }}</span></div>
            @endif
        </div>

        @isset($header)
            <header class="bg-white shadow">
                <div class="flex items-center justify-between px-4 py-6 W-full sm:px-6 lg:px-8">{{ $header }}</div>
            </header>
        @endisset

        <main>
            {{ $slot }}
        </main>
    </div>

    <script>
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
                } catch (e) {}
            }

            function isFormDirty(form) {
                if (!formStates.has(form)) return false;
                return formStates.get(form) !== serializeForm(form);
            }

            function recalc() {
                globalDirty = Array.from(document.querySelectorAll(FORM_SELECTOR)).some(isFormDirty);
                window.onbeforeunload = globalDirty ? function(e) {
                    e.preventDefault();
                    e.returnValue = WARNING_MESSAGE;
                    return WARNING_MESSAGE;
                } : null;
            }

            function mark(e) {
                const f = e.target && e.target.closest(FORM_SELECTOR);
                if (!f) return;
                recalc();
            }

            function submit(e) {
                const f = e.target;
                if (!f.matches(FORM_SELECTOR)) return;
                formStates.set(f, serializeForm(f));
                recalc();
            }

            function link(e) {
                const a = e.target.closest('a[href]');
                if (!a) return;
                const h = a.getAttribute('href');
                if (!h || h.startsWith('#') || h.startsWith('javascript:')) return;
                if (a.target === '_blank' || a.hasAttribute('download')) return;
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
                document.addEventListener('input', mark, true);
                document.addEventListener('change', mark, true);
                document.addEventListener('submit', submit, true);
                document.addEventListener('click', link, true);
                window.addEventListener('beforeunload', function(e) {
                    if (!globalDirty) recalc();
                    if (globalDirty) {
                        e.preventDefault();
                        e.returnValue = WARNING_MESSAGE;
                        return WARNING_MESSAGE;
                    }
                });
                window.UnsavedChanges = {
                    refreshInitialStates() {
                        document.querySelectorAll(FORM_SELECTOR).forEach(initForm);
                        recalc();
                    },
                    clear() {
                        document.querySelectorAll(FORM_SELECTOR).forEach(initForm);
                        recalc();
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
</body>

</html>
