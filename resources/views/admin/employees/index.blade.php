<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Data Karyawan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <!-- Formulir Pencarian -->
                    <div class="mb-4">
                        <form action="{{ route('admin.employees.index') }}" method="GET">
                            <div class="flex">
                                <input type="text" name="search" placeholder="Cari berdasarkan nama atau ID..." class="w-full rounded-l-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600" value="{{ request('search') }}">
                                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white font-semibold rounded-r-md hover:bg-indigo-700">Cari</button>
                            </div>
                        </form>
                    </div>

                    <!-- Tabel Karyawan -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    @php
                                        // Helper untuk membuat link sorting
                                        function sort_link($field, $label, $currentSortBy, $currentSortDir) {
                                            $newSortDir = ($currentSortBy == $field && $currentSortDir == 'asc') ? 'desc' : 'asc';
                                            return '<a href="' . route('admin.employees.index', array_merge(request()->query(), ['sort_by' => $field, 'sort_dir' => $newSortDir])) . '">' . $label . '</a>';
                                        }
                                    @endphp
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{!! sort_link('emp_code', 'ID Karyawan', $sortBy, $sortDir) !!}</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{!! sort_link('first_name', 'Nama', $sortBy, $sortDir) !!}</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{!! sort_link('department', 'Departemen', $sortBy, $sortDir) !!}</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{!! sort_link('position', 'Posisi', $sortBy, $sortDir) !!}</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse ($employees as $employee)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $employee['emp_code'] }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">{{ trim(($employee['first_name'] ?? '') . ' ' . ($employee['last_name'] ?? '')) }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $employee['department_name'] ?? ($employee['department']['name'] ?? 'N/A') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $employee['position_name'] ?? ($employee['position']['name'] ?? 'N/A') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-4 text-center text-gray-500">Tidak ada data karyawan ditemukan.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Paginasi -->
                    <div class="mt-6">
                        {{ $employees->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
