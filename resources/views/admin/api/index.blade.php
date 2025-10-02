<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Pengaturan API Token WDMS') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    
                    {{-- Menampilkan pesan sukses setelah refresh --}}
                    @if (session('success'))
                        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-md" role="alert">
                            <p class="font-bold">Sukses</p>
                            <p>{{ session('success') }}</p>
                        </div>
                    @endif

                    {{-- Menampilkan pesan error jika refresh gagal --}}
                    @if (session('error'))
                        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-md" role="alert">
                            <p class="font-bold">Error</p>
                            <p>{{ session('error') }}</p>
                        </div>
                    @endif

                    <div class="mb-6">
                        <label for="api_token" class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">
                            Token API Saat Ini:
                        </label>
                        <div class="relative flex items-center">
                            {{-- Input untuk menampilkan token --}}
                            <input id="api_token" type="text" value="{{ $apiToken ?? 'Belum ada token. Silakan refresh.' }}" readonly
                                   class="shadow-sm appearance-none border rounded-l-md w-full py-2 px-3 text-gray-700 dark:text-gray-300 bg-gray-50 dark:bg-gray-700 dark:border-gray-600 leading-tight focus:outline-none focus:ring focus:border-blue-300">

                            {{-- Tombol untuk menyalin token --}}
                            <button onclick="copyToken()" title="Salin Token" class="px-4 py-2 bg-blue-500 text-white rounded-r-md hover:bg-blue-600 focus:outline-none focus:ring focus:ring-blue-300 focus:ring-opacity-50">
                                Salin
                            </button>
                        </div>
                        <p id="copy-feedback" class="text-green-600 dark:text-green-400 text-xs italic mt-2" style="display: none;">Token berhasil disalin ke clipboard!</p>
                    </div>

                    <div class="flex items-center justify-between border-t border-gray-200 dark:border-gray-700 pt-6">
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            Klik tombol ini untuk meminta token baru dari server WDMS. <br>
                            Token lama akan langsung diganti.
                        </p>
                        {{-- Form untuk tombol Refresh Token --}}
                        <form action="{{ route('admin.api.refresh') }}" method="POST">
                            @csrf
                            <button type="submit"
                                    class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Refresh Token
                            </button>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <script>
        function copyToken() {
            const tokenInput = document.getElementById('api_token');
            tokenInput.select();
            tokenInput.setSelectionRange(0, 99999); // Kompatibilitas untuk mobile

            try {
                // Gunakan Clipboard API jika tersedia (lebih modern dan aman)
                if (navigator.clipboard) {
                    navigator.clipboard.writeText(tokenInput.value).then(() => {
                        showCopyFeedback();
                    });
                } else {
                    // Fallback untuk browser lama
                    document.execCommand('copy');
                    showCopyFeedback();
                }
            } catch (err) {
                alert('Gagal menyalin token.');
            }
        }

        function showCopyFeedback() {
            const feedback = document.getElementById('copy-feedback');
            feedback.style.display = 'block';
            setTimeout(() => {
                feedback.style.display = 'none';
            }, 2000);
        }
    </script>
</x-app-layout>

