<x-app-layout>
    <x-slot name="title">
        {{ __('User Details') }} - {{ config('app.name', 'ATMS') }}
    </x-slot>

    <!-- Main Content Section -->
    <div class="py-6 mt-20 ml-4 sm:ml-64">
        <div class="w-full max-w-4xl px-6 mx-auto">
            <!-- Breadcrumb Navigation -->
            <x-bread-crumb-navigation />

            <!-- User Details Container -->
            <div class="p-8 bg-white border border-gray-200 rounded-lg shadow-lg relative">
                <!-- Header Section -->
                <div class="flex justify-between items-center">
                    <h2 class="text-xl font-bold text-gray-700">User Details</h2>
                    <div class="flex gap-2">
                        <a href="{{ route('users.edit', $user->id) }}" class="flex items-center px-4 py-2 text-white bg-green-500 rounded-lg hover:bg-green-600 transition">
                            Edit
                        </a>
                        <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="flex items-center px-4 py-2 text-white bg-red-500 rounded-lg hover:bg-red-600 transition">
                                Delete
                            </button>
                        </form>
                    </div>
                </div>

                <hr class="my-4 border-gray-300">

                <!-- User Info -->
                <div class="space-y-2 text-gray-600">
                    <p><strong>Employee ID:</strong> {{ $user->employee_id }}</p>
                    <p><strong>User Name:</strong> {{ $user->name }}</p>
                    <p><strong>Role:</strong> {{ $user->role }}</p>
                    <p><strong>Email:</strong> {{ $user->email }}</p>
                    <p><strong>Phone:</strong> {{ $user->phone }}</p>
                    <p><strong>Date of Joining:</strong> {{ $user->doj }}</p>
                    <p><strong>Address:</strong> {{ $user->address }} -  {{ $user->state }}</p>
                    <p><strong>Date of Birth:</strong> {{ $user->dob }}</p>
                    <p><strong>Gender:</strong> {{ $user->gender }}</p>
                    <p><strong>Blood Group:</strong> {{ $user->blood_group }}</p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
