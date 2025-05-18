<?php

use Illuminate\Support\Facades\Route;

Route::get('/ollama', [\App\Http\Controllers\OllamaComparisonController::class, 'compare'])
    ->name('ollama.compare');
Route::post('/ollama', [\App\Http\Controllers\OllamaComparisonController::class, 'process'])
    ->name('ollama.process');

Route::get('/test', \App\Http\Controllers\OllamaTestController::class);
