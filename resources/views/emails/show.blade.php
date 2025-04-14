<x-app-layout>
    <x-slot name="title">
        {{ __('Email Details') }} - {{ config('app.name', 'ATMS') }}
    </x-slot>

    <div class="py-6 mt-20 ml-4 sm:ml-64">
        <div class="w-full mx-auto max-w-7xl sm:px-6 lg:px-8">
            <x-bread-crumb-navigation />

            <div class="p-8 bg-gray-800 border border-gray-700 rounded-lg shadow-lg relative">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-3xl font-bold text-gray-200">Email Details</h2>
                    <div class="flex gap-2">
                        <a href="{{ route('emails.create') }}"
                            class="flex items-center px-4 py-2 text-white bg-blue-500 rounded-lg hover:bg-blue-600 transition">
                            Compose New Email
                        </a>
                        <form action="{{ route('emails.destroy', $email->id) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="flex items-center px-4 py-2 text-white bg-red-500 rounded-lg hover:bg-red-600 transition">
                                Delete Email
                            </button>
                        </form>
                    </div>
                </div>

                <hr class="my-6 border-gray-600">

                <div class="space-y-4 text-gray-300">
                    <p><strong>Subject:</strong> {{ $email->subject }}</p>
                    <p><strong>To:</strong> {{ $email->to }}</p>
                    <p><strong>CC:</strong> {{ $email->cc ?? 'N/A' }}</p>
                    <p><strong>BCC:</strong> {{ $email->bcc ?? 'N/A' }}</p>
                    <p><strong>Body:</strong></p>
                    <div>{!! $email->body !!}</div> <!-- This will render the HTML content properly -->

                    @if($email->attachments)
                        <p><strong>Attachments:</strong></p>
                        <ul class="list-disc pl-5">
                            @foreach(json_decode($email->attachments) as $attachment)
                                <li>
                                    <a href="{{ asset('storage/' . $attachment) }}" class="text-blue-400 hover:underline"
                                        target="_blank">
                                        {{ basename($attachment) }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p><strong>Attachments:</strong> None</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
