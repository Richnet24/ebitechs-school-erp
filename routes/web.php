<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'auth'])->group(function () {
    Route::get('/admin/cash-report/export', \App\Http\Controllers\CashReportExportController::class)
        ->name('admin.cash-report.export');
});

Route::get('/', function () {
    return view('home');
});
