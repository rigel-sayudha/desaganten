<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Admin\WilayahController;

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
    return view('home');
});

// Untuk halaman statistik/wilayah, ambil data dari database
Route::get('/statistik/wilayah', function () {
    $wilayah = \App\Models\Wilayah::all();
    return view('statistik.wilayah', compact('wilayah'));
});


// Admin login form
Route::get('/admin/login', function () {
    return view('auth.login');
})->name('login')->middleware('guest');

// Admin login POST
Route::post('/admin/login', function (Request $request) {
    $credentials = $request->only('email', 'password');
    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();
        return redirect()->intended('/admin/dashboard');
    }
    return back()->with('error', 'Email atau password salah.');
})->name('admin.login')->middleware('guest');

// Admin logout
Route::post('/admin/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/admin/login');
})->name('admin.logout')->middleware('auth');

Route::middleware('auth')->prefix('admin')->group(function () {
    Route::resource('wilayah', WilayahController::class, [
        'as' => 'admin'
    ])->except(['show']);
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    });
});
