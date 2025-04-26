<x-app-layout>
    <x-slot name="title">
        {{ __('Edit Profile') }} - {{ config('app.name', 'IMS') }}
    </x-slot>

    <div class="py-6 mt-20 ml-4 sm:ml-64">
        <div class="w-full mx-auto max-w-7xl sm:px-6 lg:px-8">
            <x-bread-crumb-navigation />

            <div class="p-8 bg-gray-800 border border-gray-700 rounded-lg shadow-lg relative">
                <h2 class="text-3xl font-bold text-gray-200 mb-6">Edit Profile</h2>

                <form method="POST" action="{{ route('profile.update') }}">
                    @csrf
                    @method('PUT')

                    <div class="space-y-6 text-gray-300">
                        <div>
                            <label class="block mb-2 font-bold text-gray-200">Name:</label>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}"
                                class="w-full border-gray-600 bg-gray-700 text-gray-200 rounded-lg">
                        </div>

                        <div>
                            <label class="block mb-2 font-bold text-gray-200">Email:</label>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}"
                                class="w-full border-gray-600 bg-gray-700 text-gray-200 rounded-lg">
                        </div>

                        <div>
                            <label class="block mb-2 font-bold text-gray-200">Phone:</label>
                            <input type="text" name="phone" value="{{ old('phone', $user->phone) }}"
                                class="w-full border-gray-600 bg-gray-700 text-gray-200 rounded-lg">
                        </div>

                        <div>
                            <label class="block mb-2 font-bold text-gray-200">Address:</label>
                            <textarea name="address" class="w-full border-gray-600 bg-gray-700 text-gray-200 rounded-lg">{{ old('address', $user->address) }}</textarea>
                        </div>

                        <div>
                            <label class="block mb-2 font-bold text-gray-200">Blood Group:</label>
                            <select name="blood_group"
                                class="w-full border-gray-600 bg-gray-700 text-gray-200 rounded-lg">
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

                        <div>
                            <label class="block mb-2 font-bold text-gray-200">State:</label>
                            <select name="state" class="w-full border-gray-600 bg-gray-700 text-gray-200 rounded-lg">
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

                        <div>
                            <label class="block mb-2 font-bold text-gray-200">Gender:</label>
                            <select name="gender" class="w-full border-gray-600 bg-gray-700 text-gray-200 rounded-lg">
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

                        <div>
                            <label class="block mb-2 font-bold text-gray-200">Date of Birth:</label>
                            <input type="date" name="dob" value="{{ old('dob', $user->dob) }}"
                                class="w-full border-gray-600 bg-gray-700 text-gray-200 rounded-lg">
                        </div>

                        <div>
                            <label class="block mb-2 font-bold text-gray-200">Date of Joining:</label>
                            <input type="date" name="doj" value="{{ old('doj', $user->doj) }}"
                                class="w-full border-gray-600 bg-gray-700 text-gray-200 rounded-lg">
                        </div>

                        <div>
                            <label class="block mb-2 font-bold text-gray-200">Role:</label>
                            <select name="role" class="w-full border-gray-600 bg-gray-700 text-gray-200 rounded-lg">
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
                    </div>

                    <div class="flex justify-end mt-8">
                        <button type="submit"
                            class="px-4 py-2 text-white bg-blue-500 rounded-lg hover:bg-blue-600 transition">
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
