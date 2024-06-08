<?php

use App\Http\Controllers\memberController;
use App\Models\member;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::post('/member/{id}', [memberController::class, 'edit'])->name('member.edit');
Route::get('/member/{id}', [memberController::class, 'edit'])->name('member.edit');

Route::get('/member', [memberController::class, 'index']);
Route::delete('/users/{id}', [memberController::class, 'destroy'])->name('member.destroy');
Route::post('/member', [memberController::class, 'store'])->name('member.store'); 
Route::post('/member/{id}', [memberController::class, 'edit'])->name('member.edit'); 

Route::get('/calculate', [memberController::class, 'calculate_mamber'])->name('calculate');
Route::post('/calculate', [memberController::class, 'calculate'])->name('calculate');
