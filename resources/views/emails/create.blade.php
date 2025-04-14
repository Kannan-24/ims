<x-app-layout>
    <div class="py-6 mt-20 ml-4 sm:ml-64">
        <div class="w-full mx-auto max-w-7xl sm:px-6 lg:px-8">
            <x-bread-crumb-navigation />

            <h2 class="text-3xl font-bold text-gray-200 mb-6">Compose Email</h2>

            @if (session('success'))
                <div class="bg-green-500 text-white p-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('emails.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="mb-6">
                    <label class="block text-gray-300 font-semibold mb-2">To:</label>
                    <input type="text" name="to" id="to"
                        class="w-full px-4 py-3 border border-gray-700 bg-gray-800 text-gray-300 rounded-lg shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500 transition"
                        placeholder="Add recipient email addresses" required>
                </div>

                <div class="mb-6">
                    <label class="block text-gray-300 font-semibold mb-2">CC:</label>
                    <input type="text" name="cc" id="cc"
                        class="w-full px-4 py-3 border border-gray-700 bg-gray-800 text-gray-300 rounded-lg shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500 transition"
                        placeholder="Add CC email addresses">
                </div>

                <div class="mb-6">
                    <label class="block text-gray-300 font-semibold mb-2">BCC:</label>
                    <input type="text" name="bcc" id="bcc"
                        class="w-full px-4 py-3 border border-gray-700 bg-gray-800 text-gray-300 rounded-lg shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500 transition"
                        placeholder="Add BCC email addresses">
                </div>

                <div class="mb-6">
                    <label class="block text-gray-300 font-semibold mb-2">Subject:</label>
                    <input type="text" name="subject" id="subject"
                        class="w-full px-4 py-3 border border-gray-700 bg-gray-800 text-gray-300 rounded-lg shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500 transition"
                        placeholder="Enter email subject" required>
                </div>

                <div class="mb-6">
                    <label class="block text-gray-300 font-semibold mb-2">Body:</label>
                    <textarea name="body" id="body"
                        class="w-full px-4 py-3 border border-gray-700 bg-gray-800 text-gray-300 rounded-lg shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500 transition"
                        placeholder="Write your email message here" required>
<p>Dear Sir,</p>
<p>Good afternoon,</p>
<p>As discussed, please find the attached quotation for your requirements.</p>
<p>We kindly request you to confirm your valuable order with us at your earliest convenience.</p>
<p>We assure you of our best service and support at all times.</p>
<p>Thank you and regards,</p>
<p>R. Radhika<br>Partner<br>SKM and Company<br>8870820449<br>skmandcompany@yahoo.in</p>
                    </textarea>
                </div>

                <div class="mb-6">
                    <label class="block text-gray-300 font-semibold mb-2">Attachments:</label>
                    <input type="file" name="attachments[]" id="attachments"
                        class="w-full px-4 py-3 border border-gray-700 bg-gray-800 text-gray-300 rounded-lg shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500 transition"
                        multiple>
                </div>

                <div class="flex justify-end">
                    <button type="submit"
                        class="px-6 py-3 bg-blue-500 hover:bg-blue-600 text-white font-semibold rounded-lg shadow-md transition">
                        Send Email
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
<style>
    textarea#body {
        min-height: 300px;
        /* Set a minimum height for the textarea */
        resize: vertical;
        /* Allow vertical resizing only */
    }
</style>
