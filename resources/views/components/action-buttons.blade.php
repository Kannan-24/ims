@props(['model', 'id'])

<td class="px-6 py-4 border-b border-gray-200">
    <div class="flex gap-4 justify-start">
        <!-- View Button with Icon -->
        <a href="{{ route($model . '.show', $id) }}" aria-label="View {{ $model }}">
            <button
                class="p-2 bg-indigo-600 rounded-lg shadow-md transform transition-transform duration-300 ease-in-out hover:bg-indigo-700 hover:scale-105 focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:ring-opacity-75">
                <i class="fas fa-eye text-white"></i>
            </button>
        </a>

        <!-- Edit Button with Icon -->
        <a href="{{ route($model . '.edit', $id) }}" aria-label="Edit {{ $model }}">
            <button
                class="p-2 bg-teal-600 rounded-lg shadow-md transform transition-transform duration-300 ease-in-out hover:bg-teal-700 hover:scale-105 focus:outline-none focus:ring-2 focus:ring-teal-400 focus:ring-opacity-75">
                <i class="fas fa-edit text-white"></i>
            </button>
        </a>

        <!-- Delete Button with Icon -->
        <form action="{{ route($model . '.destroy', $id) }}" method="POST" class="inline">
            @csrf
            @method('DELETE')
            <button type="submit"
                class="p-2 bg-red-600 rounded-lg shadow-md transform transition-transform duration-300 ease-in-out hover:bg-red-700 hover:scale-105 focus:outline-none focus:ring-2 focus:ring-red-400 focus:ring-opacity-75"
                onclick="return confirm('Are you sure you want to delete this?');">
                <i class="fas fa-trash-alt text-white"></i>
            </button>
        </form>
    </div>
</td>

