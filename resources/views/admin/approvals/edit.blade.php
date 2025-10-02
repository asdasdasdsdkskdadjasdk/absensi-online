<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Data Absensi untuk: ') }} <span class="font-bold">{{ $attendance->user->name }}</span>
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <div class="mb-6 text-center">
                        <img src="{{ asset('storage/' . $attendance->photo_path) }}" alt="Foto Absen" class="w-40 h-40 object-cover rounded-lg inline-block">
                        <p class="mt-2 text-sm text-gray-600">Waktu Absen: {{ $attendance->created_at->format('d M Y, H:i:s') }}</p>
                    </div>

                    <form method="POST" action="{{ route('admin.approvals.update', $attendance) }}">
                        @csrf
                        @method('PUT')

                        <div class="mt-4">
                            <x-input-label for="office_location_id" :value="__('Kantor Terkait')" />
                            <select name="office_location_id" id="office_location_id" class="block mt-1 w-full rounded-md shadow-sm border-gray-300">
                                @foreach ($locations as $location)
                                    <option value="{{ $location->id }}" @selected($location->id == $attendance->office_location_id)>
                                        {{ $location->name }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('office_location_id')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="type" :value="__('Tipe Absen')" />
                            <select name="type" id="type" class="block mt-1 w-full rounded-md shadow-sm border-gray-300">
                                <option value="kantor" @selected($attendance->type == 'kantor')>Kantor</option>
                                <option value="dinas" @selected($attendance->type == 'dinas')>Dinas</option>
                            </select>
                            <x-input-error :messages="$errors->get('type')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="status" :value="__('Status')" />
                            <select name="status" id="status" class="block mt-1 w-full rounded-md shadow-sm border-gray-300">
                                <option value="pending" @selected($attendance->status == 'pending')>Pending</option>
                                <option value="approved" @selected($attendance->status == 'approved')>Approved</option>
                                <option value="rejected" @selected($attendance->status == 'rejected')>Rejected</option>
                            </select>
                             <x-input-error :messages="$errors->get('status')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ url()->previous() }}" class="text-sm text-gray-600 hover:text-gray-900 underline mr-4">
                                {{ __('Batal') }}
                            </a>
                            <x-primary-button>
                                {{ __('Simpan Perubahan') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>