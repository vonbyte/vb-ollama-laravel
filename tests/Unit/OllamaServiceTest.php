<?php

use App\Services\OllamaService;

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
    simulateProcessedHttpResponse();
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
        config('ollama-laravel.url') . "/api/generate" => Http::response([
            'error' => 'Model not found'
        ], 404)
    ]);
    $service = new OllamaService();
    $result = $service->processPrompt('Test prompt', ['non-existent-model']);

    expect($result['success'])->toBeFalse();
    expect($result['error'])->toBe('No response from model');

});

/**
it('accepts prompts with the maximum allowed length of 2000 characters', function () {
    simulateProcessedHttpResponse();

    $service = new OllamaService();

    $longprompt = str_repeat('a', 2000);

    $result = $service->processPrompt($longprompt, ['llama3.2:3b']);

    expect($result['success'])->toBeTrue();
});

it('rejects prompts exceeding the maximum allowed length', function () {
    simulateProcessedHttpResponse();

    $service = new OllamaService();

    $tooLongPrompt = str_repeat('a', 2001);

    $result = $service->processPrompt($tooLongPrompt, ['llama3.2:3b']);

    expect($result['success'])->toBeFalse();
    expect($result['error'])->toBe('No response from model');
});

it('rejects prompts with no models selected', function () {
    $response = $this->post('/ollama/process', [
        'prompt' => 'Test prompt'
    ]);

    $response->assertSessionHasErrors('models');
    $response->assertSessionHasErrors(['models' => 'The model field is required.']);

});

it('rejects empty prompt submissions', function () {
    $response = $this->post('/ollama/process', [
        'prompt' => '',
        'models' => ['llama3.2:3b']
    ]);

    $response->assertSessionHasErrors('prompt');
    $response->assertSessionHasErrors(['prompt' => 'The prompt field is required.']);
});

it('rejects null prompt submissions', function () {
    $response = $this->post('/ollama/process', [
        'prompt' => null,
        'models' => ['llama3.2:3b']
    ]);

    $response->assertSessionHasErrors('prompt');
});

it('rejects whitespace-only prompt submissions', function () {
    $response = $this->post('/ollama/process', [
        'prompt' => ' ',
        'models' => ['llama3.2:3b']
    ]);

    $response->assertSessionHasErrors('prompt');
});
**/
