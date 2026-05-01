@extends('app')

@section('title', 'Settings - On Prize Admin')
@section('page-title', 'Settings')
@section('page-subtitle', '<p class="text-gray-600 text-sm">Configure application settings</p>')

@section('content')
    @if (!empty($settings))
        <div class="space-y-6">
            @foreach ($settings as $key => $setting)
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">{{ ucfirst(str_replace('_', ' ', $key)) }}</h3>
                            @if (isset($setting['description']))
                                <p class="text-gray-600 text-sm mt-1">{{ $setting['description'] }}</p>
                            @endif
                        </div>
                    </div>

                    <form action="{{ route('settings.update', $key) }}" method="POST" class="flex gap-4">
                        @csrf
                        @method('PUT')

                        @if (is_array($setting) && isset($setting['value']))
                            @if ($setting['type'] === 'boolean')
                                <select name="value" class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
                                    <option value="1" {{ $setting['value'] ? 'selected' : '' }}>Enabled</option>
                                    <option value="0" {{ !$setting['value'] ? 'selected' : '' }}>Disabled</option>
                                </select>
                            @elseif ($setting['type'] === 'number')
                                <input type="number" name="value" value="{{ $setting['value'] }}"
                                    class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
                            @else
                                <textarea name="value" rows="3"
                                    class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">{{ $setting['value'] }}</textarea>
                            @endif
                        @else
                            <input type="text" name="value" value="{{ $setting ?? '' }}"
                                class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
                        @endif

                        <button type="submit" class="px-6 py-2 bg-gradient-to-r from-red-600 to-orange-500 text-white rounded-lg hover:from-red-700 hover:to-orange-600 transition-all font-medium whitespace-nowrap shadow-md">
                            Save
                        </button>
                    </form>
                </div>
            @endforeach
        </div>
    @else
        <div class="bg-white rounded-xl shadow-sm p-12 text-center border border-gray-100">
            <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            <p class="text-gray-500 font-medium">No settings available</p>
        </div>
    @endif
@endsection
