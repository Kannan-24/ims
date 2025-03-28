<x-app-layout>
    <div class="py-6 mt-20 ml-4 sm:ml-64">
        <div class="w-full mx-auto max-w-7xl sm:px-6 lg:px-8">
            <x-bread-crumb-navigation />

            <h2 class="text-3xl font-bold text-gray-200 mb-6">Create User</h2>

            <form action="{{ route('users.store') }}" method="POST">
                @csrf

                <div class="mb-6">
                    <label class="block text-gray-300 font-semibold mb-2">Name:</label>
                    <input type="text" name="name" id="name" class="w-full px-4 py-3 border border-gray-700 bg-gray-800 text-gray-300 rounded-lg shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500 transition" required>
                </div>

                <div class="mb-6">
                    <label class="block text-gray-300 font-semibold mb-2">Email:</label>
                    <input type="email" name="email" id="email" class="w-full px-4 py-3 border border-gray-700 bg-gray-800 text-gray-300 rounded-lg shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500 transition" required>
                </div>

                <div class="mb-6">
                    <label class="block text-gray-300 font-semibold mb-2">Role:</label>
                    <select name="role" id="role" class="w-full px-4 py-3 border border-gray-700 bg-gray-800 text-gray-300 rounded-lg shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500 transition" required>
                        <option value="">Select Role</option>
                        <option value="Admin">Admin</option>
                        <option value="Manager">Manager</option>
                        <option value="Employee">Employee</option>
                    </select>
                </div>

                <div class="mb-6">
                    <label class="block text-gray-300 font-semibold mb-2">Address:</label>
                    <input type="text" name="address" id="address" class="w-full px-4 py-3 border border-gray-700 bg-gray-800 text-gray-300 rounded-lg shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500 transition" required>
                </div>

                <div class="mb-6">
                    <label class="block text-gray-300 font-semibold mb-2">Blood Group:</label>
                    <select name="blood_group" id="blood_group" class="w-full px-4 py-3 border border-gray-700 bg-gray-800 text-gray-300 rounded-lg shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500 transition" required>
                        <option value="">Select Blood Group</option>
                        <option value="A+">A+</option>
                        <option value="A-">A-</option>
                        <option value="B+">B+</option>
                        <option value="B-">B-</option>
                        <option value="AB+">AB+</option>
                        <option value="AB-">AB-</option>
                        <option value="O+">O+</option>
                        <option value="O-">O-</option>
                    </select>
                </div>

                <div class="mb-6">
                    <label class="block text-gray-300 font-semibold mb-2">State:</label>
                    <input type="text" name="state" id="state" class="w-full px-4 py-3 border border-gray-700 bg-gray-800 text-gray-300 rounded-lg shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500 transition" required>
                </div>

                <div class="mb-6">
                    <label class="block text-gray-300 font-semibold mb-2">Gender:</label>
                    <select name="gender" id="gender" class="w-full px-4 py-3 border border-gray-700 bg-gray-800 text-gray-300 rounded-lg shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500 transition" required>
                        <option value="">Select Gender</option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                        <option value="Other">Other</option>
                    </select>
                </div>

                <div class="mb-6">
                    <label class="block text-gray-300 font-semibold mb-2">Date of Birth:</label>
                    <input type="date" name="dob" id="dob" class="w-full px-4 py-3 border border-gray-700 bg-gray-800 text-gray-300 rounded-lg shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500 transition" required>
                </div>

                <div class="mb-6">
                    <label class="block text-gray-300 font-semibold mb-2">Phone Number:</label>
                    <input type="text" name="phone" id="phone" class="w-full px-4 py-3 border border-gray-700 bg-gray-800 text-gray-300 rounded-lg shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500 transition" required>
                </div>

                <div class="mb-6">
                    <label class="block text-gray-300 font-semibold mb-2">Date of Joining:</label>
                    <input type="date" name="doj" id="doj" class="w-full px-4 py-3 border border-gray-700 bg-gray-800 text-gray-300 rounded-lg shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500 transition" required>
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="px-6 py-3 bg-blue-500 hover:bg-blue-600 text-white font-semibold rounded-lg shadow-md transition">
                        Create User
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
