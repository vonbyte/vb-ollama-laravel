<?php

use App\Livewire\OllamaComparison;
use App\Services\OllamaService;

it('displays the model comparison form', function () {

    $this->mock(OllamaService::class, function ($mock) {
        $mock->shouldReceive('listModels')
            ->once()
            ->andReturn([]);
    });

    $response = $this->get('/ollama');
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

    Livewire::test(OllamaComparison::class)
        ->assertSee($mockModels[0]['name'])
        ->assertSee($mockModels[0]['size'])
        ->assertSee($mockModels[1]['name'])
        ->assertSee($mockModels[1]['size'])
        ->assertSee($mockModels[2]['name'])
        ->assertSee($mockModels[2]['size']);
});

it('requires a prompt', function () {
    $component = Livewire::test(OllamaComparison::class)
        ->set('prompt', '')
        ->set('selectedModels', ['model1'])
        ->call('compare');

    $component->assertHasErrors(['prompt' => 'required']);
});

it('requires at least one model to be selected', function () {

    $component = Livewire::test(OllamaComparison::class)
        ->set('prompt', 'Example prompt')
        ->set('selectedModels', [])
        ->call('compare');

    $component->assertHasErrors(['selectedModels' => 'required']);
});

it('limits model selection to maximum 4', function () {

    $component = Livewire::test(OllamaComparison::class)
        ->set('prompt', 'Example prompt')
        ->set('selectedModels', ['model1', 'model2', 'model3', 'model4', 'model5'])
        ->call('compare');

    $component->assertHasErrors(['selectedModels' => 'max']);

});

it('displays character count for the  prompt', function () {
    $testPrompt = 'This is a test prompt with exactly 42 characters.';

    $component = Livewire::test(OllamaComparison::class)
        ->set('prompt', $testPrompt);

    $component->assertSet('charCount', strlen($testPrompt));
});

it('processes a prompt with a single model', function () {
    $mockResult = [
        'success' => true,
        'results' => [
            [
                'model' => 'llama3.2:3b',
                'response' => 'Test response',
                'total_duration' => 1.5,
            ]
        ],
        'error' => null
    ];

    $this->mock(OllamaService::class, function ($mock) use ($mockResult) {
        $mock->shouldReceive('listModels')
            ->andReturn([]);

        $mock->shouldReceive('processPrompt')
            ->once()
            ->with('Test Prompt', ['llama3.2:3b'])
            ->andReturn($mockResult);
    });

    $component = Livewire::test(OllamaComparison::class)
        ->set('prompt', 'Test Prompt')
        ->set('selectedModels', ['llama3.2:3b'])
        ->call('compare');

    $component
        ->assertSet('results', $mockResult['results']);
    $component
        ->assertSee('Results')
        ->assertSee('Test response')
        ->assertSee('1.50s');

});

it('processes a prompt with multiple models', function () {
    $mockResult = [
        'success' => true,
        'results' => [
            [
                'model' => 'llama3.2:3b',
                'response' => 'Test response',
                'total_duration' => 1.5,
            ],
            [
                'model' => 'gemma2:2b',
                'response' => 'Test response 2',
                'total_duration' => 1.7,
            ]
        ],
        'error' => null
    ];

    $this->mock(OllamaService::class, function ($mock) use ($mockResult) {
        $mock->shouldReceive('listModels')
            ->andReturn([]);

        $mock->shouldReceive('processPrompt')
            ->once()
            ->with('Test Prompt', ['llama3.2:3b','gemma2:2b'])
            ->andReturn($mockResult);
    });

    $component = Livewire::test(OllamaComparison::class)
        ->set('prompt', 'Test Prompt')
        ->set('selectedModels', ['llama3.2:3b','gemma2:2b'])
        ->call('compare');

    $component
        ->assertSet('results', $mockResult['results']);
    $component
        ->assertSee('Results')
        ->assertSee('Test response')
        ->assertSee('Test response 2')
        ->assertSee('1.50s')
        ->assertSee('1.70s');

});
