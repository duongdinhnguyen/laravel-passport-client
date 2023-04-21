<?php

use App\Http\Controllers\ProfileController;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';

Route::get('/redirect', function () {
    $query = http_build_query([
        'client_id' => '6',
        'redirect_uri' => 'http://127.0.0.1:8000/callback',
        'response_type' => 'code',
        'scope' => '',
    ]);

    return redirect('http://laravel-passport.test/oauth/authorize?' . $query);
})->name('get.token');

Route::get('/callback', function (Request $request) {
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

    return json_decode((string) $response->getBody(), true);
});
