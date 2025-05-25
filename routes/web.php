<?php

use Illuminate\Support\Facades\Route;

Route::get('/ollama', [\App\Http\Controllers\OllamaComparisonController::class, 'compare'])
    ->name('ollama.compare');
Route::get('/ollama/history', ['App\Http\Controllers\OllamaComparisonController', 'history'])
    ->name('ollama.history');

Route::get('/test', \App\Http\Controllers\OllamaTestController::class);
