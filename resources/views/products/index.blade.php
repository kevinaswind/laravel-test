<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Products List') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <table class="w-full">
                        <thead>
                        <tr>
                            <th class="border-b border-gray-300 p-2">#</th>
                            <th class="border-b border-gray-300 p-2">Name</th>
                            <th class="border-b border-gray-300 p-2">Price</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($products as $product)
                            <tr>
                                <td class="text-center p-2">{{ $loop->index + 1 }}</td>
                                <td class="text-center p-2">{{ $product->name }}</td>
                                <td class="text-center p-2">{{ $product->price }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="p-2 text-center">
                                    No products found.
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>