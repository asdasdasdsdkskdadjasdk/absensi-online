<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Edit Lokasi untuk Terminal: {{ $terminal['alias'] ?? $terminal['name'] ?? 'Tidak Dikenal' }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    @if ($errors->any())
                        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                            <strong class="font-bold">Oops!</strong>
                            <span class="block sm:inline">Ada beberapa kesalahan pada input Anda.</span>
                        </div>
                    @endif

                    {{-- 
                        Form utama untuk UPDATE. Beri ID agar bisa di-target oleh tombol.
                        PENTING: Form ditutup SEBELUM bagian tombol-tombol.
                    --}}
                    <form id="location-update-form" action="{{ route('admin.locations.update', $terminal['id']) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                            <div>
                                <label for="latitude" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Latitude</label>
                                <input 
                                    type="number" 
                                    name="latitude" 
                                    id="latitude" 
                                    value="{{ old('latitude', $location->latitude) }}" 
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 @error('latitude') border-red-500 @enderror" 
                                    required
                                    step="any"
                                    min="-90"
                                    max="90">
                                @error('latitude')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="longitude" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Longitude</label>
                                <input 
                                    type="number" 
                                    name="longitude" 
                                    id="longitude" 
                                    value="{{ old('longitude', $location->longitude) }}" 
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 @error('longitude') border-red-500 @enderror" 
                                    required
                                    step="any"
                                    min="-180"
                                    max="180">
                                @error('longitude')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-6">
                            <label for="radius_meters" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Radius (dalam meter)</label>
                            <input 
                                type="number" 
                                name="radius_meters" 
                                id="radius_meters" 
                                value="{{ old('radius_meters', $location->radius_meters) }}" 
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 @error('radius_meters') border-red-500 @enderror" 
                                required
                                min="1">
                             @error('radius_meters')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </form> <!-- FORM UPDATE DITUTUP DI SINI -->

                    {{-- Wadah untuk semua tombol di bagian bawah --}}
                    <div class="mt-6 flex items-center justify-between">
                        
                        {{-- Tombol Hapus (dengan form-nya sendiri yang terpisah) --}}
                        <div>
                            @if($location->exists)
                                <form action="{{ route('admin.locations.destroy', $terminal['id']) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data lokasi ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-sm text-red-600 dark:text-red-500 hover:underline">Hapus Lokasi</button>
                                </form>
                            @endif
                        </div>

                        {{-- Tombol Batal & Simpan --}}
                        <div class="flex items-center">
                            <a href="{{ route('admin.locations.index') }}" class="text-gray-600 dark:text-gray-400 hover:underline mr-4">Batal</a>
                            {{-- Tombol ini sekarang secara eksplisit mengirim form 'location-update-form' --}}
                            <button type="submit" form="location-update-form" class="px-4 py-2 bg-indigo-600 text-white font-semibold rounded-md hover:bg-indigo-700">Simpan Perubahan</button>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>