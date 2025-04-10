<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json(['Laravel' => app()->version()]);
});

// Opsi tambahan kalau kamu nanti pakai Laravel Breeze atau Fortify
// require __DIR__.'/auth.php';
