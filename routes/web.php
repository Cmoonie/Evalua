<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FormController;
use App\Http\Controllers\FilledFormController;

Route::get('/', function () {
    return view('/auth/login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // CRUD routes voor onze controllers
    Route::resource('forms', FormController::class);
    Route::resource('filled_forms', FilledFormController::class);
    Route::get('/gradelist', [FilledFormController::class, 'gradeList'])
        ->name('filled_forms.gradelist');
    Route::get('filled_forms/create/{form}', [FilledFormController::class, 'create'])
        ->name('filled_forms.create');
});

require __DIR__.'/auth.php';
