<?php

use App\Services\OllamaService;

it('displays the model comparison form', function () {

    $this->mock(OllamaService::class, function ($mock) {
        $mock->shouldReceive('listModels')->once()->andReturn([]);
    });

    $response = $this->get('/compare');
    $response
        ->assertStatus(200)
        ->assertSee('Enter your prompt')
        ->assertSee('Select models')
        ->assertSee('Compare');
});

it('shows all available models', function () {
    $mockModels = [
        ['name' => 'llama3.2:3b', 'size' => '2.0 GB'],
        ['name' => 'deepseek-r1:1.5b', 'size' => '1.1 GB'],
        ['name' => 'smollm2:135m', 'size' => '270 MB']
    ];

    $this->mock(OllamaService::class, function ($mock) use ($mockModels) {
        $mock->shouldReceive('listModels')->once()->andReturn($mockModels);
    });

    $response = $this->get('/compare');

    $response
        ->assertSee($mockModels[0]['name'])
        ->assertSee($mockModels[0]['size'])
        ->assertSee($mockModels[1]['name'])
        ->assertSee($mockModels[1]['size'])
        ->assertSee($mockModels[2]['name'])
        ->assertSee($mockModels[2]['size']);
});

it('requires a prompt', function () {
    $response = $this->post('/compare', [
        'models' => ['smollm2:135m']
    ]);

    $response->assertSessionHasErrors(['prompt']);
});

it('limits model selection to maximum 4', function () {
    $response = $this->post('/compare', [
        'prompt' => 'Exampleprompt',
        'models' => ['model1', 'model2', 'model3', 'model4', 'model5']
    ]);

    $response->assertSessionHasErrors(['models']);
});

it('processes form submission successfully', function () {
    $mockResult = [
        'success' => true,
        'results' => [
            [
                'model' => 'llama3.2:3b',
                'response' => 'Test response',
                'total_duration' => 1.5,
            ]
        ]
    ];

    $this->mock(OllamaService::class, function ($mock) use ($mockResult) {
        $mock->shouldReceive('processPrompt')
            ->once()
            ->with('Test prompt', ['llama3.2:3b'])
            ->andReturn($mockResult);
    });

    $response = $this->post('/process', [
        'prompt' => 'Test prompt',
        'models' => ['llama3.2:3b']
    ]);

    $response->assertStatus(200)
        ->assertJson(['success' => true])
        ->assertJsonPath(['results.0.model' => 'llama3.2:3b']);
});
