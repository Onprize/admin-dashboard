<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function index()
    {
        try {
            $response = $this->httpGet(config('api.base_url') . '/admin/settings');

            if ($response->failed()) {
                if ($response->status() === 401) {
                    return redirect('/login');
                }
                return view('settings')->with('error', 'Failed to load settings');
            }

            $data = $response->json();

            return view('settings', [
                'settings' => $data['data'] ?? $data,
            ]);
        } catch (\Exception $e) {
            return view('settings')->with('error', 'Connection error');
        }
    }

    public function update(Request $request, $key)
    {
        try {
            $response = $this->httpPut(config('api.base_url') . "/admin/settings/{$key}", [
                'value' => $request->input('value'),
            ]);

            if ($response->failed()) {
                return back()->with('error', $response->json('message') ?? 'Update failed');
            }

            return back()->with('success', 'Setting updated successfully');
        } catch (\Exception $e) {
            return back()->with('error', 'Connection error');
        }
    }
}
