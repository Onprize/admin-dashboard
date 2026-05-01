<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

abstract class Controller
{
    protected function getApiHeaders()
    {
        return [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . session('api_token'),
            'ngrok-skip-browser-warning' => 'true',
        ];
    }

    protected function httpGet($url, $params = [])
    {
        return Http::withHeaders($this->getApiHeaders())->withoutVerifying()->get($url, $params);
    }

    protected function httpPost($url, $data = [])
    {
        return Http::withHeaders($this->getApiHeaders())->withoutVerifying()->post($url, $data);
    }

    protected function httpPut($url, $data = [])
    {
        return Http::withHeaders($this->getApiHeaders())->withoutVerifying()->put($url, $data);
    }
}
