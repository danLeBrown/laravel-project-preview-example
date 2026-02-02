<?php

use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

Route::get('/health', function () {
    $checks = [];

    try {
        DB::connection()->getPdo();
        DB::select('SELECT 1');
        $checks['database'] = 'ok';
    } catch (\Throwable $e) {
        $checks['database'] = 'error';
    }

    try {
        Cache::store('redis')->get('health');
        $checks['redis'] = 'ok';
    } catch (\Throwable $e) {
        $checks['redis'] = 'error';
    }

    return response()->json([
        'status' => 'ok',
        'app' => config('app.name'),
        'environment' => config('app.env'),
        'checks' => $checks,
    ]);
});

Route::get('/version', function () {
    return response()->json([
        'app' => config('app.name'),
        'environment' => config('app.env'),
        'laravel_version' => app()->version(),
    ]);
});

Route::get('/users/count', function () {
    return response()->json([
        'count' => User::count(),
    ]);
});

Route::get('/users', function () {
    return response()->json([
        'users' => User::all(),
    ]);
});
