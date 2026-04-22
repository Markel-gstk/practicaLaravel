<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\IdeaController;

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
});

Route::get('/idea', [IdeaController::class, 'index'])->name('idea.index');
Route::get('/idea/crear', [IdeaController::class, 'create'])->name('idea.create');
Route::post('/idea/crear', [IdeaController::class, 'store'])->name('idea.store');
Route::get('/idea/editar/{idea}', [IdeaController::class, 'edit'])->name('idea.edit');
Route::put('/idea/actualizar/{idea}', [IdeaController::class, 'update'])->name('idea.update');
Route::get('/idea/{idea}', [IdeaController::class, 'show'])->name('idea.show');
Route::delete('/idea/{idea}', [IdeaController::class, 'delete'])->name('idea.delete');


require __DIR__.'/auth.php';
