<?php

use App\Http\Controllers\Admin\BookController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\BookFileController;
use App\Http\Controllers\PageController;
use Illuminate\Support\Facades\Route;

Route::controller(PageController::class)->group(function () {
    Route::get('/', 'home')->name('home');
    Route::get('/college', 'college')->name('college');
    Route::get('/school', 'school')->name('school');
    Route::get('/maqra', 'maqra')->name('maqra');
    Route::get('/library', 'library')->name('library');
    Route::get('/path', 'path')->name('path');
    Route::get('/register', 'register')->name('register');
});

// قسم المتون العلمية is now the third section of المقرأة; keep the old URL working.
Route::redirect('/matun', '/maqra#matun', 301)->name('matun');

// Digital copy of a library book (signed Cloudflare R2 link).
Route::get('/library/books/{book}/file', BookFileController::class)->middleware('throttle:60,1')->name('library.book.file');

// Admin dashboard (AdminLTE). Sign-in, password reset and profile updates are Fortify routes under /admin.
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/', DashboardController::class)->name('dashboard');
    Route::resource('books', BookController::class)->except('show');
    Route::get('/profile', ProfileController::class)->name('profile');
});
