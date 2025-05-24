<?php

use Illuminate\Support\Facades\Route;

Route::get('/ollama', [\App\Http\Controllers\OllamaComparisonController::class, 'compare'])
    ->name('ollama.compare');

Route::get('/test', \App\Http\Controllers\OllamaTestController::class);
Route::get('/test-service', function (App\Services\OllamaService $service) {
    try {
        $models = $service->listModels();
        return response()->json(['success' => true, 'models' => $models]);
    } catch (\Exception $e) {
        return response()->json(['success' => false, 'error' => $e->getMessage()]);
    }
});
