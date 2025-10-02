<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Transaksi Absensi (Data Lengkap)</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-8">
    <div class="max-w-7xl mx-auto bg-white p-6 rounded-lg shadow-md">
        <h1 class="text-2xl font-bold mb-4">Laporan Transaksi Absensi (Data Lengkap)</h1>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        {{-- Membuat header tabel secara dinamis dari data pertama --}}
                        @if($transactions->isNotEmpty())
                            @foreach (array_keys((array)$transactions->first()) as $key)
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ str_replace('_', ' ', $key) }}</th>
                            @endforeach
                        @endif
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    {{-- PERBAIKAN: Menggunakan variabel $transactions --}}
                    @forelse ($transactions as $transaction)
                        <tr>
                            {{-- Menampilkan semua nilai data secara dinamis --}}
                            @foreach ($transaction as $key => $value)
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {{-- Format tanggal agar mudah dibaca --}}
                                    @if(in_array($key, ['punch_time', 'upload_time', 'sync_time']) && $value)
                                        {{ \Carbon\Carbon::parse($value)->format('d M Y, H:i:s') }}
                                    @else
                                        {{ $value }}
                                    @endif
                                </td>
                            @endforeach
                        </tr>
                    @empty
                        <tr>
                            {{-- Menyesuaikan colspan secara dinamis --}}
                            <td colspan="{{ $transactions->isNotEmpty() ? count((array)$transactions->first()) : 1 }}" class="px-6 py-4 text-center text-gray-500">
                                Tidak ada data transaksi yang ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Link Navigasi Halaman -->
        <div class="mt-4">
            {{-- PERBAIKAN: Menggunakan variabel $transactions --}}
            {{ $transactions->links() }}
        </div>
    </div>
</body>
</html>

