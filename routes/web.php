<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ComponentController;
use App\Http\Controllers\CompetencyController;
use App\Http\Controllers\FormController;
use App\Http\Controllers\FilledFormController;
use App\Http\Controllers\GradeLevelController;

Route::get('/', function () {
    return view('/auth/login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

//Route::get('/forms/show-test', function () {
//    $filledForm = (object) [
//        'student_name' => 'Chrystella Agyemang',
//        'subject' => 'Web Development',
//        'filledComponents' => [
//            (object) [
//                'component_name' => 'Samenwerken',
//                'score' => 5,
//                'comment' => 'Toont veel initiatief en goede samenwerking'
//            ],
//            (object) [
//                'component_name' => 'Communicatie',
//                'score' => 3,
//                'comment' => 'Soms onduidelijk in uitleg'
//            ],
//            (object) [
//                'component_name' => 'Reflectie',
//                'score' => 0,
//                'comment' => null
//            ],
//        ],
//    ];
//
//    return view('forms.show', compact('filledForm'));
//});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// CRUD routes voor onze controllers
Route::resource('competencies', CompetencyController::class);
Route::resource('components', ComponentController::class);
Route::resource('forms', FormController::class);
Route::resource('filledforms', FilledFormController::class);
Route::resource('gradelevels', GradeLevelController::class);


require __DIR__.'/auth.php';
