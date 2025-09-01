<?php

use Illuminate\Support\Facades\Route;

Route::get('/test-user-surat', function () {
    return response()->json([
        'status' => 'success',
        'message' => 'Route test working',
        'timestamp' => now(),
        'route' => '/test-user-surat'
    ]);
});

Route::get('/test-view-simple', function () {
    return view('test-simple');
});
