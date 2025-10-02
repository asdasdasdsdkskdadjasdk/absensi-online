<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Error Kelola Lokasi') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-red-700 bg-red-100 border border-red-400 rounded-lg">
                    <h3 class="font-bold text-lg">Gagal Terhubung ke Server API</h3>
                    <p class="mt-2">Tidak dapat mengambil daftar terminal dari server ZKTeco. Pastikan server API sedang berjalan dan bisa diakses.</p>
                    <p class="text-sm mt-4 text-gray-600"><strong>Detail Error:</strong> {{ $error }}</p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
