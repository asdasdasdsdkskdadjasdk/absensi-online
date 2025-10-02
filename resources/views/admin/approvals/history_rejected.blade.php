<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Persetujuan Absensi') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Navigasi Tab -->
            <div class="mb-4 border-b border-gray-200">
                <ul class="flex flex-wrap -mb-px text-sm font-medium text-center">
                    <li class="mr-2">
                        <a href="{{ route('admin.approvals.index') }}" 
                           class="{{ request()->routeIs('admin.approvals.index') ? 'inline-block p-4 text-blue-600 border-b-2 border-blue-600 rounded-t-lg active' : 'inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300' }}">
                            Menunggu Persetujuan
                        </a>
                    </li>
                    <li class="mr-2">
            <a href="{{ route('admin.approvals.history_waiting') }}" 
               class="{{ request()->routeIs('admin.approvals.history_waiting') ? 'inline-block p-4 text-blue-600 border-b-2 border-blue-600 rounded-t-lg active' : 'inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300' }}">
                Menunggu Sinkronisasi
            </a>
        </li>
                    <li class="mr-2">
                        <a href="{{ route('admin.approvals.history_rejected') }}" 
                           class="{{ request()->routeIs('admin.approvals.history_rejected') ? 'inline-block p-4 text-blue-600 border-b-2 border-blue-600 rounded-t-lg active' : 'inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300' }}">
                            Riwayat Ditolak
                        </a>
                    </li>
                    <li class="mr-2">
                        <a href="{{ route('admin.approvals.history_approved') }}" 
                           class="{{ request()->routeIs('admin.approvals.history_approved') ? 'inline-block p-4 text-blue-600 border-b-2 border-blue-600 rounded-t-lg active' : 'inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300' }}">
                            Riwayat Disetujui
                        </a>
                    </li>
                </ul>
            </div>
            
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if (session('success'))
                        <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-lg">
                            {{ session('success') }}
                        </div>
                    @endif

                    <!-- ===== KODE FORMULIR PENCARIAN UNTUK "MENUNGGU PERSETUJUAN" ===== -->
                    <div class="mb-4">
                        <form action="{{ route('admin.approvals.history_rejected') }}" method="GET">
                            <div class="flex">
                                <input type="text" name="search" placeholder="Cari berdasarkan nama..." class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" value="{{ $search ?? '' }}">
                                <button type="submit" class="ml-2 px-4 py-2 bg-blue-500 text-white font-semibold rounded-md hover:bg-blue-600">Cari</button>
                            </div>
                        </form>
                    </div>

                    @forelse ($rejectedAttendances as $attendance)
                        <div class="mb-6 p-4 border rounded-lg md:flex md:items-start md:space-x-4">
                            <!-- Foto Selfie -->
                            <div class="flex-shrink-0 mb-4 md:mb-0">
                                <img src="{{ asset('storage/' . $attendance->photo_path) }}" alt="Foto Absen" class="w-32 h-32 object-cover rounded-lg">
                            </div>
                            <!-- Detail Absensi -->
                            <div class="flex-grow">
                                <p class="font-bold text-lg">{{ $attendance->user->name }}</p>
                                <p class="text-sm text-gray-600">Tipe: <span class="font-semibold">{{ ucfirst($attendance->type) }}</span></p>
                                <p class="text-sm text-gray-600">Waktu Absen: {{ $attendance->created_at->format('d M Y, H:i:s') }}</p>
                                <p class="text-sm text-gray-600">Ditolak Pada: {{ $attendance->updated_at->format('d M Y, H:i:s') }}</p>
                                <p class="text-sm text-gray-600">Lokasi: 
                                    <a href="https://www.google.com/maps?q={{ $attendance->latitude }},{{ $attendance->longitude }}" target="_blank" class="text-blue-500 hover:underline">
                                        Lihat di Peta
                                    </a>
                                </p>
                            </div>
                             <!-- Tombol Aksi (Hapus) -->
                             <div class="flex-shrink-0 mt-4 md:mt-0 flex items-center space-x-2">
                                <form action="{{ route('admin.approvals.destroy', $attendance) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini secara permanen?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-4 py-2 bg-gray-500 text-white text-sm font-medium rounded-md hover:bg-gray-600">Hapus</button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <p>Tidak ada riwayat absensi yang ditolak.</p>
                    @endforelse

                    <div class="mt-4">
                        {{ $rejectedAttendances->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
