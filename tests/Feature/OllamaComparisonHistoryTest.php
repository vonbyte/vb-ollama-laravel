<?php

use App\Livewire\OllamaComparison;
use App\Models\OllamaHistory;
use App\Services\OllamaHistoryService;
use App\Services\OllamaService;
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
    expect($result->results)->toHaveCount(2);

});

it('saves one comparison entry with multiple model results', function () {
    $mockResult = [
        'success' => true,
        'results' => [
            [
                'model' => 'llama3.2:3b',
                'response' => 'Test response',
                'total_duration' => 1.5,
                'response_tokens' => 42
            ],
            [
                'model' => 'gemma2:2b',
                'response' => 'Test response 2',
                'total_duration' => 1.7,
                'response_tokens' => 42
            ]
        ],
        'error' => null
    ];
    $this->mock(OllamaService::class, function ($mock) use ($mockResult) {
        $mock->shouldReceive('listModels')
            ->once()
            ->andReturn([]);
        $mock->shouldReceive('processPrompt')
            ->once()
            ->with("Test prompt", ['llama3.2:3b', 'gemma2:2b'])
            ->andReturn($mockResult);
    });


    expect(OllamaHistory::count())->toBe(0);

    Livewire::test(OllamaComparison::class)
        ->set('prompt', 'Test prompt')
        ->set('selectedModels', ['llama3.2:3b', 'gemma2:2b'])
        ->call('compare');

    expect(OllamaHistory::count())->toBe(1);

    $comparison = OllamaHistory::first();
    expect($comparison->results)->toHaveCount(2);
    expect($comparison->results[0]['model'])->toBe('llama3.2:3b');
    expect($comparison->results[1]['model'])->toBe('gemma2:2b');

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

    $component = Livewire::test(OllamaComparison::class)
        ->set('prompt', 'Test prompt')
        ->set('selectedModels', ['gemma2:2b'])
        ->call('compare');


    $this->assertDatabaseHas('ollama_histories', ['prompt' => "Test prompt"]);

});

it('can add tags to a comparison', function () {
    $comparisonData = [
        'prompt' => "Test prompt",
        'models' => ['gemma2:2b'],
        'results' => [
            [
                'model' => 'gemma2:2b',
                'response' => 'Test response 2',
                'total_duration' => 1.7,
                'response_tokens' => 42
            ]
        ],
        'tags' => ['coding', 'python', 'beginner']
    ];

    $result = $this->historyService->saveComparison($comparisonData);
    expect($result->tags)->toBe($comparisonData['tags']);
    $this->assertDatabaseHas('ollama_histories', ['tags' => json_encode(['coding', 'python', 'beginner'])]);

    expect(true)->toBeTrue();
});

it('can add notes to a comparison', function () {
    $comparisonData = [
        'prompt' => "Test prompt",
        'models' => ['gemma2:2b'],
        'results' => [
            [
                'model' => 'gemma2:2b',
                'response' => 'Test response 2',
                'total_duration' => 1.7,
                'response_tokens' => 42
            ]
        ],
        'tags' => ['coding', 'python', 'beginner'],
        'notes' => 'Test notes with some context, just to berecognized',
    ];

    $result = $this->historyService->saveComparison($comparisonData);
    expect($result->notes)->toBe($comparisonData['notes']);
    $this->assertDatabaseHas('ollama_histories', ['notes' => $comparisonData['notes']]);
});

it('displays tags and notes in history view', function () {
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
            'tags' => ['coding', 'python', 'beginner'],
            'notes' => 'Test notes with some context, just to berecognized',
        ];

        $this->historyService->saveComparison($mockHistory);

        $component = Livewire::test(\App\Livewire\OllamaHistory::class);

        $component->assertSee($mockHistory['tags'])
            ->assertSee($mockHistory['notes']);

});

it('can access the history page via route', function () {
    $response = $this->get('/ollama/history');
    $response->assertStatus(200)
        ->assertSeeLivewire(
            \App\Livewire\OllamaHistory::class
        );
});
