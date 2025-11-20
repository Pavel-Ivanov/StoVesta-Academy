<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DocumentController;
use Illuminate\Support\Facades\Route;

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

    // Documents user guide (printable -> Save as PDF)
    Route::get('/documents/guide', function () { return view('documents.guide'); })->name('documents.guide');
    // Admin documents guide (printable -> Save as PDF)
    Route::get('/sadmin/documents/guide', function () { return view('documents.admin_guide'); })->name('documents.admin.guide');

    // Document viewer (multi-page)
    Route::get('/documents/{document}/view', [DocumentController::class, 'view'])->name('documents.view');
    // Print route for documents
    Route::get('/documents/{document}/print', [DocumentController::class, 'print'])->name('documents.print');
    // Download all pages as ZIP
    Route::get('/documents/{document}/download-all', [DocumentController::class, 'downloadAll'])->name('documents.downloadAll');
});

require __DIR__.'/auth.php';
