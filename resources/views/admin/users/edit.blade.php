<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Pengguna: ') }} {{ $user->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form action="{{ route('admin.users.update', $user) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- Name -->
                        <div>
                            <label for="name" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Nama') }}</label>
                            <input id="name" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:border-gray-600" type="text" name="name" value="{{ old('name', $user->name) }}" required autofocus />
                        </div>

                        <!-- Email Address -->
                        <div class="mt-4">
                            <label for="email" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Email') }}</label>
                            <input id="email" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:border-gray-600" type="email" name="email" value="{{ old('email', $user->email) }}" required />
                        </div>
                        
                        <!-- Employee Code -->
                        <div class="mt-4">
                            <label for="emp_code" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('ID Karyawan (Opsional)') }}</label>
                            <input id="emp_code" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:border-gray-600" type="text" name="emp_code" value="{{ old('emp_code', $user->emp_code) }}" />
                        </div>

                        <!-- Password -->
                        <div class="mt-4">
                            <label for="password" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Password Baru (Kosongkan jika tidak diubah)') }}</label>
                            <input id="password" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:border-gray-600" type="password" name="password" autocomplete="new-password" />
                        </div>

                        <!-- Confirm Password -->
                        <div class="mt-4">
                            <label for="password_confirmation" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Konfirmasi Password Baru') }}</label>
                            <input id="password_confirmation" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:border-gray-600" type="password" name="password_confirmation" />
                        </div>

                        <!-- Role -->
                        <div class="mt-4">
                            <label for="is_admin" class="inline-flex items-center">
                                <input id="is_admin" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="is_admin" value="1" @if(old('is_admin', $user->is_admin)) checked @endif>
                                <span class="ms-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Jadikan sebagai Admin?') }}</span>
                            </label>
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <button type="submit" class="px-4 py-2 bg-indigo-600 text-white font-semibold rounded-md hover:bg-indigo-700">
                                {{ __('Simpan Perubahan') }}
                            </button>
                        </div>
                    </form>
                    
                    <!-- Delete User Form -->
                    <div class="mt-6 border-t border-gray-200 dark:border-gray-700 pt-6">
                         <form method="POST" action="{{ route('admin.users.destroy', $user) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-4 py-2 bg-red-600 text-white font-semibold rounded-md hover:bg-red-700" onclick="return confirm('Apakah Anda yakin ingin menghapus pengguna ini?')">
                                Hapus Pengguna
                            </button>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
