<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RestaurantController extends Controller
{
    public function index(Request $request)
    {
        try {
            $params = [
                'page' => $request->input('page', 1),
                'per_page' => $request->input('per_page', 15),
                'search' => $request->input('search', ''),
                'status' => $request->input('status', ''),
            ];

            $response = $this->httpGet(config('api.base_url') . '/admin/restaurants', $params);

            if ($response->failed()) {
                if ($response->status() === 401) {
                    return redirect('/login');
                }
                return view('restaurants')->with('error', 'Failed to load restaurants');
            }

            $data = $response->json();

            return view('restaurants', [
                'restaurants' => $data['data'] ?? [],
                'pagination' => $data['meta'] ?? [],
                'search' => $params['search'] ?? '',
                'status' => $params['status'] ?? '',
            ]);
        } catch (\Exception $e) {
            return view('restaurants')->with('error', 'Connection error');
        }
    }

    public function show($id)
    {
        try {
            $response = $this->httpGet(config('api.base_url') . "/admin/restaurants/{$id}");

            if ($response->failed()) {
                if ($response->status() === 401) {
                    return redirect('/login');
                }
                return redirect('restaurants')->with('error', 'Restaurant not found');
            }

            $restaurant = $response->json('data');

            return view('restaurants.show', ['restaurant' => $restaurant]);
        } catch (\Exception $e) {
            return redirect('restaurants')->with('error', 'Connection error');
        }
    }

    public function approve($id)
    {
        try {
            $response = $this->httpPut(config('api.base_url') . "/admin/restaurants/{$id}/approve");

            if ($response->failed()) {
                return back()->with('error', $response->json('message') ?? 'Approval failed');
            }

            return back()->with('success', 'Restaurant approved successfully');
        } catch (\Exception $e) {
            return back()->with('error', 'Connection error');
        }
    }

    public function reject($id)
    {
        try {
            $response = $this->httpPut(config('api.base_url') . "/admin/restaurants/{$id}/reject");

            if ($response->failed()) {
                return back()->with('error', $response->json('message') ?? 'Rejection failed');
            }

            return back()->with('success', 'Restaurant rejected successfully');
        } catch (\Exception $e) {
            return back()->with('error', 'Connection error');
        }
    }

    public function toggleStatus($id)
    {
        try {
            $response = $this->httpPut(config('api.base_url') . "/admin/restaurants/{$id}/toggle-status");

            if ($response->failed()) {
                return back()->with('error', $response->json('message') ?? 'Update failed');
            }

            return back()->with('success', 'Restaurant status updated successfully');
        } catch (\Exception $e) {
            return back()->with('error', 'Connection error');
        }
    }
}
