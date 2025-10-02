<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tambah Lokasi Kantor Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    
                    <form method="POST" action="{{ route('admin.locations.store') }}">
                        @csrf

                        <div>
                            <x-input-label for="name" :value="__('Nama Lokasi')" />
                            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="off" />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="latitude" :value="__('Latitude')" />
                            <x-text-input id="latitude" class="block mt-1 w-full" type="text" name="latitude" :value="old('latitude')" required autocomplete="off" />
                            <x-input-error :messages="$errors->get('latitude')" class="mt-2" />
                            <p class="mt-1 text-sm text-gray-500">Contoh: -6.2087634</p>
                        </div>

                        <div class="mt-4">
                            <x-input-label for="longitude" :value="__('Longitude')" />
                            <x-text-input id="longitude" class="block mt-1 w-full" type="text" name="longitude" :value="old('longitude')" required autocomplete="off" />
                            <x-input-error :messages="$errors->get('longitude')" class="mt-2" />
                            <p class="mt-1 text-sm text-gray-500">Contoh: 106.845599</p>
                        </div>

                        <div class="mt-4">
                            <x-input-label for="radius_meters" :value="__('Radius (dalam meter)')" />
                            <x-text-input id="radius_meters" class="block mt-1 w-full" type="number" name="radius_meters" :value="old('radius_meters')" required />
                            <x-input-error :messages="$errors->get('radius_meters')" class="mt-2" />
                            <p class="mt-1 text-sm text-gray-500">Jarak toleransi absensi dari titik pusat lokasi.</p>
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('admin.locations.index') }}" class="text-sm text-gray-600 hover:text-gray-900 underline mr-4">
                                {{ __('Batal') }}
                            </a>
                            <x-primary-button>
                                {{ __('Simpan Lokasi') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
