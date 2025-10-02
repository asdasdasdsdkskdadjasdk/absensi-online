<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Persetujuan Absensi') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Navigasi Tab -->
            @include('admin.approvals.partials.nav-tabs')
            
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if (session('success'))
                        <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-lg">
                            {{ session('success') }}
                        </div>
                    @endif

                    <!-- Formulir Pencarian -->
                    <div class="mb-4">
                        <form action="{{ route('admin.approvals.index') }}" method="GET">
                            <div class="flex">
                                <input type="text" name="search" placeholder="Cari berdasarkan nama..." class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" value="{{ $search }}">
                                <button type="submit" class="ml-2 px-4 py-2 bg-blue-500 text-white font-semibold rounded-md hover:bg-blue-600">Cari</button>
                            </div>
                        </form>
                    </div>

                    @forelse ($pendingAttendances as $attendance)
                        <div class="mb-6 p-4 border rounded-lg md:flex md:items-start md:space-x-4">
                            <!-- Foto Selfie -->
                            <div class="flex-shrink-0 mb-4 md:mb-0">
                                <img src="{{ asset('storage/' . $attendance->photo_path) }}" alt="Foto Absen" class="w-32 h-32 object-cover rounded-lg">
                            </div>
                            <!-- Detail Absensi -->
                            <div class="flex-grow">
                                <p class="font-bold text-lg">{{ $attendance->user->name }}</p>
                                <p class="text-sm text-gray-600">Tipe: <span class="font-semibold">{{ ucfirst($attendance->type) }}</span></p>
                                <p class="text-sm text-gray-600">Waktu: {{ $attendance->created_at->format('d M Y, H:i:s') }}</p>
                                <p class="text-sm text-gray-600">Lokasi: 
                                    <a href="https://www.google.com/maps?q={{ $attendance->latitude }},{{ $attendance->longitude }}" target="_blank" class="text-blue-500 hover:underline">
                                        Lihat di Peta
                                    </a>
                                </p>
                            </div>
                            <!-- Tombol Aksi -->
                            <div class="flex-shrink-0 mt-4 md:mt-0 flex items-center space-x-2">
                                <form action="{{ route('admin.approvals.approve', $attendance) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="px-4 py-2 bg-green-500 text-white text-sm font-medium rounded-md hover:bg-green-600">Setujui</button>
                                </form>
                                <form action="{{ route('admin.approvals.reject', $attendance) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="px-4 py-2 bg-red-500 text-white text-sm font-medium rounded-md hover:bg-red-600">Tolak</button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <p>Tidak ada absensi yang menunggu persetujuan @if($search) dengan kata kunci "{{ $search }}" @endif.</p>
                    @endforelse

                    <!-- Pagination Links -->
                    <div class="mt-4">
                        {{ $pendingAttendances->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
