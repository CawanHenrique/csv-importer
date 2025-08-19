<?php

use App\Http\Controllers\ContactImportController;
use App\Http\Controllers\DashboardController;
use ContactImportController as GlobalContactImportController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function(){
    Route::get('/contacts', [ContactImportController::class, 'index'])->name('contacts.index');
    Route::post('/contacts/import', [ContactImportController::class, 'store'])->name('contacts.import');
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
