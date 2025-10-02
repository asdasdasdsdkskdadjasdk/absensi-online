<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Skema Database') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

                <!-- Kolom untuk setiap koneksi database -->
                @foreach ($schemas as $connectionName => $schema)
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900">
                            <h3 class="text-xl font-bold mb-4 border-b pb-2">{{ $schema['name'] }}</h3>

                            @if ($schema['error'])
                                <div class="p-4 bg-red-100 text-red-700 rounded-lg">
                                    <strong>Error:</strong> {{ $schema['error'] }}
                                </div>
                            @else
                                <div class="space-y-4">
                                    @forelse ($schema['tables'] as $tableName => $columns)
                                        <div class="border rounded-lg p-4">
                                            <p class="font-semibold text-lg">{{ $tableName }}</p>
                                            <ul class="list-disc list-inside mt-2 text-sm text-gray-600">
                                                @foreach ($columns as $column)
                                                    <li>{{ $column }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @empty
                                        <p>Tidak ada tabel yang ditemukan di database ini.</p>
                                    @endforelse
                                </div>
                            @endif

                        </div>
                    </div>
                @endforeach

            </div>
        </div>
    </div>
</x-app-layout>
