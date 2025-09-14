<x-app-layout>
    <x-slot name="title">
        {{ __('Email Details') }} - {{ config('app.name', 'IMS') }}
    </x-slot>

    <div class="bg-white min-h-screen" x-data="{ showDeleteModal: false }">
        <!-- Breadcrumbs -->
        <div class="px-6 py-3 bg-gray-50 border-b border-gray-200">
            <nav class="flex" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="{{ route('dashboard') }}"
                            class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600">
                            <i class="fas fa-home mr-2"></i>
                            Dashboard
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                            <a href="{{ route('emails.index') }}"
                                class="text-sm font-medium text-gray-700 hover:text-blue-600">
                                Emails
                            </a>
                        </div>
                    </li>
                    <li aria-current="page">
                        <div class="flex items-center">
                            <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                            <span class="text-sm font-medium text-gray-500">Email Details</span>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>

        <!-- Header -->
        <div class="px-6 py-6 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-envelope text-blue-600 text-xl"></i>
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">Email Details</h1>
                        <div class="flex items-center mt-2 space-x-4">
                            @if($email->is_draft)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                    <i class="fas fa-edit mr-1"></i>
                                    Draft
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <i class="fas fa-check mr-1"></i>
                                    Sent
                                </span>
                            @endif
                            <span class="text-sm text-gray-500">
                                <i class="fas fa-calendar mr-1"></i>
                                {{ $email->created_at->format('M d, Y \a\t g:i A') }}
                            </span>
                        </div>
                    </div>
                </div>
                <div class="flex items-center space-x-3">
                    <!-- Back Button -->
                    <a href="{{ route('emails.index') }}"
                        class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition-colors">
                        <i class="fas fa-arrow-left w-4 h-4 mr-2"></i>
                        Back to Emails
                    </a>
                    <!-- Edit Button (if draft) -->
                    @if($email->is_draft)
                        <a href="{{ route('emails.edit', $email) }}"
                            class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                            <i class="fas fa-edit w-4 h-4 mr-2"></i>
                            Edit Draft
                        </a>
                    @endif
                    <!-- Delete Button -->
                    <button @click="showDeleteModal = true"
                        class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-colors">
                        <i class="fas fa-trash w-4 h-4 mr-2"></i>
                        Delete
                    </button>
                </div>
            </div>
        </div>

        <!-- Email Content -->
        <div class="px-6 py-6">
            <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden">
                <!-- Email Header -->
                <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                    <div class="space-y-4">
                        <!-- Subject -->
                        @if($email->subject)
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Subject</label>
                                <p class="text-lg font-semibold text-gray-900">{{ $email->subject }}</p>
                            </div>
                        @endif

                        <!-- Recipients -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <!-- To -->
                            @if($email->to)
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">To</label>
                                    <div class="space-y-2">
                                        @php
                                            $recipients = explode(',', $email->to);
                                        @endphp
                                        @foreach($recipients as $recipient)
                                            <div class="flex items-center">
                                                <div class="w-6 h-6 bg-blue-100 rounded-full flex items-center justify-center mr-2">
                                                    <i class="fas fa-user text-blue-600 text-xs"></i>
                                                </div>
                                                <span class="text-sm text-gray-900">{{ trim($recipient) }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <!-- CC -->
                            @if($email->cc)
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">CC</label>
                                    <div class="space-y-2">
                                        @php
                                            $ccRecipients = explode(',', $email->cc);
                                        @endphp
                                        @foreach($ccRecipients as $cc)
                                            <div class="flex items-center">
                                                <div class="w-6 h-6 bg-gray-100 rounded-full flex items-center justify-center mr-2">
                                                    <i class="fas fa-user text-gray-600 text-xs"></i>
                                                </div>
                                                <span class="text-sm text-gray-900">{{ trim($cc) }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <!-- BCC -->
                            @if($email->bcc)
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">BCC</label>
                                    <div class="space-y-2">
                                        @php
                                            $bccRecipients = explode(',', $email->bcc);
                                        @endphp
                                        @foreach($bccRecipients as $bcc)
                                            <div class="flex items-center">
                                                <div class="w-6 h-6 bg-gray-100 rounded-full flex items-center justify-center mr-2">
                                                    <i class="fas fa-user-secret text-gray-600 text-xs"></i>
                                                </div>
                                                <span class="text-sm text-gray-900">{{ trim($bcc) }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Email Body -->
                <div class="px-6 py-6">
                    @if($email->body)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-3">Message Content</label>
                            <div class="prose max-w-none bg-gray-50 rounded-lg p-4 border border-gray-200">
                                {!! $email->body !!}
                            </div>
                        </div>
                    @else
                        <div class="text-center py-8 text-gray-500">
                            <i class="fas fa-file-alt text-4xl mb-4"></i>
                            <p>No message content available</p>
                        </div>
                    @endif
                </div>

                <!-- Attachments -->
                @if($email->attachments && count(json_decode($email->attachments)) > 0)
                    <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                        <label class="block text-sm font-medium text-gray-700 mb-3">
                            <i class="fas fa-paperclip mr-2"></i>
                            Attachments ({{ count(json_decode($email->attachments)) }})
                        </label>
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                            @foreach(json_decode($email->attachments) as $attachment)
                                <div class="flex items-center p-3 bg-white border border-gray-200 rounded-lg hover:border-blue-300 transition-colors">
                                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                        @php
                                            $extension = pathinfo($attachment, PATHINFO_EXTENSION);
                                        @endphp
                                        @if(in_array(strtolower($extension), ['pdf']))
                                            <i class="fas fa-file-pdf text-red-600"></i>
                                        @elseif(in_array(strtolower($extension), ['doc', 'docx']))
                                            <i class="fas fa-file-word text-blue-600"></i>
                                        @elseif(in_array(strtolower($extension), ['xls', 'xlsx']))
                                            <i class="fas fa-file-excel text-green-600"></i>
                                        @elseif(in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'gif']))
                                            <i class="fas fa-file-image text-purple-600"></i>
                                        @else
                                            <i class="fas fa-file text-gray-600"></i>
                                        @endif
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-900 truncate">
                                            {{ basename($attachment) }}
                                        </p>
                                        <p class="text-xs text-gray-500">
                                            {{ strtoupper($extension) }} File
                                        </p>
                                    </div>
                                    <a href="{{ asset('storage/' . $attachment) }}" 
                                       target="_blank"
                                       class="ml-2 text-blue-600 hover:text-blue-800 transition-colors">
                                        <i class="fas fa-download"></i>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Delete Confirmation Modal -->
        <div x-show="showDeleteModal" 
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
             style="display: none;">
            <div x-show="showDeleteModal"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 transform scale-95"
                 x-transition:enter-end="opacity-100 transform scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 transform scale-100"
                 x-transition:leave-end="opacity-0 transform scale-95"
                 class="bg-white rounded-lg p-6 w-full max-w-md mx-4">
                <div class="flex items-center justify-center w-12 h-12 mx-auto mb-4 bg-red-100 rounded-full">
                    <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 text-center mb-2">Delete Email</h3>
                <p class="text-gray-600 text-center mb-6">
                    Are you sure you want to delete this email? This action cannot be undone.
                </p>
                <div class="flex gap-3 justify-end">
                    <button @click="showDeleteModal = false"
                            class="px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-700 text-sm font-medium rounded-lg transition-colors">
                        Cancel
                    </button>
                    <form action="{{ route('emails.destroy', $email->id) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-colors">
                            Delete Email
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
