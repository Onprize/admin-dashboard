@extends('app')

@section('title', 'Orders - On Prize Admin')
@section('page-title', 'Orders')
@section('page-subtitle', '<p class="text-gray-600 text-sm">Track and manage orders</p>')

@section('content')
    <!-- Search and Filter -->
    <div class="mb-6 bg-white rounded-xl shadow-sm border border-gray-100 p-4">
        <form action="{{ route('orders.index') }}" method="GET" class="flex gap-4">
            <div class="flex-1 relative">
                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </span>
                <input type="text" name="search" placeholder="Search orders by ID..." value="{{ $search }}"
                    class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
            </div>
            <select name="status" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
                <option value="">All Status</option>
                <option value="pending" {{ $status === 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="confirmed" {{ $status === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                <option value="preparing" {{ $status === 'preparing' ? 'selected' : '' }}>Preparing</option>
                <option value="ready" {{ $status === 'ready' ? 'selected' : '' }}>Ready</option>
                <option value="completed" {{ $status === 'completed' ? 'selected' : '' }}>Completed</option>
                <option value="cancelled" {{ $status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
            </select>
            <button type="submit" class="px-6 py-2 bg-gradient-to-r from-red-600 to-orange-500 text-white rounded-lg hover:from-red-700 hover:to-orange-600 transition-all font-medium shadow-md">
                Search
            </button>
        </form>
    </div>

    <!-- Orders Table -->
    @if (!empty($orders))
        <div class="bg-white rounded-xl shadow-sm border border-gray-100">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-100">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Order ID</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">User</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Restaurant</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Total</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($orders as $order)
                            <tr class="border-b border-gray-100 hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4">
                                    <a href="{{ route('orders.show', $order['id']) }}" class="text-red-600 hover:text-red-700 font-semibold">
                                        #{{ $order['id'] }}
                                    </a>
                                </td>
                                <td class="px-6 py-4 text-gray-600">{{ $order['user_name'] ?? 'N/A' }}</td>
                                <td class="px-6 py-4 text-gray-600">{{ $order['restaurant_name'] ?? 'N/A' }}</td>
                                <td class="px-6 py-4 font-semibold text-gray-900">₹{{ $order['total'] ?? '0' }}</td>
                                <td class="px-6 py-4">
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold
                                        {{ $order['status'] === 'completed' ? 'bg-green-100 text-green-800' : 
                                           ($order['status'] === 'cancelled' ? 'bg-red-100 text-red-800' :
                                           ($order['status'] === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-blue-100 text-blue-800')) }}">
                                        {{ ucfirst($order['status'] ?? 'pending') }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-gray-600 text-sm">{{ $order['created_at'] ?? 'N/A' }}</td>
                                <td class="px-6 py-4 text-sm">
                                    <a href="{{ route('orders.show', $order['id']) }}" class="text-red-600 hover:text-red-700 font-medium">View</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if (!empty($pagination) && isset($pagination['total']) && $pagination['total'] > 0)
                <div class="px-6 py-4 border-t border-gray-100 flex justify-between items-center bg-gray-50 rounded-b-xl">
                    <p class="text-gray-600 text-sm">Showing {{ count($orders) }} of {{ $pagination['total'] ?? 0 }} orders</p>
                    <div class="flex gap-2">
                        @if ($pagination['current_page'] > 1)
                            <a href="{{ route('orders.index', ['page' => $pagination['current_page'] - 1, 'search' => $search, 'status' => $status]) }}"
                                class="px-4 py-2 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 text-sm font-medium transition-colors">← Previous</a>
                        @endif

                        @if ($pagination['current_page'] < $pagination['last_page'])
                            <a href="{{ route('orders.index', ['page' => $pagination['current_page'] + 1, 'search' => $search, 'status' => $status]) }}"
                                class="px-4 py-2 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 text-sm font-medium transition-colors">Next →</a>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    @else
        <div class="bg-white rounded-xl shadow-sm p-12 text-center border border-gray-100">
            <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
            </svg>
            <p class="text-gray-500 font-medium">No orders found</p>
        </div>
    @endif
@endsection
