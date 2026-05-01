@extends('app')

@section('title', 'Users - On Prize Admin')
@section('page-title', 'Users')
@section('page-subtitle', '<p class="text-gray-600 text-sm">Manage platform users</p>')

@section('content')
    <!-- Search and Filter -->
    <div class="mb-6 bg-white rounded-xl shadow-sm border border-gray-100 p-4">
        <form action="{{ route('users.index') }}" method="GET" class="flex gap-4">
            <div class="flex-1 relative">
                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </span>
                <input type="text" name="search" placeholder="Search users by name or email..." value="{{ $search }}"
                    class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
            </div>
            <button type="submit" class="px-6 py-2 bg-gradient-to-r from-red-600 to-orange-500 text-white rounded-lg hover:from-red-700 hover:to-orange-600 transition-all font-medium shadow-md">
                Search
            </button>
        </form>
    </div>

    <!-- Users Table -->
    @if (!empty($users))
        <div class="bg-white rounded-xl shadow-sm border border-gray-100">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-100">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Name</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Email</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Phone</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Joined</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                            <tr class="border-b border-gray-100 hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center mr-3">
                                            <span class="text-red-600 font-semibold text-sm">{{ substr($user['name'] ?? 'U', 0, 1) }}</span>
                                        </div>
                                        <a href="{{ route('users.show', $user['id']) }}" class="text-red-600 hover:text-red-700 font-semibold">
                                            {{ $user['name'] ?? 'N/A' }}
                                        </a>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-gray-600">{{ $user['email'] ?? 'N/A' }}</td>
                                <td class="px-6 py-4 text-gray-600">{{ $user['phone'] ?? 'N/A' }}</td>
                                <td class="px-6 py-4">
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold {{ ($user['status'] ?? 'inactive') === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ ucfirst($user['status'] ?? 'inactive') }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-gray-600 text-sm">{{ $user['created_at'] ?? 'N/A' }}</td>
                                <td class="px-6 py-4 text-sm">
                                    <a href="{{ route('users.show', $user['id']) }}" class="text-red-600 hover:text-red-700 font-medium">View</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if (!empty($pagination) && isset($pagination['total']) && $pagination['total'] > 0)
                <div class="px-6 py-4 border-t border-gray-100 flex justify-between items-center bg-gray-50 rounded-b-xl">
                    <p class="text-gray-600 text-sm">Showing {{ count($users) }} of {{ $pagination['total'] ?? 0 }} users</p>
                    <div class="flex gap-2">
                        @if ($pagination['current_page'] > 1)
                            <a href="{{ route('users.index', ['page' => $pagination['current_page'] - 1, 'search' => $search]) }}"
                                class="px-4 py-2 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 text-sm font-medium transition-colors">← Previous</a>
                        @endif

                        @if ($pagination['current_page'] < $pagination['last_page'])
                            <a href="{{ route('users.index', ['page' => $pagination['current_page'] + 1, 'search' => $search]) }}"
                                class="px-4 py-2 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 text-sm font-medium transition-colors">Next →</a>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    @else
        <div class="bg-white rounded-xl shadow-sm p-12 text-center border border-gray-100">
            <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
            </svg>
            <p class="text-gray-500 font-medium">No users found</p>
        </div>
    @endif
@endsection
