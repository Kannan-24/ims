<x-app-layout>
    <div class="py-6 mt-20 ml-4 sm:ml-64">
        <div class="w-full mx-auto max-w-7xl sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold text-gray-200 mb-6">Test Email Form</h2>

            @if (session('success'))
                <div class="bg-green-500 text-white p-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="bg-red-500 text-white p-3 rounded mb-4">
                    {{ session('error') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="bg-red-500 text-white p-3 rounded mb-4">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('emails.store') }}" method="POST" id="testForm">
                @csrf

                <div class="mb-6">
                    <label class="block text-gray-300 font-semibold mb-2">To:</label>
                    <input type="text" name="to" value="test@example.com"
                        class="w-full px-4 py-3 border border-gray-700 bg-gray-800 text-gray-300 rounded-lg" required>
                </div>

                <div class="mb-6">
                    <label class="block text-gray-300 font-semibold mb-2">Subject:</label>
                    <input type="text" name="subject" value="Test Email"
                        class="w-full px-4 py-3 border border-gray-700 bg-gray-800 text-gray-300 rounded-lg" required>
                </div>

                <div class="mb-6">
                    <label class="block text-gray-300 font-semibold mb-2">Body:</label>
                    <textarea name="body" rows="5"
                        class="w-full px-4 py-3 border border-gray-700 bg-gray-800 text-gray-300 rounded-lg">Test email content</textarea>
                </div>

                <div class="space-x-3">
                    <button type="submit" name="save_draft" value="1"
                        class="px-6 py-3 bg-yellow-500 hover:bg-yellow-600 text-white font-semibold rounded-lg">
                        Save as Draft
                    </button>
                    <button type="submit"
                        class="px-6 py-3 bg-blue-500 hover:bg-blue-600 text-white font-semibold rounded-lg">
                        Send Email
                    </button>
                </div>
            </form>

            <hr class="my-8 border-gray-600">

            <h3 class="text-xl font-bold text-gray-200 mb-4">Test Empty Body</h3>

            <form action="{{ route('emails.store') }}" method="POST" id="emptyTestForm">
                @csrf

                <div class="mb-6">
                    <label class="block text-gray-300 font-semibold mb-2">To:</label>
                    <input type="text" name="to" value="test@example.com"
                        class="w-full px-4 py-3 border border-gray-700 bg-gray-800 text-gray-300 rounded-lg" required>
                </div>

                <div class="mb-6">
                    <label class="block text-gray-300 font-semibold mb-2">Subject:</label>
                    <input type="text" name="subject" value="Empty Body Test"
                        class="w-full px-4 py-3 border border-gray-700 bg-gray-800 text-gray-300 rounded-lg" required>
                </div>

                <div class="mb-6">
                    <label class="block text-gray-300 font-semibold mb-2">Body (intentionally empty):</label>
                    <textarea name="body" rows="5"
                        class="w-full px-4 py-3 border border-gray-700 bg-gray-800 text-gray-300 rounded-lg"></textarea>
                </div>

                <div class="space-x-3">
                    <button type="submit" name="save_draft" value="1"
                        class="px-6 py-3 bg-yellow-500 hover:bg-yellow-600 text-white font-semibold rounded-lg">
                        Save Empty Draft (Should Work)
                    </button>
                    <button type="submit"
                        class="px-6 py-3 bg-red-500 hover:bg-red-600 text-white font-semibold rounded-lg">
                        Send Empty Email (Should Fail)
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
