<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Foto Karyawan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <!-- Formulir Pencarian -->
                    <div class="mb-6">
                        <form action="{{ route('admin.biophotos.index') }}" method="GET">
                            <div class="flex">
                                <input type="text" name="search" placeholder="Cari nama atau ID karyawan..." class="w-full rounded-l-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600" value="{{ request('search') }}">
                                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white font-semibold rounded-r-md hover:bg-indigo-700 transition-colors">Cari</button>
                            </div>
                        </form>
                    </div>

                    <!-- Grid untuk Foto -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                        @forelse ($bioPhotos as $photo)
                            <div class="border dark:border-gray-700 rounded-lg overflow-hidden shadow-md">
                                {{-- Menampilkan gambar dari data Base64 --}}
                                @if(!empty($photo['register_photo']))
                                    <img src="data:image/jpeg;base64,{!! $photo['register_photo'] !!}" alt="Foto Registrasi {{ $photo['first_name'] }}" class="w-full h-48 object-cover bg-gray-200">
                                @else
                                    {{-- Tampilkan placeholder jika foto tidak ada --}}
                                    <div class="w-full h-48 bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
                                        <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l-1.586-1.586a2 2 0 00-2.828 0L6 14m6-6l.01.01"></path></svg>
                                    </div>
                                @endif
                                <div class="p-4">
                                    <p class="font-bold truncate">{{ $photo['first_name'] }} {{ $photo['last_name'] }}</p>
                                    {{-- Asumsi API mengembalikan detail employee di dalam data bio_photo --}}
                                    <p class="text-xs text-gray-500 dark:text-gray-500 mt-2">
                                        {{ \Carbon\Carbon::parse($photo['register_time'])->translatedFormat('d F Y, H:i') }}
                                    </p>
                                </div>
                            </div>
                        @empty
                            <p class="col-span-full text-center text-gray-500">Tidak ada foto registrasi yang ditemukan.</p>
                        @endforelse
                    </div>

                    <!-- Paginasi -->
                    <div class="mt-8">
                        {{ $bioPhotos->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>

