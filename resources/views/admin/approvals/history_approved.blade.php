<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Persetujuan Absensi') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Navigasi Tab -->
            <div class="mb-4 border-b border-gray-200 dark:border-gray-700">
                <ul class="flex flex-wrap -mb-px text-sm font-medium text-center">
                    <li class="mr-2">
                        <a href="{{ route('admin.approvals.index') }}" 
                           class="{{ request()->routeIs('admin.approvals.index') ? 'inline-block p-4 text-blue-600 border-b-2 border-blue-600 rounded-t-lg active dark:text-blue-500 dark:border-blue-500' : 'inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300' }}">
                            Menunggu Persetujuan
                        </a>
                    </li>
                    <li class="mr-2">
                        <a href="{{ route('admin.approvals.history_waiting') }}" 
                           class="{{ request()->routeIs('admin.approvals.history_waiting') ? 'inline-block p-4 text-blue-600 border-b-2 border-blue-600 rounded-t-lg active dark:text-blue-500 dark:border-blue-500' : 'inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300' }}">
                            Menunggu Sinkronisasi
                        </a>
                    </li>
                    <li class="mr-2">
                        <a href="{{ route('admin.approvals.history_rejected') }}" 
                           class="{{ request()->routeIs('admin.approvals.history_rejected') ? 'inline-block p-4 text-blue-600 border-b-2 border-blue-600 rounded-t-lg active dark:text-blue-500 dark:border-blue-500' : 'inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300' }}">
                            Riwayat Ditolak
                        </a>
                    </li>
                    <li class="mr-2">
                        <a href="{{ route('admin.approvals.history_approved') }}" 
                           class="{{ request()->routeIs('admin.approvals.history_approved') ? 'inline-block p-4 text-blue-600 border-b-2 border-blue-600 rounded-t-lg active dark:text-blue-500 dark:border-blue-500' : 'inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300' }}">
                            Riwayat Disetujui
                        </a>
                    </li>
                </ul>
            </div>
            
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100" x-data="{ exportModal: false, isExporting: false }">
                    
                    @if(session('success'))
                        <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-lg dark:bg-green-900 dark:text-green-300">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                            <strong class="font-bold">Error!</strong>
                            <span class="block sm:inline">{{ $errors->first() }}</span>
                        </div>
                    @endif

                    <div class="flex justify-between items-center my-4">
                        <form action="{{ route('admin.approvals.history_approved') }}" method="GET">
                            <input type="text" name="search" placeholder="Cari nama karyawan..." value="{{ $search ?? '' }}" class="rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600">
                            <button type="submit" class="ml-2 px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">Cari</button>
                        </form>
                        <button @click="exportModal = true" class="inline-flex items-center px-4 py-2 bg-green-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-600">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            Export Data
                        </button>
                    </div>

                    <div x-show="exportModal" @keydown.escape.window="exportModal = false" class="fixed inset-0 bg-opacity-75 overflow-y-auto h--md w-md z-50 flex items-center justify-center px-2" style="display: none;">
                        <div @click.away="exportModal = false" class="relative p-25 border max-w-md max-h-md shadow-lg rounded-md bg-white dark:bg-gray-900">
                            <div class="mt-10">
                                <br><br>
                                <h3 class="text-lg leading-6  mt-5 font-medium text-gray-900 dark:text-gray-100 text-center">Filter Laporan Absensi</h3>
                                <form id="export-form" action="{{ route('admin.approvals.export') }}" method="POST">
                                    @csrf
                                    <div class="mt-6 px-4 py-3 space-y-6">
                                        
                                        {{-- Filter Tanggal & Dropdown --}}
                                        <div class="space-y-4">
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                                <div>
                                                    <label for="start_date" class="font-medium text-sm text-gray-700 dark:text-gray-300">Dari Tanggal:</label>
                                                    <input type="date" id="start_date" name="start_date" class="mt-1 block w-full rounded-md dark:bg-gray-700 text-sm border-gray-600 shadow-sm">
                                                </div>
                                                <div>
                                                    <label for="end_date" class="font-medium text-sm text-gray-700 dark:text-gray-300">Sampai Tanggal:</label>
                                                    <input type="date" id="end_date" name="end_date" class="mt-1 block w-full rounded-md dark:bg-gray-700 text-sm border-gray-600 shadow-sm">
                                                </div>
                                                <div>
                                                    <label for="department" class="font-medium text-sm text-gray-700 dark:text-gray-300">Departemen:</label>
                                                    <select id="department" name="department" class="mt-1 block w-full rounded-md dark:bg-gray-700 text-sm border-gray-600 shadow-sm">
                                                        <option value="">Semua Departemen</option>
                                                        @foreach($departments as $department)
                                                            <option value="{{ $department }}">{{ $department }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div>
                                                    <label for="terminal_alias" class="font-medium text-sm text-gray-700 dark:text-gray-300">Terminal:</label>
                                                    <select id="terminal_alias" name="terminal_alias" class="mt-1 block w-full rounded-md dark:bg-gray-700 text-sm border-gray-600 shadow-sm">
                                                        <option value="">Semua Terminal</option>
                                                        @foreach($terminals as $terminal)
                                                            <option value="{{ $terminal }}">{{ $terminal }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>


                                        <div>
                                            <label class="font-medium text-sm text-gray-700 dark:text-gray-300">Kolom untuk Ekspor:</label>
                                            {{-- PERBAIKAN: Menggunakan flexbox untuk layout menyamping yang bisa wrap --}}

                                            <div class="flex flex-wrap items-center gap-x-6 gap-y-2 mt-2 text-sm text-gray-600 dark:text-gray-400">
                                                <div><label class="flex items-center whitespace-nowrap"><input type="checkbox" name="columns[]" value="first_name" checked class="rounded mr-2 ml-4"> Nama Depan  </label></div>
                                                <div><label class="flex items-center whitespace-nowrap"><input type="checkbox" name="columns[]" value="emp_code" checked class="rounded mr-2 ml-4"> Kode Karyawan</label></div>
                                                <div><label class="flex items-center whitespace-nowrap"><input type="checkbox" name="columns[]" value="last_name"  class="rounded mr-2 ml-4"> Nama Belakang</label></div>
                                                <div><label class="flex items-center whitespace-nowrap"><input type="checkbox" name="columns[]" value="department" checked class="rounded mr-2 ml-4"> Departemen  </label></div>
                                                <div><label class="flex items-center whitespace-nowrap"><input type="checkbox" name="columns[]" value="position" checked class="rounded mr-2 ml-4"> Posisi</label></div>
                                                <div><label class="flex items-center whitespace-nowrap"><input type="checkbox" name="columns[]" value="punch_time" checked class="mr-2 ml-4"> Waktu Absen</label></div>
                                                <div><label class="flex items-center whitespace-nowrap"><input type="checkbox" name="columns[]" value="punch_state_display" class="rounded mr-2 ml-4"> Status Absen</label></div>
                                                <div><label class="flex items-center whitespace-nowrap"><input type="checkbox" name="columns[]" value="verify_type_display" class="rounded mr-2 ml-4"> Tipe Verifikasi</label></div>
                                                <div><label class="flex items-center whitespace-nowrap"><input type="checkbox" name="columns[]" value="terminal_alias" checked class="rounded mr-2 ml-4"> Alias Terminal</label></div>
                                                <div><label class="flex items-center whitespace-nowrap"><input type="checkbox" name="columns[]" value="gps_location" class="rounded mr-2 ml-4"> Lokasi GPS</label></div>
                                                <div><label class="flex items-center whitespace-nowrap"><input type="checkbox" name="columns[]" value="temperature" class="rounded mr-2 ml-4"> Suhu</label></div>
                                                <div><label class="flex items-center whitespace-nowrap"><input type="checkbox" name="columns[]" value="is_mask" class="rounded mr-2 ml-4"> Pakai Masker</label></div>
                                            </div>
                                        </div>

                                        {{-- Format Ekspor --}}
                                        <div>
                                            <label for="format" class="font-medium text-sm text-gray-700 dark:text-gray-300">Format:</label>
                                            <select id="format" name="format" class="mt-1 block w-full rounded-md dark:bg-gray-700 text-sm border-gray-600 shadow-sm">
                                                <option value="excel">Excel (.xlsx)</option>
                                                <option value="pdf">PDF</option>
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <div class="px-4 py-3 bg-gray-50 dark:bg-gray-800 rounded-b-md">
                                        <br>
                                        <div class="flex justify-end items-center space-x-4">
                                            <button type="button" @click="exportModal = false" class="text-sm font-medium text-gray-600 dark:text-gray-400 hover:underline">Batal</button>
                                            <button type="submit" id="submit-export-btn" class="px-6 py-2 bg-green-500 text-white text-base font-medium rounded-md shadow-sm hover:bg-green-600 border border-green-600">Download</button>
                                            
                                        </div>
                                        <br>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    
                    <div class="overflow-x-auto mt-6">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Karyawan</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Waktu Absen</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Verifikasi</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Terminal</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Detail</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse ($transactions as $transaction)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $transaction['first_name'] ?? 'N/A' }} {{ $transaction['last_name'] ?? '' }}</div>
                                            <div class="text-sm text-gray-500 dark:text-gray-400">{{ $transaction['emp_code'] ?? '' }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            {{ \Carbon\Carbon::parse($transaction['punch_time'])->format('d M Y, H:i:s') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $transaction['punch_state_display'] ?? 'N/A' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $transaction['verify_type_display'] ?? 'N/A' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $transaction['terminal_alias'] ?? 'N/A' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            <div class="flex">
                                                <span class="font-semibold w-16">Dept:</span>
                                                <span>{{ $transaction['department'] ?? 'N/A' }}</span>
                                            </div>
                                            <div class="flex">
                                                <span class="font-semibold w-16">Posisi:</span>
                                                <span>{{ $transaction['position'] ?? 'N/A' }}</span>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">
                                            Tidak ada data yang cocok dengan pencarian atau filter Anda.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $transactions->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const exportForm = document.getElementById('export-form');
            if (exportForm) {
                exportForm.addEventListener('submit', function (e) {
                    e.preventDefault(); 
                    
                    const alpineComponent = e.target.closest('[x-data]');
                    const alpineData = alpineComponent.__x.data;

                    alpineData.isExporting = true;

                    const formData = new FormData(exportForm);

                    fetch(exportForm.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                            'Accept': 'application/json',
                        }
                    })
                    .then(response => {
                        if (!response.ok) {
                            return response.json().then(err => { throw err; });
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            const link = document.createElement('a');
                            link.href = data.download_url;
                            document.body.appendChild(link);
                            link.click();
                            document.body.removeChild(link);
                            
                            window.location.href = "{{ route('admin.approvals.history_approved') }}?export_success=1";
                        } else {
                            alert('Gagal: ' + (data.message || 'Terjadi kesalahan saat membuat file.'));
                            alpineData.isExporting = false;
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Terjadi kesalahan. Periksa console untuk detail.');
                        alpineData.isExporting = false;
                    });
                });
            }
        });
    </script>
    @endpush
</x-app-layout>

