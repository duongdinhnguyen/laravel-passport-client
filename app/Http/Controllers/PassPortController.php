<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PassPortController extends Controller
{
    /**
     * login with SSO
     */
    public function login()
    {
        $query = http_build_query([
            'client_id' => '6',
            'redirect_uri' => 'http://127.0.0.1:8000/callback',
            'response_type' => 'code',
            'scope' => '',
        ]);
        return redirect('http://laravel-passport.test/oauth/authorize?' . $query);
    }

    /**
     * callback login with SSO
     */
    public function callbackLogin(Request $request)
    {
        $http = new Client();
        $response = $http->post('http://laravel-passport.test/oauth/token', [
            'form_params' => [
                'grant_type' => 'authorization_code',
                'client_id' => '6',
                'client_secret' => 'wSGwI8VKhUtXi5V5zncLfoRFecWqXbSUZHZxAhUG',
                'redirect_uri' => 'http://127.0.0.1:8000/callback',
                'code' => $request->code,
            ],
        ]);

        $accessToken = json_decode((string) $response->getBody(), true)['access_token'];
        $result = $http->post('http://laravel-passport.test/api/details', [
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'Authorization' => "Bearer $accessToken",
            ]
        ]);
        return (string) $result->getBody();
    }
}
