@extends('app')

@section('title', 'Delivery Partner Details - On Prize Admin')
@section('page-title', 'Delivery Partner Details')
@section('page-subtitle', '<p class="text-gray-600 text-sm">View delivery partner information</p>')

@section('content')
    @if (!empty($partner))
        <div class="bg-white rounded-lg shadow">
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center">
                            <span class="text-2xl">🚗</span>
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900">{{ $partner['name'] ?? 'N/A' }}</h2>
                            <p class="text-gray-600">{{ $partner['email'] ?? 'N/A' }}</p>
                        </div>
                    </div>
                    <a href="{{ route('delivery-partners.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                        ← Back to Partners
                    </a>
                </div>
            </div>

            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Personal Information</h3>
                        <div class="space-y-3">
                            <div>
                                <p class="text-sm text-gray-600">Name</p>
                                <p class="font-medium text-gray-900">{{ $partner['name'] ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Email</p>
                                <p class="font-medium text-gray-900">{{ $partner['email'] ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Phone</p>
                                <p class="font-medium text-gray-900">{{ $partner['phone'] ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Vehicle Information</h3>
                        <div class="space-y-3">
                            <div>
                                <p class="text-sm text-gray-600">Vehicle Type</p>
                                <p class="font-medium text-gray-900">{{ ucfirst($partner['vehicle_type'] ?? 'N/A') }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Vehicle Number</p>
                                <p class="font-medium text-gray-900">{{ $partner['vehicle_number'] ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">License Number</p>
                                <p class="font-medium text-gray-900">{{ $partner['license_number'] ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Status Information</h3>
                        <div class="space-y-3">
                            <div>
                                <p class="text-sm text-gray-600">Status</p>
                                <span class="px-3 py-1 rounded-full text-sm font-medium {{ ($partner['is_active'] ?? false) ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ ($partner['is_active'] ?? false) ? 'Active' : 'Inactive' }}
                                </span>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Verification Status</p>
                                <span class="px-3 py-1 rounded-full text-sm font-medium {{ ($partner['is_verified'] ?? false) ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                    {{ ($partner['is_verified'] ?? false) ? 'Verified' : 'Pending' }}
                                </span>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Joined</p>
                                <p class="font-medium text-gray-900">{{ $partner['created_at'] ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>

                    @if (isset($partner['bank_name']))
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Bank Information</h3>
                            <div class="space-y-3">
                                <div>
                                    <p class="text-sm text-gray-600">Bank Name</p>
                                    <p class="font-medium text-gray-900">{{ $partner['bank_name'] ?? 'N/A' }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">Account Number</p>
                                    <p class="font-medium text-gray-900">{{ $partner['account_number'] ?? 'N/A' }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">IFSC Code</p>
                                    <p class="font-medium text-gray-900">{{ $partner['ifsc_code'] ?? 'N/A' }}</p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                @if (!($partner['is_verified'] ?? false))
                    <div class="mt-6 flex gap-4">
                        <form action="{{ route('delivery-partners.verify', $partner['id']) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-medium">
                                Verify
                            </button>
                        </form>
                        <form action="{{ route('delivery-partners.reject', $partner['id']) }}" method="POST">
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
            <p class="text-gray-600">Delivery partner not found</p>
        </div>
    @endif
@endsection
