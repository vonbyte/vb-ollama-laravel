<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/ollama', \App\Http\Controllers\OllamaTestController::class);
Route::post('/ollama/process', \App\Http\Controllers\OllamaTestController::class);
