<nav x-data="{ open: false }" class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo Diganti dengan Nama Website -->
                <div class="shrink-0 flex items-center">
                    {{-- Logo ini akan mengarah ke halaman yang sesuai tergantung peran user --}}
                    <a href="{{ Auth::user()->is_admin ? route('admin.approvals.index') : route('absensi.create') }}" class="flex items-center">
                        {{-- Ganti SVG dengan tag <img> --}}
                        <img src="data:image/jpeg;base64,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" alt="Logo Absensi" class="h-8 w-8 me-2 object-cover">

                        <span class="font-bold text-lg text-gray-800 dark:text-gray-200">
                            Absensi Karyawan
                        </span>
                    </a>
                </div>

                <!-- Navigation Links untuk Admin -->
                @if(Auth::user()->is_admin)
                    <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                        <x-nav-link :href="route('admin.approvals.index')" :active="request()->routeIs('admin.approvals.*')">
                            {{ __('Kelola Absen') }}
                        </x-nav-link>
                        <x-nav-link :href="route('admin.locations.index')" :active="request()->routeIs('admin.locations.*')">
                            {{ __('Kelola Lokasi') }}
                        </x-nav-link>
                        {{-- Link Baru Ditambahkan di Sini --}}
                        <x-nav-link :href="route('admin.employees.index')" :active="request()->routeIs('admin.employees.index')">
                            {{ __('Data Karyawan') }}
                        </x-nav-link>
                        {{-- LINK BARU DITAMBAHKAN DI SINI --}}
                        {{-- <x-nav-link :href="route('admin.biophotos.index')" :active="request()->routeIs('admin.biophotos.index')">
                            {{ __('Foto Registrasi') }}
                        </x-nav-link> --}}
                        {{-- LINK BARU DITAMBAHKAN DI SINI --}}
                        <x-nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.index')">
                            {{ __('Kelola Users') }}
                        </x-nav-link>
                        <x-nav-link :href="route('admin.api.index')" :active="request()->routeIs('admin.api.index')">
                            {{ __('Kelola API') }}
                        </x-nav-link>
                    </div>
                @endif
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }}</div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                     onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-900 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-900 focus:text-gray-500 dark:focus:text-gray-400 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <!-- Link Admin untuk Tampilan Mobile -->
            @if(Auth::user()->is_admin)
                <x-responsive-nav-link :href="route('admin.approvals.index')" :active="request()->routeIs('admin.approvals.*')">
                    {{ __('Persetujuan Absen') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.locations.index')" :active="request()->routeIs('admin.locations.*')">
                    {{ __('Kelola Lokasi') }}
                </x-responsive-nav-link>
                {{-- Link Baru Ditambahkan di Sini Juga --}}
                 <x-responsive-nav-link :href="route('admin.employees.index')" :active="request()->routeIs('admin.employees.index')">
                    {{ __('Data Karyawan') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.index')">
                    {{ __('Kelola Users') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.api.index')" :active="request()->routeIs('admin.api.index')">
                    {{ __('Kelola API') }}
                </x-responsive-nav-link>
            @endif
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-600">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800 dark:text-gray-200">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>

