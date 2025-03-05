<div class="mb-6">
    <nav class="flex items-center justify-between p-4 bg-white border border-gray-200 rounded-lg shadow-md"
        aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-2 text-gray-700">
            <li>
                <a href="{{ route('dashboard') }}"
                    class="font-medium text-blue-600 transition duration-300 hover:text-blue-700">
                    Dashboard
                </a>
            </li>
            @php
                $segments = request()->segments(); // Get URL segments
                $url = '';
                $lastSegment = end($segments); // Get last segment
            @endphp
            @foreach ($segments as $index => $segment)
                @php
                    $url .= '/' . $segment; // Build dynamic URL
                @endphp
                <li class="flex items-center">
                    /&nbsp;
                    @if ($index !== count($segments) - 1)
                        <a href="{{ url($url) }}"
                            class="font-medium text-blue-600 capitalize transition duration-300 hover:text-blue-700">
                            {{ str_replace('-', ' ', $segment) }}
                        </a>
                    @else
                        <span class="font-semibold text-gray-900 capitalize">
                            {{ str_replace('-', ' ', $segment) }}
                        </span>
                    @endif
                </li>
            @endforeach
        </ol>

        <!-- Show "Create" Button Only on Index Pages -->
        @if (count($segments) == 1 && !in_array($lastSegment, ['profile', 'account-settings']))
            <a href="{{ url($url . '/create') }}"
                class="px-5 py-2 ml-4 text-sm text-white transition duration-300 rounded-lg shadow-md bg-gradient-to-r from-teal-500 to-blue-600 hover:from-teal-600 hover:to-blue-700">
                Create {{ ucfirst(str_replace('-', ' ', $lastSegment)) }}
            </a>
        @endif
    </nav>
</div>
