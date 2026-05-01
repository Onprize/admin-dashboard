<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DeliveryPartnerController extends Controller
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

            $response = $this->httpGet(config('api.base_url') . '/admin/delivery-partners', $params);

            if ($response->failed()) {
                if ($response->status() === 401) {
                    return redirect('/login');
                }
                return view('delivery_partners')->with('error', 'Failed to load partners');
            }

            $data = $response->json();

            return view('delivery_partners', [
                'partners' => $data['data'] ?? [],
                'pagination' => $data['meta'] ?? [],
                'search' => $params['search'] ?? '',
                'status' => $params['status'] ?? '',
            ]);
        } catch (\Exception $e) {
            return view('delivery_partners')->with('error', 'Connection error');
        }
    }

    public function show($id)
    {
        try {
            $response = $this->httpGet(config('api.base_url') . "/admin/delivery-partners/{$id}");

            if ($response->failed()) {
                if ($response->status() === 401) {
                    return redirect('/login');
                }
                return redirect('delivery-partners')->with('error', 'Partner not found');
            }

            $partner = $response->json('data');

            return view('delivery-partners.show', ['partner' => $partner]);
        } catch (\Exception $e) {
            return redirect('delivery-partners')->with('error', 'Connection error');
        }
    }

    public function verify($id)
    {
        try {
            $response = $this->httpPut(config('api.base_url') . "/admin/delivery-partners/{$id}/verify");

            if ($response->failed()) {
                return back()->with('error', $response->json('message') ?? 'Verification failed');
            }

            return back()->with('success', 'Partner verified successfully');
        } catch (\Exception $e) {
            return back()->with('error', 'Connection error');
        }
    }

    public function reject($id)
    {
        try {
            $response = $this->httpPut(config('api.base_url') . "/admin/delivery-partners/{$id}/reject");

            if ($response->failed()) {
                return back()->with('error', $response->json('message') ?? 'Rejection failed');
            }

            return back()->with('success', 'Partner rejected successfully');
        } catch (\Exception $e) {
            return back()->with('error', 'Connection error');
        }
    }

    public function toggleStatus($id)
    {
        try {
            $response = $this->httpPut(config('api.base_url') . "/admin/delivery-partners/{$id}/toggle-status");

            if ($response->failed()) {
                return back()->with('error', $response->json('message') ?? 'Update failed');
            }

            return back()->with('success', 'Partner status updated successfully');
        } catch (\Exception $e) {
            return back()->with('error', 'Connection error');
        }
    }
}
