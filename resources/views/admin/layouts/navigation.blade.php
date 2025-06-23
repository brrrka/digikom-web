{{-- resources/views/admin/layouts/navigation.blade.php --}}
<nav class="flex flex-1 flex-col">
    <ul role="list" class="flex flex-1 flex-col gap-y-7">
        <li>
            <ul role="list" class="-mx-2 space-y-1">
                <!-- Dashboard -->
                <li>
                    <a href="{{ route('admin.dashboard') }}"
                        class="group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold {{ request()->routeIs('admin.dashboard') ? 'bg-primary-50 text-primary-700' : 'text-gray-700 hover:text-primary-700 hover:bg-primary-50' }}">
                        <svg class="h-6 w-6 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                        </svg>
                        Dashboard
                    </a>
                </li>

                <!-- Praktikum Group -->
                <li>
                    <div class="text-xs font-semibold leading-6 text-gray-400 uppercase tracking-wider mt-6 mb-2">
                        Praktikum</div>
                    <ul role="list" class="space-y-1">
                        <li>
                            <a href="{{ route('admin.praktikums.index') }}"
                                class="group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-medium {{ request()->routeIs('admin.praktikums.*') ? 'bg-primary-50 text-primary-700' : 'text-gray-600 hover:text-primary-700 hover:bg-primary-50' }}">
                                <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M17.593 3.322c1.1.128 1.907 1.077 1.907 2.185V21L12 17.25 4.5 21V5.507c0-1.108.806-2.057 1.907-2.185a48.507 48.507 0 0111.186 0z" />
                                </svg>
                                Praktikum
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.moduls.index') }}"
                                class="group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-medium {{ request()->routeIs('admin.moduls.*') ? 'bg-primary-50 text-primary-700' : 'text-gray-600 hover:text-primary-700 hover:bg-primary-50' }}">
                                <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0118 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
                                </svg>
                                Modul Praktikum
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Inventaris & Peminjaman -->
                <li>
                    <div class="text-xs font-semibold leading-6 text-gray-400 uppercase tracking-wider mt-6 mb-2">
                        Inventaris</div>
                    <ul role="list" class="space-y-1">
                        <li>
                            <a href="{{ route('admin.inventaris.index') }}"
                                class="group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-medium {{ request()->routeIs('admin.inventaris.*') ? 'bg-primary-50 text-primary-700' : 'text-gray-600 hover:text-primary-700 hover:bg-primary-50' }}">
                                <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
                                </svg>
                                Inventaris
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.peminjaman.index') }}"
                                class="group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-medium {{ request()->routeIs('admin.peminjaman.*') ? 'bg-primary-50 text-primary-700' : 'text-gray-600 hover:text-primary-700 hover:bg-primary-50' }}">
                                <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M9 8.25H7.5a2.25 2.25 0 00-2.25 2.25v9a2.25 2.25 0 002.25 2.25h9a2.25 2.25 0 002.25-2.25v-9a2.25 2.25 0 00-2.25-2.25H15M9 12l2 2 4-4m6-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Peminjaman
                                @if ($pendingCount = \App\Models\Peminjaman::where('status', 'diajukan')->count())
                                    <span
                                        class="ml-auto inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        {{ $pendingCount }}
                                    </span>
                                @endif
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Management -->
                <li>
                    <div class="text-xs font-semibold leading-6 text-gray-400 uppercase tracking-wider mt-6 mb-2">
                        Management</div>
                    <ul role="list" class="space-y-1">
                        <li>
                            <a href="{{ route('admin.users.index') }}"
                                class="group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-medium {{ request()->routeIs('admin.users.*') ? 'bg-primary-50 text-primary-700' : 'text-gray-600 hover:text-primary-700 hover:bg-primary-50' }}">
                                <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                                </svg>
                                Pengguna
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.artikel.index') }}"
                                class="group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-medium {{ request()->routeIs('admin.artikel.*') ? 'bg-primary-50 text-primary-700' : 'text-gray-600 hover:text-primary-700 hover:bg-primary-50' }}">
                                <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                </svg>
                                Artikel
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Quick Actions -->
                <li>
                    <div class="text-xs font-semibold leading-6 text-gray-400 uppercase tracking-wider mt-6 mb-2">Quick
                        Actions</div>
                    <ul role="list" class="space-y-1">
                        <li>
                            <a href="{{ route('admin.export.all') }}"
                                class="group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-medium text-gray-600 hover:text-primary-700 hover:bg-primary-50">
                                <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M12 9.75v6.75m0 0l-3-3m3 3l3-3m-8.25 6a4.5 4.5 0 01-1.41-8.775 5.25 5.25 0 0110.233-2.33 3 3 0 013.758 3.848A3.752 3.752 0 0118 19.5H6.75z" />
                                </svg>
                                Export Data
                            </a>
                        </li>
                        <li>
                            <button onclick="checkOverdue()"
                                class="group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-medium text-gray-600 hover:text-orange-700 hover:bg-orange-50 w-full text-left">
                                <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                                </svg>
                                Check Overdue
                            </button>
                        </li>
                    </ul>
                </li>
            </ul>
        </li>
    </ul>
</nav>

<script>
    function checkOverdue() {
        fetch('/admin/peminjaman/check-overdue', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    // Refresh jika ada update
                    if (window.location.pathname.includes('peminjaman')) {
                        location.reload();
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
    }
</script>
