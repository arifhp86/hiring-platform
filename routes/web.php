<?php

use App\Http\Controllers\CandidateController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/



Route::get('candidates', [CandidateController::class, 'index'])->name('candidates.index');
Route::post('candidates/{id}/contact', [CandidateController::class, 'contact'])->name('candidates.contact');
Route::post('candidates/{id}/hire', [CandidateController::class, 'hire'])->name('candidates.hire');

Route::get('{any}', HomeController::class)->where('any','.*');