<?php

use App\Services\OllamaService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Config;

it('formats models correctly when listing', function () {
    Http::fake([
        config('ollama-laravel.url') . "/api/tags" => Http::response([
            'models' => [
                ['name' => 'llama3.2:3b', 'size' => 2000000000],
                ['name' => 'smollm2:135m', 'size' => 1100000000]
            ]
        ]),
    ]);

    $service = new OllamaService();
    $result = $service->listModels();
    expect($result)->toHaveCount(2);
    expect($result[0])->toHaveKeys(['name', 'size']);
    expect($result[0]['name'])->toBe('llama3.2:3b');
    expect($result[0]['size'])->toBe('2.0 GB');
});

it('processes prompts with a single model', function () {
    Http::fake([
        config('ollama-laravel.url'). "/api/generate" => Http::response([
            'response' => 'Test response',
            'total_duration' => 1500000000
        ], 200),
    ]);

    $service = new OllamaService();
    $result = $service->processPrompt('Test prompt', ['llama3.2:3b']);

    expect($result['success'])->toBeTrue();
    expect($result['results'])->toHaveCount(1);
    expect($result['results'][0]['response'])->toBe('Test response');
    expect($result['results'][0]['total_duration'])->toBeFloat();
    expect($result['results'][0]['model'])->toBe('llama3.2:3b');
});

it('handles API errors gracefully', function () {
    Http::fake([
        config('ollama-laravel.url'). "/api/generate" => Http::response([
            'error' => 'Model not found'
        ], 404)
    ]);
    $service = new OllamaService();
    $result = $service->processPrompt('Test prompt', ['non-existent-model']);

    expect($result['success'])->toBeFalse();
    expect($result['error'])->toBe('Model not found');

});
