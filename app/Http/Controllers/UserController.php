<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        try {
            $params = [
                'page' => $request->input('page', 1),
                'per_page' => $request->input('per_page', 15),
                'search' => $request->input('search', ''),
            ];

            $response = $this->httpGet(config('api.base_url') . '/admin/users', $params);

            if ($response->failed()) {
                if ($response->status() === 401) {
                    return redirect('/login');
                }
                return view('users')->with('error', 'Failed to load users');
            }

            $data = $response->json();

            return view('users', [
                'users' => $data['data'] ?? [],
                'pagination' => $data['meta'] ?? [],
                'search' => $params['search'] ?? '',
            ]);
        } catch (\Exception $e) {
            return view('users')->with('error', 'Connection error');
        }
    }

    public function show($id)
    {
        try {
            $response = $this->httpGet(config('api.base_url') . "/admin/users/{$id}");

            if ($response->failed()) {
                if ($response->status() === 401) {
                    return redirect('/login');
                }
                return redirect('users')->with('error', 'User not found');
            }

            $user = $response->json('data');

            return view('users.show', ['user' => $user]);
        } catch (\Exception $e) {
            return redirect('users')->with('error', 'Connection error');
        }
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate(['status' => 'required|in:active,inactive,suspended']);

        try {
            $response = $this->httpPut(config('api.base_url') . "/admin/users/{$id}/status", [
                'status' => $request->input('status'),
            ]);

            if ($response->failed()) {
                return back()->with('error', $response->json('message') ?? 'Update failed');
            }

            return back()->with('success', 'User status updated successfully');
        } catch (\Exception $e) {
            return back()->with('error', 'Connection error');
        }
    }
}
