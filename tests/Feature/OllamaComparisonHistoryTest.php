<?php

use App\Livewire\OllamaComparison;
use App\Models\OllamaHistory;
use App\Services\OllamaHistoryService;
use Livewire\Livewire;
use Mockery\MockInterface;

beforeEach(function () {
    $this->historyService = new OllamaHistoryService();
});

it('can save a comparison to history', function () {

    $mockHistory = [
        'prompt' => "Test prompt",
        'models' => ['llama3.2:3b', 'gemma2:2b'],
        'results' => [
            [
                'model' => 'llama3.2:3b',
                'response' => 'Test response',
                'total_duration' => 1.5,
                'response_tokens' => 33
            ],
            [
                'model' => 'gemma2:2b',
                'response' => 'Test response 2',
                'total_duration' => 1.7,
                'response_tokens' => 42
            ]
        ],
    ];

    $result = $this->historyService->saveComparison($mockHistory);

    expect($result)->toBeInstanceOf(OllamaHistory::class);
    expect($result->prompt)->toBe("Test prompt");
    expect($result->results)->toBe(json_encode($mockHistory['results']));

});

it('can retrieve a comparison history', function () {
    $firstComparison = [
        'prompt' => 'First prompt',
        'models' => ['llama3.2:3b'],
        'results' => [
            [
                'model' => 'llama3.2:3b',
                'response' => 'Test response',
                'total_duration' => 1.5,
                'response_tokens' => 33
            ]
        ]
    ];

    $secondComparison = [
        'prompt' => 'Second prompt',
        'models' => ['gemma2:2b'],
        'results' => [
            [
                'model' => 'gemma2:2b',
                'response' => 'Test response 2',
                'total_duration' => 1.7,
                'response_tokens' => 42
            ]
        ],
    ];

    $this->historyService->saveComparison($firstComparison);
    $this->historyService->saveComparison($secondComparison);

    $history = $this->historyService->getHistory();

    expect($history)->toHaveCount(2);
    expect($history->pluck('prompt'))->toContain("First prompt");
    expect($history->pluck('prompt'))->toContain("Second prompt");

});

it('displays comparison history on history page', function () {
    // Arrange: Create some history first
    $mockHistory = [
        'prompt' => "Test prompt",
        'models' => ['llama3.2:3b', 'gemma2:2b'],
        'results' => [
            [
                'model' => 'llama3.2:3b',
                'response' => 'Test response',
                'total_duration' => 1.5,
                'response_tokens' => 33
            ],
            [
                'model' => 'gemma2:2b',
                'response' => 'Test response 2',
                'total_duration' => 1.7,
                'response_tokens' => 42
            ]
        ],
    ];
    $this->historyService->saveComparison($mockHistory);

    $component = Livewire::test(\App\Livewire\OllamaHistory::class);

    $component->assertSee($mockHistory['prompt'])
        ->assertSee($mockHistory['results'][0]['model'])
        ->assertSee($mockHistory['results'][0]['response'])
        ->assertSee($mockHistory['models'][0]);

});

it('automatically saves comparison to history after successful processing', function () {
    // Arrange: Mock Ollama service to return results
    $mockResult = [
        'success' => true,
        'results' => [
            [
                'model' => 'gemma2:2b',
                'response' => 'Test response 2',
                'total_duration' => 1.7,
                'response_tokens' => 42
            ]
        ],
        'error' => null,
    ];
    $this->mock(\App\Services\OllamaService::class, function (MockInterface $mock) use ($mockResult) {
        $mock->shouldReceive('listModels')->andReturn([]);
        $mock->shouldReceive('processPrompt')
            ->once()
            ->with('Test prompt', ['gemma2:2b'])
            ->andReturn($mockResult);
    });
    // Act: Run a comparison through OllamaComparison component
    $component = Livewire::test(OllamaComparison::class)
        ->set('prompt', 'Test prompt')
        ->set('selectedModels', ['gemma2:2b'])
        ->call('compare');

    // Assert: Verify it appears in history
    $this->assertDatabaseHas('ollama_histories', ['prompt' => "Test prompt"]);

});
