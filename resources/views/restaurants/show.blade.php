@extends('app')

@section('title', 'Restaurant Details - On Prize Admin')
@section('page-title', 'Restaurant Details')
@section('page-subtitle', '<p class="text-gray-600 text-sm">View restaurant information</p>')

@section('content')
    @if (!empty($restaurant))
        <div class="bg-white rounded-lg shadow">
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center">
                            <span class="text-2xl">🍽️</span>
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900">{{ $restaurant['name'] ?? 'N/A' }}</h2>
                            <p class="text-gray-600">{{ $restaurant['email'] ?? 'N/A' }}</p>
                        </div>
                    </div>
                    <a href="{{ route('restaurants.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                        ← Back to Restaurants
                    </a>
                </div>
            </div>

            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Restaurant Information</h3>
                        <div class="space-y-3">
                            <div>
                                <p class="text-sm text-gray-600">Name</p>
                                <p class="font-medium text-gray-900">{{ $restaurant['name'] ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Owner</p>
                                <p class="font-medium text-gray-900">{{ $restaurant['owner_name'] ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Email</p>
                                <p class="font-medium text-gray-900">{{ $restaurant['email'] ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Phone</p>
                                <p class="font-medium text-gray-900">{{ $restaurant['phone'] ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Status Information</h3>
                        <div class="space-y-3">
                            <div>
                                <p class="text-sm text-gray-600">Status</p>
                                <span class="px-3 py-1 rounded-full text-sm font-medium {{ ($restaurant['is_active'] ?? false) ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ ($restaurant['is_active'] ?? false) ? 'Active' : 'Inactive' }}
                                </span>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Approval Status</p>
                                <span class="px-3 py-1 rounded-full text-sm font-medium {{ ($restaurant['approval_status'] ?? 'pending') === 'approved' ? 'bg-green-100 text-green-800' : (($restaurant['approval_status'] ?? 'pending') === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                    {{ ucfirst($restaurant['approval_status'] ?? 'pending') }}
                                </span>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Registered</p>
                                <p class="font-medium text-gray-900">{{ $restaurant['created_at'] ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                @if (isset($restaurant['address']))
                    <div class="mt-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Address</h3>
                        <p class="text-gray-900">{{ $restaurant['address'] ?? 'N/A' }}</p>
                    </div>
                @endif

                @if ($restaurant['approval_status'] === 'pending')
                    <div class="mt-6 flex gap-4">
                        <form action="{{ route('restaurants.approve', $restaurant['id']) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-medium">
                                Approve
                            </button>
                        </form>
                        <form action="{{ route('restaurants.reject', $restaurant['id']) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="px-6 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors font-medium">
                                Reject
                            </button>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    @else
        <div class="bg-white rounded-lg shadow p-6 text-center">
            <p class="text-gray-600">Restaurant not found</p>
        </div>
    @endif
@endsection
