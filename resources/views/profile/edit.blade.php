<x-app-layout>
    <div class="py-6 mt-20 ml-4 sm:ml-64">
        <div class="w-full max-w-4xl px-6 mx-auto">
            <x-bread-crumb-navigation />

            <!-- Edit Profile Section -->
            <div class="p-8 bg-white border border-gray-200 rounded-lg shadow-lg">

                <form method="POST" action="{{ route('profile.update') }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label class="block mb-2 font-bold">Name:</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}"
                            class="w-full border rounded-lg">
                    </div>

                    <div class="mb-4">
                        <label class="block mb-2 font-bold">Email:</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}"
                            class="w-full border rounded-lg">
                    </div>

                    <div class="mb-4">
                        <label class="block mb-2 font-bold">Phone:</label>
                        <input type="text" name="phone" value="{{ old('phone', $user->phone) }}"
                            class="w-full border rounded-lg">
                    </div>

                    <div class="mb-4">
                        <label class="block mb-2 font-bold">Address:</label>
                        <textarea name="address" class="w-full border rounded-lg">{{ old('address', $user->address) }}</textarea>
                    </div>

                    <div class="mb-4">
                        <label class="block mb-2 font-bold">Blood Group:</label>
                        <select name="blood_group" class="w-full border rounded-lg">
                            <option value="">Select Blood Group</option>
                            @php
                                $bloodGroups = ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-', 'A1+'];
                            @endphp
                            @foreach ($bloodGroups as $group)
                                <option value="{{ $group }}"
                                    {{ old('blood_group', $user->blood_group) == $group ? 'selected' : '' }}>
                                    {{ $group }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block mb-2 font-bold">State:</label>
                        <select name="state" class="w-full border rounded-lg">
                            <option value="">Select State</option>
                            @php
                                $states = [
                                    'Tamil Nadu',
                                    'Andhra Pradesh',
                                    'Arunachal Pradesh',
                                    'Assam',
                                    'Bihar',
                                    'Chhattisgarh',
                                    'Goa',
                                    'Gujarat',
                                    'Haryana',
                                    'Himachal Pradesh',
                                    'Jharkhand',
                                    'Karnataka',
                                    'Kerala',
                                    'Madhya Pradesh',
                                    'Maharashtra',
                                    'Manipur',
                                    'Meghalaya',
                                    'Mizoram',
                                    'Nagaland',
                                    'Odisha',
                                    'Punjab',
                                    'Rajasthan',
                                    'Sikkim',
                                    'Telangana',
                                    'Tripura',
                                    'Uttar Pradesh',
                                    'Uttarakhand',
                                    'West Bengal',
                                    'Andaman and Nicobar Islands',
                                    'Chandigarh',
                                    'Dadra and Nagar Haveli',
                                    'Daman and Diu',
                                    'Lakshadweep',
                                    'Delhi',
                                    'Puducherry',
                                ];
                            @endphp
                            @foreach ($states as $state)
                                <option value="{{ $state }}"
                                    {{ old('state', $user->state) == $state ? 'selected' : '' }}>
                                    {{ $state }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block mb-2 font-bold">Gender:</label>
                        <select name="gender" class="w-full border rounded-lg">
                            <option value="">Select Gender</option>
                            @php
                                $genders = ['Male', 'Female', 'Other'];
                            @endphp
                            @foreach ($genders as $gender)
                                <option value="{{ $gender }}"
                                    {{ old('gender', $user->gender) == $gender ? 'selected' : '' }}>
                                    {{ $gender }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block mb-2 font-bold">Date of Birth:</label>
                        <input type="date" name="dob" value="{{ old('dob', $user->dob) }}"
                            class="w-full border rounded-lg">
                    </div>

                    <div class="mb-4">
                        <label class="block mb-2 font-bold">Date of Joining:</label>
                        <input type="date" name="doj" value="{{ old('doj', $user->doj) }}"
                            class="w-full border rounded-lg">
                    </div>

                    <div class="mb-4">
                        <label class="block mb-2 font-bold">Designation:</label>
                        <input type="text" name="designation" value="{{ old('designation', $user->designation) }}"
                            class="w-full border rounded-lg">
                    </div>

                    <div class="mb-4">
                        <label class="block mb-2 font-bold">Role:</label>
                        <select name="role" class="w-full border rounded-lg">
                            <option value="">Select Role</option>
                            @php
                                $roles = ['Admin', 'Employee'];
                            @endphp
                            @foreach ($roles as $role)
                                <option value="{{ $role }}"
                                    {{ old('role', $user->role) == $role ? 'selected' : '' }}>
                                    {{ $role }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Submit Button at the End -->
                    <div class="flex justify-end">
                        <button type="submit" class="px-4 py-2 text-white bg-blue-500 rounded-lg hover:bg-blue-600">
                            Save Changes
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</x-app-layout>
