<?php

use App\Http\Controllers\InviteController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->view('qr-only-404', [], 404);
});

Route::middleware('token')->group(function () {
    Route::get('/invite', [InviteController::class, 'show'])->name('invite.show');
    Route::post('/invite/submit', [InviteController::class, 'submit'])->name('invite.submit');
    Route::get('/invite/success/{submission}', [InviteController::class, 'success'])->name('invite.success');
});
