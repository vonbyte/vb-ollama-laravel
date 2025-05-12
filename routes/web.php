<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/ollama', \App\Http\Controllers\OllamaTestController::class);
