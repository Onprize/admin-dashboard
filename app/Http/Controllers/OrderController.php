<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OrderController extends Controller
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

            $response = $this->httpGet(config('api.base_url') . '/admin/orders', $params);

            if ($response->failed()) {
                if ($response->status() === 401) {
                    return redirect('/login');
                }
                return view('orders')->with('error', 'Failed to load orders');
            }

            $data = $response->json();

            return view('orders', [
                'orders' => $data['data'] ?? [],
                'pagination' => $data['meta'] ?? [],
                'search' => $params['search'] ?? '',
                'status' => $params['status'] ?? '',
            ]);
        } catch (\Exception $e) {
            return view('orders')->with('error', 'Connection error');
        }
    }

    public function show($id)
    {
        try {
            $response = $this->httpGet(config('api.base_url') . "/admin/orders/{$id}");

            if ($response->failed()) {
                if ($response->status() === 401) {
                    return redirect('/login');
                }
                return redirect('orders')->with('error', 'Order not found');
            }

            $order = $response->json('data');

            return view('orders.show', ['order' => $order]);
        } catch (\Exception $e) {
            return redirect('orders')->with('error', 'Connection error');
        }
    }
}
