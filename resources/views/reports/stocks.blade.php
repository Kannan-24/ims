<x-app-layout>
    <x-slot name="title">Stock Reports</x-slot>

    <div class="p-6 mt-20 sm:ml-64">
        <h2 class="text-2xl font-bold mb-4">Stock Reports</h2>

        <table class="w-full table-auto bg-white shadow rounded">
            <thead class="bg-gray-800 text-white">
                <tr>
                    <th class="px-4 py-2">#</th>
                    <th class="px-4 py-2">Product</th>
                    <th class="px-4 py-2">Category</th>
                    <th class="px-4 py-2">Available Stock</th>
                    <th class="px-4 py-2">Unit</th>
                </tr>
            </thead>
            <tbody>
                @foreach($products as $index => $product)
                    <tr class="border-b">
                        <td class="px-4 py-2">{{ $index + 1 }}</td>
                        <td class="px-4 py-2">{{ $product->name }}</td>
                        <td class="px-4 py-2">{{ $product->category->name ?? '-' }}</td>
                        <td class="px-4 py-2">{{ $product->stock }}</td>
                        <td class="px-4 py-2">{{ $product->unit }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-app-layout>
