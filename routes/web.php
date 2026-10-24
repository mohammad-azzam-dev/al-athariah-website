<?php

use App\Http\Controllers\PageController;
use Illuminate\Support\Facades\Route;

Route::controller(PageController::class)->group(function () {
    Route::get('/', 'home')->name('home');
    Route::get('/college', 'college')->name('college');
    Route::get('/school', 'school')->name('school');
    Route::get('/maqra', 'maqra')->name('maqra');
    Route::get('/matun', 'matun')->name('matun');
    Route::get('/library', 'library')->name('library');
    Route::get('/path', 'path')->name('path');
    Route::get('/register', 'register')->name('register');
});
