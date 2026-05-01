<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        try {
            $response = $this->httpGet(config('api.base_url') . '/admin/dashboard');

            if ($response->failed()) {
                if ($response->status() === 401) {
                    return redirect('/login');
                }
                return view('dashboard')->with('error', 'Failed to load dashboard');
            }

            $data = $response->json();

            return view('dashboard', [
                'stats' => $data['stats'] ?? [],
                'recentOrders' => $data['recent_orders'] ?? [],
                'topRestaurants' => $data['top_restaurants'] ?? [],
            ]);
        } catch (\Exception $e) {
            return view('dashboard')->with('error', 'Connection error');
        }
    }
}
