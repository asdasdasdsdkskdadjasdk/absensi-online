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

