<x-app-layout>
    <x-slot name="title">
        {{ __('Activity Log Details') }} - {{ config('app.name', 'ATMS') }}
    </x-slot>

    <div class="py-6 mt-20 ml-4 sm:ml-64">
        <div class="w-full mx-auto max-w-7xl sm:px-6 lg:px-8">
            <x-bread-crumb-navigation />

            <div class="p-8 bg-gray-800 border border-gray-700 rounded-lg shadow-lg relative">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-3xl font-bold text-gray-200">Activity Log Details</h2>
                    <a href="{{ route('activity-logs.index') }}"
                        class="px-4 py-2 text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 transition">
                        Back
                    </a>
                </div>

                <hr class="my-6 border-gray-600">

                <table class="w-auto text-gray-300 border-collapse">
                    <tr>
                        <td class="px-4 py-2 font-semibold">User Name</td>
                        <td class="px-4 py-2">{{ $log->user->name ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td class="px-4 py-2 font-semibold">User Type</td>
                        <td class="px-4 py-2">{{ $log->user_type ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td class="px-4 py-2 font-semibold">Module</td>
                        <td class="px-4 py-2">{{ $log->module ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td class="px-4 py-2 font-semibold">Action Type</td>
                        <td class="px-4 py-2">{{ $log->action_type }}</td>
                    </tr>
                    <tr>
                        <td class="px-4 py-2 font-semibold">Description</td>
                        <td class="px-4 py-2">{{ $log->description }}</td>
                    </tr>
                    <tr>
                        <td class="px-4 py-2 font-semibold">Date</td>
                        <td class="px-4 py-2">{{ $log->created_at }}</td>
                    </tr>
                    <tr>
                        <td class="px-4 py-2 font-semibold">IP Address</td>
                        <td class="px-4 py-2">{{ $log->ip_address }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
