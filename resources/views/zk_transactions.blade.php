<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Absensi Lengkap</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-8">
    <div class="max-w-full mx-auto bg-white p-6 rounded-lg shadow-md">
        <h1 class="text-2xl font-bold mb-4">Laporan Transaksi Absensi (Data Lengkap)</h1>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        @if($transactions->isNotEmpty())
                            {{-- Membuat header tabel secara dinamis dari nama kolom data pertama --}}
                            @foreach (array_keys((array) $transactions->first()) as $columnName)
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ str_replace('_', ' ', $columnName) }}</th>
                            @endforeach
                        @endif
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($transactions as $transaction)
                        <tr>
                            {{-- Menampilkan nilai setiap kolom secara dinamis --}}
                            @foreach ($transaction as $value)
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {{-- Mengurangi panjang teks yang terlalu panjang agar tabel rapi --}}
                                    {{ Str::limit($value, 50) }}
                                </td>
                            @endforeach
                        </tr>
                    @empty
                        <tr>
                            <td class="px-6 py-4 text-center text-gray-500">
                                Tidak ada data transaksi yang ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Link Pagination -->
        <div class="mt-4">
            {{ $transactions->links() }}
        </div>
    </div>
</body>
</html>

