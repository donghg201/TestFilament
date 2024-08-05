<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/test', function () {
    return view('testfilament');
});

Route::get('{employee}/invoice/generate', [App\Http\Controllers\InvoicesController::class, 'generatePdf'])
    ->name('employee.invoice.generate');
