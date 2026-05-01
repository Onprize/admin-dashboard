@extends('app')

@section('title', 'Delivery Partners - On Prize Admin')
@section('page-title', 'Delivery Partners')
@section('page-subtitle', '<p class="text-gray-600 text-sm">Manage delivery partners and verifications</p>')

@section('content')
    <!-- Search and Filter -->
    <div class="mb-6 bg-white rounded-xl shadow-sm border border-gray-100 p-4">
        <form action="{{ route('delivery-partners.index') }}" method="GET" class="flex gap-4">
            <div class="flex-1 relative">
                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </span>
                <input type="text" name="search" placeholder="Search partners..." value="{{ $search }}"
                    class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
            </div>
            <select name="status" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
                <option value="">All Status</option>
                <option value="pending" {{ $status === 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="verified" {{ $status === 'verified' ? 'selected' : '' }}>Verified</option>
                <option value="rejected" {{ $status === 'rejected' ? 'selected' : '' }}>Rejected</option>
            </select>
            <button type="submit" class="px-6 py-2 bg-gradient-to-r from-red-600 to-orange-500 text-white rounded-lg hover:from-red-700 hover:to-orange-600 transition-all font-medium shadow-md">
                Search
            </button>
        </form>
    </div>

    <!-- Partners Table -->
    @if (!empty($partners))
        <div class="bg-white rounded-xl shadow-sm border border-gray-100">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-100">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Name</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Email</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Phone</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Vehicle</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Verification</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($partners as $partner)
                            <tr class="border-b border-gray-100 hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                            <span class="text-blue-600 font-semibold text-sm">🚗</span>
                                        </div>
                                        <a href="{{ route('delivery-partners.show', $partner['id']) }}" class="text-red-600 hover:text-red-700 font-semibold">
                                            {{ $partner['name'] ?? 'N/A' }}
                                        </a>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-gray-600">{{ $partner['email'] ?? 'N/A' }}</td>
                                <td class="px-6 py-4 text-gray-600">{{ $partner['phone'] ?? 'N/A' }}</td>
                                <td class="px-6 py-4 text-gray-600">{{ ucfirst($partner['vehicle_type'] ?? 'N/A') }}</td>
                                <td class="px-6 py-4">
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold {{ ($partner['is_active'] ?? false) ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ ($partner['is_active'] ?? false) ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold {{ ($partner['is_verified'] ?? false) ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                        {{ ($partner['is_verified'] ?? false) ? 'Verified' : 'Pending' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    <a href="{{ route('delivery-partners.show', $partner['id']) }}" class="text-red-600 hover:text-red-700 font-medium">View</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if (!empty($pagination) && isset($pagination['total']) && $pagination['total'] > 0)
                <div class="px-6 py-4 border-t border-gray-100 flex justify-between items-center bg-gray-50 rounded-b-xl">
                    <p class="text-gray-600 text-sm">Showing {{ count($partners) }} of {{ $pagination['total'] ?? 0 }} partners</p>
                    <div class="flex gap-2">
                        @if ($pagination['current_page'] > 1)
                            <a href="{{ route('delivery-partners.index', ['page' => $pagination['current_page'] - 1, 'search' => $search, 'status' => $status]) }}"
                                class="px-4 py-2 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 text-sm font-medium transition-colors">← Previous</a>
                        @endif

                        @if ($pagination['current_page'] < $pagination['last_page'])
                            <a href="{{ route('delivery-partners.index', ['page' => $pagination['current_page'] + 1, 'search' => $search, 'status' => $status]) }}"
                                class="px-4 py-2 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 text-sm font-medium transition-colors">Next →</a>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    @else
        <div class="bg-white rounded-xl shadow-sm p-12 text-center border border-gray-100">
            <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m0 0V1a2 2 0 012-2h4a2 2 0 012 2v4"/>
            </svg>
            <p class="text-gray-500 font-medium">No delivery partners found</p>
        </div>
    @endif
@endsection
