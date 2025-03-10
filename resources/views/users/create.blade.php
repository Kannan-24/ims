<x-app-layout>
    <div class="py-6 mt-20 ml-4 sm:ml-64">
        <div class="w-full max-w-4xl px-6 mx-auto">
            <x-bread-crumb-navigation />

            <!-- User Registration Form -->
            <div class="p-8 bg-white border border-gray-200 rounded-lg shadow-lg">
                <form action="{{ route('users.store') }}" method="POST">
                    @csrf

                    <div class="mb-4">
                        <label class="block mb-2 font-bold">Name:</label>
                        <input type="text" name="name" id="name" class="w-full px-4 py-2 border rounded-lg" required>
                    </div>

                    <div class="mb-4">
                        <label class="block mb-2 font-bold">Email:</label>
                        <input type="email" name="email" id="email" class="w-full px-4 py-2 border rounded-lg" required>
                    </div>
                    
                    <div class="mb-4">
                        <label class="block mb-2 font-bold">Role:</label>
                        <select name="role" id="role" class="w-full px-4 py-2 border rounded-lg" required>
                            <option value="">Select Role</option>
                            <option value="Admin">Admin</option>
                            <option value="Manager">Manager</option>
                            <option value="Employee">Employee</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block mb-2 font-bold">Address:</label>
                        <input type="text" name="address" id="address" class="w-full px-4 py-2 border rounded-lg" required>
                    </div>
                    <div class="mb-4">
                        <label class="block mb-2 font-bold">Blood Group:</label>
                        <select name="blood_group" id="blood_group" class="w-full px-4 py-2 border rounded-lg" required>
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

                    <div class="mb-4">
                        <label class="block mb-2 font-bold">State:</label>
                        <input type="text" name="state" id="state" class="w-full px-4 py-2 border rounded-lg" required>
                    </div>

                    <div class="mb-4">
                        <label class="block mb-2 font-bold">Gender:</label>
                        <select name="gender" id="gender" class="w-full px-4 py-2 border rounded-lg" required>
                            <option value="">Select Gender</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block mb-2 font-bold">Date of Birth:</label>
                        <input type="date" name="dob" id="dob" class="w-full px-4 py-2 border rounded-lg" required>
                    </div>

                    <div class="mb-4">
                        <label class="block mb-2 font-bold">Phone Number:</label>
                        <input type="text" name="phone" id="phone" class="w-full px-4 py-2 border rounded-lg" required>
                    </div>

                    <div class="mb-4">
                        <label class="block mb-2 font-bold">Date of Joining:</label>
                        <input type="date" name="doj" id="doj" class="w-full px-4 py-2 border rounded-lg" required>
                    </div>


                    <div class="flex justify-end">
                        <button type="submit" class="px-4 py-2 text-white bg-blue-500 rounded-lg hover:bg-blue-600">
                            Create User
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>