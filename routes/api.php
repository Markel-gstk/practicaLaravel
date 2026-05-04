<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ApiController;

Route::prefix('ideas')->group(function () {
    Route::get('/', [ApiController::class,'getIdea'])->name('listarIdeas');
    route::get('/{id}', [ApiController::class,'showIdea'])->name('visualizarIdea');
    Route::post('/', [ApiController::class,'storeIdea'])->name('guardarIdea');
    Route::put('/{id}', [ApiController::class,'updateIdea'])->name('editarIdea');
    Route::delete('/{id}', [ApiController::class,'destroyIdea'])->name('borrarIdea');
});

Route::prefix('usuarios')->group(function () {
    Route::get('/', [ApiController::class,'getUser'])->name('listarUsuarios');
    Route::get('/{id}', [ApiController::class,'showUser'])->name('visualizarUsuario');
    Route::post('/', [ApiController::class,'storeUser'])->name('guardarUsuario');
    Route::put('/{id}', [ApiController::class, 'updateUser'])->name('editarUsuario');
    Route::delete('/{id}', [ApiController::class, 'destroyUser'])-> name('borrarUsuario');
});