@extends('app')

@section('title', 'Order Details - On Prize Admin')
@section('page-title', 'Order Details')
@section('page-subtitle', '<p class="text-gray-600 text-sm">View order information</p>')

@section('content')
    @if (!empty($order))
        <div class="bg-white rounded-lg shadow">
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">Order #{{ $order['id'] ?? 'N/A' }}</h2>
                        <p class="text-gray-600">{{ $order['created_at'] ?? 'N/A' }}</p>
                    </div>
                    <a href="{{ route('orders.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                        ← Back to Orders
                    </a>
                </div>
            </div>

            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-sm text-gray-600">Customer</p>
                        <p class="font-medium text-gray-900">{{ $order['user_name'] ?? 'N/A' }}</p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-sm text-gray-600">Restaurant</p>
                        <p class="font-medium text-gray-900">{{ $order['restaurant_name'] ?? 'N/A' }}</p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-sm text-gray-600">Total</p>
                        <p class="font-bold text-2xl text-gray-900">₹{{ $order['total'] ?? '0' }}</p>
                    </div>
                </div>

                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Order Status</h3>
                    <span class="px-4 py-2 rounded-full text-sm font-medium
                        {{ $order['status'] === 'completed' ? 'bg-green-100 text-green-800' : 
                           ($order['status'] === 'cancelled' ? 'bg-red-100 text-red-800' :
                           ($order['status'] === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-blue-100 text-blue-800')) }}">
                        {{ ucfirst($order['status'] ?? 'pending') }}
                    </span>
                </div>

                @if (isset($order['items']) && !empty($order['items']))
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Order Items</h3>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <table class="w-full">
                                <thead>
                                    <tr class="border-b border-gray-200">
                                        <th class="text-left py-2 text-sm font-medium text-gray-700">Item</th>
                                        <th class="text-left py-2 text-sm font-medium text-gray-700">Quantity</th>
                                        <th class="text-right py-2 text-sm font-medium text-gray-700">Price</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($order['items'] as $item)
                                        <tr class="border-b border-gray-200 last:border-0">
                                            <td class="py-2 text-gray-900">{{ $item['name'] ?? 'N/A' }}</td>
                                            <td class="py-2 text-gray-600">{{ $item['quantity'] ?? 0 }}</td>
                                            <td class="py-2 text-right text-gray-900">₹{{ $item['price'] ?? '0' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif

                @if (isset($order['delivery_address']))
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Delivery Address</h3>
                        <p class="text-gray-900">{{ $order['delivery_address'] ?? 'N/A' }}</p>
                    </div>
                @endif
            </div>
        </div>
    @else
        <div class="bg-white rounded-lg shadow p-6 text-center">
            <p class="text-gray-600">Order not found</p>
        </div>
    @endif
@endsection
