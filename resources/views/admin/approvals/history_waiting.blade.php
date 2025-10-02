<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Absensi Menunggu Sinkronisasi') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Navigasi Tab -->
            @include('admin.approvals.partials.nav-tabs')
            
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <!-- Formulir Pencarian -->
                    <div class="mb-4">
                        <form action="{{ route('admin.approvals.history_waiting') }}" method="GET">
                            <div class="flex">
                                <input type="text" name="search" placeholder="Cari berdasarkan nama..." class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" value="{{ $search ?? '' }}">
                                <button type="submit" class="ml-2 px-4 py-2 bg-blue-500 text-white font-semibold rounded-md hover:bg-blue-600">Cari</button>
                            </div>
                        </form>
                    </div>

                    <div class="space-y-4">
                         @forelse ($waitingAttendances as $attendance)
                            <div class="p-4 border rounded-lg md:flex md:items-start md:space-x-4">
                                <!-- Foto Selfie -->
                                <div class="flex-shrink-0 mb-4 md:mb-0">
                                    <img src="{{ asset('storage/' . $attendance->photo_path) }}" alt="Foto Absen" class="w-24 h-24 object-cover rounded-lg">
                                </div>
                                <!-- Detail Absensi -->
                                <div class="flex-grow">
                                    <p class="font-bold text-lg">{{ $attendance->user->name ?? 'Pengguna tidak ditemukan' }}</p>
                                    <p class="text-sm text-gray-600">Tipe: <span class="font-semibold">{{ ucfirst($attendance->type) }}</span></p>
                                    <p class="text-sm text-gray-600">Waktu Absen: {{ $attendance->created_at->format('d M Y, H:i:s') }}</p>
                                    <p class="text-sm text-gray-600">Ditambahkan ke Antrean: {{ $attendance->updated_at->format('d M Y, H:i:s') }}</p>
                                </div>
                            </div>
                        @empty
                            <p>Tidak ada absensi yang sedang menunggu sinkronisasi @if($search ?? false) dengan kata kunci "{{ $search }}" @endif.</p>
                        @endforelse
                    </div>

                    <!-- Pagination Links -->
                    <div class="mt-4">
                        {{ $waitingAttendances->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
