<x-app-layout>
    <div class="py-6 mt-20 ml-4 sm:ml-64">
        <div class="w-full mx-auto max-w-7xl sm:px-6 lg:px-8">
            <x-bread-crumb-navigation />

            <div class="flex justify-between items-center mb-6">
                <h2 class="text-3xl font-bold text-gray-200">Email Drafts</h2>
                <a href="{{ route('emails.create') }}"
                    class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded font-semibold">
                    <i class="fas fa-plus mr-2"></i>Compose New Email
                </a>
            </div>

            @if (session('success'))
                <div class="bg-green-500 text-white p-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            <div class="overflow-hidden bg-gray-800 rounded-lg shadow-xl">
                <div class="p-4 overflow-x-auto">
                    @if ($emails->isEmpty())
                        <div class="text-center text-gray-300 py-8">
                            <i class="fas fa-inbox text-4xl mb-4"></i>
                            <p>{{ __('No draft emails found.') }}</p>
                            <a href="{{ route('emails.create') }}"
                                class="mt-4 inline-block px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded">
                                Create Your First Draft
                            </a>
                        </div>
                    @else
                        <table class="min-w-full text-left border-collapse table-auto">
                            <thead>
                                <tr class="text-sm text-gray-300 bg-gray-700 uppercase tracking-wider">
                                    <th class="px-6 py-4 border-b-2 border-gray-600">#</th>
                                    <th class="px-6 py-4 border-b-2 border-gray-600">To</th>
                                    <th class="px-6 py-4 border-b-2 border-gray-600">Subject</th>
                                    <th class="px-6 py-4 border-b-2 border-gray-600">Created</th>
                                    <th class="px-6 py-4 border-b-2 border-gray-600">Attachments</th>
                                    <th class="px-6 py-4 border-b-2 border-gray-600 text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="text-sm text-gray-300 divide-y divide-gray-700">
                                @foreach ($emails as $email)
                                    <tr class="hover:bg-gray-700 transition duration-200">
                                        <td class="px-6 py-4">{{ $loop->iteration }}</td>
                                        <td class="px-6 py-4">{{ $email->to }}</td>
                                        <td class="px-6 py-4">{{ $email->subject }}</td>
                                        <td class="px-6 py-4">{{ $email->created_at->format('M d, Y H:i') }}</td>
                                        <td class="px-6 py-4">
                                            @if ($email->attachments && count(json_decode($email->attachments, true)) > 0)
                                                <span
                                                    class="inline-flex items-center px-2 py-1 text-xs font-medium bg-blue-100 text-blue-800 rounded-full">
                                                    <i class="fas fa-paperclip mr-1"></i>
                                                    {{ count(json_decode($email->attachments, true)) }} file(s)
                                                </span>
                                            @else
                                                <span class="text-gray-500">No attachments</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 flex justify-center gap-3">
                                            <a href="{{ route('emails.edit', $email) }}"
                                                class="text-blue-400 hover:text-blue-600 transition duration-300"
                                                title="Edit Draft">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="{{ route('emails.show', $email) }}"
                                                class="text-green-400 hover:text-green-600 transition duration-300"
                                                title="Preview">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <form action="{{ route('emails.destroy', $email) }}" method="POST"
                                                class="inline">
                                                @csrf @method('DELETE')
                                                <button type="submit"
                                                    class="text-red-400 hover:text-red-600 transition duration-300"
                                                    title="Delete Draft"
                                                    onclick="return confirm('Are you sure you want to delete this draft?')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
