@extends('app')

@section('title', 'User Details - On Prize Admin')
@section('page-title', 'User Details')
@section('page-subtitle', '<p class="text-gray-600 text-sm">View user information</p>')

@section('content')
    @if (!empty($user))
        <div class="bg-white rounded-lg shadow">
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center">
                            <span class="text-2xl font-bold text-red-600">{{ substr($user['name'] ?? 'U', 0, 1) }}</span>
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900">{{ $user['name'] ?? 'N/A' }}</h2>
                            <p class="text-gray-600">{{ $user['email'] ?? 'N/A' }}</p>
                        </div>
                    </div>
                    <a href="{{ route('users.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                        ← Back to Users
                    </a>
                </div>
            </div>

            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Personal Information</h3>
                        <div class="space-y-3">
                            <div>
                                <p class="text-sm text-gray-600">Full Name</p>
                                <p class="font-medium text-gray-900">{{ $user['name'] ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Email</p>
                                <p class="font-medium text-gray-900">{{ $user['email'] ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Phone</p>
                                <p class="font-medium text-gray-900">{{ $user['phone'] ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Account Information</h3>
                        <div class="space-y-3">
                            <div>
                                <p class="text-sm text-gray-600">Role</p>
                                <p class="font-medium text-gray-900">{{ ucfirst($user['role'] ?? 'N/A') }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Status</p>
                                <span class="px-3 py-1 rounded-full text-sm font-medium {{ $user['status'] === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ ucfirst($user['status'] ?? 'inactive') }}
                                </span>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Joined</p>
                                <p class="font-medium text-gray-900">{{ $user['created_at'] ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                @if (isset($user['address']))
                    <div class="mt-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Address</h3>
                        <p class="text-gray-900">{{ $user['address'] ?? 'N/A' }}</p>
                    </div>
                @endif
            </div>
        </div>
    @else
        <div class="bg-white rounded-lg shadow p-6 text-center">
            <p class="text-gray-600">User not found</p>
        </div>
    @endif
@endsection
