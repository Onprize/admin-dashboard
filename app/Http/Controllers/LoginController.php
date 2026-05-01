<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    public function showLogin()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'ngrok-skip-browser-warning' => 'true',
            ])->withoutVerifying()->post(config('api.base_url') . '/auth/login', [
                'identifier' => $credentials['email'],
                'password' => $credentials['password'],
            ]);

            if ($response->failed()) {
                $error = $response->json('message') ?? 'Login failed';
                return back()->with('error', $error);
            }

            $data = $response->json();

            // Check if user is admin
            if ($data['user']['role'] !== 'admin') {
                return back()->with('error', 'Access denied. Admin account required.');
            }

            // Store token and user in session
            Session::put('api_token', $data['access_token']);
            Session::put('admin_user', $data['user']);

            return redirect('/dashboard');
        } catch (\Exception $e) {
            return back()->with('error', 'Connection error: ' . $e->getMessage());
        }
    }

    public function logout()
    {
        Session::forget('api_token');
        Session::forget('admin_user');
        
        return redirect('/login')->with('success', 'Logged out successfully');
    }
}
