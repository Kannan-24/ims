<x-app-layout>
    <div class="py-6 mt-20 ml-4 sm:ml-64">
        <div class="w-full mx-auto max-w-7xl sm:px-6 lg:px-8">
            <x-bread-crumb-navigation />

            <h2 class="text-3xl font-bold text-gray-200 mb-6">Edit User</h2>

            <form action="{{ route('users.update', $user->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-6">
                    <label class="block text-gray-300 font-semibold mb-2">Name:</label>
                    <input type="text" name="name" id="name" class="w-full px-4 py-3 border border-gray-700 bg-gray-800 text-gray-300 rounded-lg shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500 transition"
                        value="{{ $user->name }}" required>
                </div>

                <div class="mb-6">
                    <label class="block text-gray-300 font-semibold mb-2">Email:</label>
                    <input type="email" name="email" id="email" class="w-full px-4 py-3 border border-gray-700 bg-gray-800 text-gray-300 rounded-lg shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500 transition"
                        value="{{ $user->email }}" required>
                </div>

                <div class="mb-6">
                    <label class="block text-gray-300 font-semibold mb-2">Role:</label>
                    <select name="role_id" id="role_id" class="w-full px-4 py-3 border border-gray-700 bg-gray-800 text-gray-300 rounded-lg shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500 transition" required>
                        <option value="">Select Role</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->id }}" 
                                {{ (old('role_id') ?? ($user->getPrimaryRole() ? $user->getPrimaryRole()->id : '')) == $role->id ? 'selected' : '' }}>
                                {{ $role->name }}
                                @if($role->description)
                                    - {{ $role->description }}
                                @endif
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-6">
                    <label class="block text-gray-300 font-semibold mb-2">Address:</label>
                    <input type="text" name="address" id="address" class="w-full px-4 py-3 border border-gray-700 bg-gray-800 text-gray-300 rounded-lg shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500 transition"
                        value="{{ $user->address }}" required>
                </div>

                <div class="mb-6">
                    <label class="block text-gray-300 font-semibold mb-2">Blood Group:</label>
                    <select name="blood_group" id="blood_group" class="w-full px-4 py-3 border border-gray-700 bg-gray-800 text-gray-300 rounded-lg shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500 transition" required>
                        <option value="">Select Blood Group</option>
                        <option value="A+" {{ $user->blood_group == 'A+' ? 'selected' : '' }}>A+</option>
                        <option value="A-" {{ $user->blood_group == 'A-' ? 'selected' : '' }}>A-</option>
                        <option value="B+" {{ $user->blood_group == 'B+' ? 'selected' : '' }}>B+</option>
                        <option value="B-" {{ $user->blood_group == 'B-' ? 'selected' : '' }}>B-</option>
                        <option value="AB+" {{ $user->blood_group == 'AB+' ? 'selected' : '' }}>AB+</option>
                        <option value="AB-" {{ $user->blood_group == 'AB-' ? 'selected' : '' }}>AB-</option>
                        <option value="O+" {{ $user->blood_group == 'O+' ? 'selected' : '' }}>O+</option>
                        <option value="O-" {{ $user->blood_group == 'O-' ? 'selected' : '' }}>O-</option>
                    </select>
                </div>

                <div class="mb-6">
                    <label class="block text-gray-300 font-semibold mb-2">State:</label>
                    <input type="text" name="state" id="state" class="w-full px-4 py-3 border border-gray-700 bg-gray-800 text-gray-300 rounded-lg shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500 transition"
                        value="{{ $user->state }}" required>
                </div>

                <div class="mb-6">
                    <label class="block text-gray-300 font-semibold mb-2">Gender:</label>
                    <select name="gender" id="gender" class="w-full px-4 py-3 border border-gray-700 bg-gray-800 text-gray-300 rounded-lg shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500 transition" required>
                        <option value="">Select Gender</option>
                        <option value="Male" {{ $user->gender == 'Male' ? 'selected' : '' }}>Male</option>
                        <option value="Female" {{ $user->gender == 'Female' ? 'selected' : '' }}>Female</option>
                        <option value="Other" {{ $user->gender == 'Other' ? 'selected' : '' }}>Other</option>
                    </select>
                </div>

                <div class="mb-6">
                    <label class="block text-gray-300 font-semibold mb-2">Date of Birth:</label>
                    <input type="date" name="dob" id="dob" class="w-full px-4 py-3 border border-gray-700 bg-gray-800 text-gray-300 rounded-lg shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500 transition"
                        value="{{ $user->dob }}" required>
                </div>

                <div class="mb-6">
                    <label class="block text-gray-300 font-semibold mb-2">Phone Number:</label>
                    <input type="text" name="phone" id="phone" class="w-full px-4 py-3 border border-gray-700 bg-gray-800 text-gray-300 rounded-lg shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500 transition"
                        value="{{ $user->phone }}" required>
                </div>

                <div class="mb-6">
                    <label class="block text-gray-300 font-semibold mb-2">Date of Joining:</label>
                    <input type="date" name="doj" id="doj" class="w-full px-4 py-3 border border-gray-700 bg-gray-800 text-gray-300 rounded-lg shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500 transition"
                        value="{{ $user->doj }}" required>
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="px-6 py-3 bg-blue-500 hover:bg-blue-600 text-white font-semibold rounded-lg shadow-md transition">
                        Update User
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
