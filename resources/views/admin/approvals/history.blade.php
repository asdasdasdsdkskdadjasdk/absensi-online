<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Persetujuan Absensi Dinas') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="mb-4 border-b border-gray-200">
                <ul class="flex flex-wrap -mb-px text-sm font-medium text-center">
                    <li class="mr-2">
                        <a href="{{ route('admin.approvals.index') }}" class="inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300">
                            Menunggu Persetujuan
                        </a>
                    </li>
                    <li class="mr-2">
                        <a href="{{ route('admin.approvals.history') }}" class="inline-block p-4 text-blue-600 border-b-2 border-blue-600 rounded-t-lg active">
                            Riwayat Persetujuan
                        </a>
                    </li>
                </ul>
            </div>
            
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @forelse ($historyAttendances as $attendance)
                        <div class="mb-4 p-4 border rounded-lg md:flex md:items-center md:space-x-4">
                            <div class="flex-grow">
                                <p class="font-bold text-lg">{{ $attendance->user->name }}</p>
                                <p class="text-sm text-gray-600">Tipe: <span class="font-semibold">{{ ucfirst($attendance->type) }}</span></p>
                                <p class="text-sm text-gray-600">Kantor Terkait: <span class="font-semibold">{{ $attendance->officeLocation->name ?? 'N/A' }}</span></p>
                                <p class="text-sm text-gray-600">Waktu Absen: {{ $attendance->created_at->format('d M Y, H:i') }}</p>
                            </div>
                            <div class="flex-shrink-0 mt-4 md:mt-0 text-right">
                                <div>
                                    @if($attendance->status == 'approved')
                                        <span class="px-3 py-1 text-sm font-semibold text-green-800 bg-green-200 rounded-full">Disetujui</span>
                                    @else
                                        <span class="px-3 py-1 text-sm font-semibold text-red-800 bg-red-200 rounded-full">Ditolak</span>
                                    @endif
                                </div>
                                <div class="mt-2 flex items-center justify-end space-x-2">
                                    <a href="{{ route('admin.approvals.edit', $attendance) }}" class="px-3 py-1 bg-yellow-500 text-white text-xs font-medium rounded-md hover:bg-yellow-600">Edit</a>
                                    <form action="{{ route('admin.approvals.destroy', $attendance) }}" method="POST" onsubmit="return confirm('Anda yakin ingin menghapus data ini secara permanen?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-3 py-1 bg-gray-500 text-white text-xs font-medium rounded-md hover:bg-gray-600">Hapus</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p>Belum ada riwayat persetujuan absensi.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>