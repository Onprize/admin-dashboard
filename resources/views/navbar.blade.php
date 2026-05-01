<!-- Top Navbar -->
<header class="bg-white border-b border-gray-100 px-6 py-4 flex justify-between items-center shadow-sm">
    <div>
        <h2 class="text-xl font-bold text-gray-900">@yield('page-title', 'Dashboard')</h2>
        @yield('page-subtitle')
    </div>

    <div class="flex items-center space-x-4">
        @if (session('admin_user'))
            <div class="text-right hidden sm:block">
                <p class="text-sm font-semibold text-gray-900">{{ session('admin_user')['name'] ?? 'Admin' }}</p>
                <p class="text-xs text-gray-500">{{ session('admin_user')['email'] ?? 'admin@example.com' }}</p>
            </div>
            <div class="w-10 h-10 bg-gradient-to-br from-red-500 to-orange-500 rounded-full flex items-center justify-center shadow-md">
                <span class="text-white font-bold">{{ substr(session('admin_user')['name'] ?? 'A', 0, 1) }}</span>
            </div>
        @endif
    </div>
</header>
