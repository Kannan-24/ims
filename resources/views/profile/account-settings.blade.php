<x-app-layout>
    <div class="py-6 mt-20 ml-4 sm:ml-64">
        <div class="w-full mx-auto max-w-7xl sm:px-6 lg:px-8">
            <x-bread-crumb-navigation />

            @if(!$confirmed)
                <div class="max-w-lg mx-auto bg-gray-900 border border-gray-700 p-8 rounded-xl shadow-2xl">
                    <h2 class="text-xl font-semibold mb-4">Confirm Access</h2>
                    <p class="text-sm text-gray-400 mb-6">For security, please re-enter your password to manage account settings.</p>
                    @if(session('error'))<p class="text-red-400 text-sm mb-4">{{ session('error') }}</p>@endif
                    <form method="POST" action="{{ route('account.settings.confirm') }}" class="space-y-5">
                        @csrf
                        <div>
                            <label class="block text-sm text-gray-300 mb-1">Password</label>
                            <input type="password" name="current_password" required autofocus class="w-full bg-gray-800 border border-gray-700 rounded p-3 text-white focus:outline-none focus:ring-2 focus:ring-blue-600" />
                        </div>
                        <button class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded font-semibold text-sm">Confirm</button>
                    </form>
                </div>
            @else
                <!-- Update Password Form -->
                <div class="p-8 mb-6 bg-gray-800 border border-gray-700 rounded-lg shadow-lg">
                    <div class="max-w-xl">
                        @include('profile.partials.update-password-form')
                    </div>
                </div>

                <!-- Two-Factor Settings -->
                <div class="p-8 mb-6 bg-gray-800 border border-gray-700 rounded-lg shadow-lg">
                    <div class="max-w-xl">
                        @php($secret = $user->two_factor_secret)
                        @include('profile.partials.two-factor-settings')
                    </div>
                </div>

                <!-- Active Sessions -->
                <div class="p-8 mb-6 bg-gray-800 border border-gray-700 rounded-lg shadow-lg">
                    <div class="max-w-3xl">
                        <h2 class="text-xl font-semibold mb-4">Active Sessions</h2>
                        <p class="text-sm text-gray-400 mb-4">These are the devices currently logged into your account.</p>
                        <table class="w-full text-sm text-left">
                            <thead class="text-gray-300 border-b border-gray-700">
                                <tr>
                                    <th class="py-2">Device / Agent</th>
                                    <th class="py-2">IP</th>
                                    <th class="py-2">Last Activity</th>
                                    <th class="py-2 text-right">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($sessions as $s)
                                    <tr class="border-b border-gray-800">
                                        <td class="py-2 pr-4 align-top">
                                            <div class="font-medium {{ $s['is_current'] ? 'text-green-400' : 'text-white' }}">
                                                {{ $s['is_current'] ? 'This Device' : 'Other Device' }}
                                            </div>
                                            <div class="text-xs text-gray-400 break-all max-w-xs">{{ Str::limit($s['user_agent'] ?? 'Unknown',80) }}</div>
                                        </td>
                                        <td class="py-2 pr-4 align-top">{{ $s['ip'] ?? '—' }}</td>
                                        <td class="py-2 pr-4 align-top">{{ $s['last_activity']->diffForHumans() }}</td>
                                        <td class="py-2 text-right align-top">
                                            @if(!$s['is_current'])
                                                <form method="POST" action="{{ route('account.settings.sessions.destroy',$s['id']) }}" onsubmit="return confirm('Log out this session?')" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="text-red-400 hover:text-red-300 text-xs">Log out</button>
                                                </form>
                                            @else
                                                <span class="text-xs text-green-500">Active</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="4" class="py-3 text-center text-gray-400">No active sessions.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                        <div class="mt-4">
                            <form method="POST" action="{{ route('account.settings.sessions.destroy.others') }}" onsubmit="return confirm('Log out all other sessions?')">
                                @csrf
                                @method('DELETE')
                                <button class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded text-xs">Log out other sessions</button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Delete User Form -->
                <div class="p-8 bg-gray-800 border border-gray-700 rounded-lg shadow-lg">
                    <div class="max-w-xl">
                        @include('profile.partials.delete-user-form')
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
