<?php

use App\Services\OllamaHistoryService;
use App\Services\OllamaService;

beforeEach(function () {
    $this->historyService = new OllamaHistoryService();
});

it('uses consistent design system classes on the history page', function () {
    $model = new \App\Models\OllamaHistory();
    $mockHistory = collect([$model::factory()->create()]);
    $this->mock(OllamaHistoryService::class, function ($mock) use($mockHistory) {
        $mock->shouldReceive('getHistory')
            ->once()
            ->andReturn($mockHistory);;

    });

    $component = Livewire::test(\App\Livewire\OllamaHistory::class);
    $component->assertSee('result-card')
        ->assertSee('result-card__header')
        ->assertSee('result-card__content')
        ->assertSee('results__grid')
        ->assertSee($mockHistory->first()->prompt)
        ->assertSee($mockHistory->first()->notes);

});

it('uses consistent design system classes for the results content', function () {
    $model = new \App\Models\OllamaHistory();
    $mockHistory = collect([$model::factory()->create()]);
    $this->mock(OllamaHistoryService::class, function ($mock) use($mockHistory) {
        $mock->shouldReceive('getHistory')
            ->once()
            ->andReturn($mockHistory);;

    });

    $component = Livewire::test(\App\Livewire\OllamaHistory::class);
    $component->assertSee('history-card')
        ->assertSee('history-card__models')
        ->assertSee('history-card__tags')
        ->assertSee('history-card__notes')
        ->assertSee('history-card__results')
        ->assertSee('model-badge')
        ->assertSee('tag-badge');


});

it('has input fields following the design system styling ', function () {
    $this->mock(OllamaService::class, function ($mock) {
        $mock->shouldReceive('listModels')
            ->once()
            ->andReturn(['name' => 'test-model', 'size' => '2.0 GB']);
    });
    $component = Livewire::test(\App\Livewire\OllamaComparison::class);

    $component->assertSee('form-group')
        ->assertSee('form-label')
        ->assertSeeHtml('class="form__input')
        ->assertSeeHtml('class="form__textarea');

});

it('display markdown consistently between live and history views', function () {
    $this->historyService->saveComparison([
        'prompt' => 'Test prompt',
        'models' => ['test-model'],
        'results' => [
            [
                'model' => 'test-model',
                'response' => "Line 1\n\nLine 2 with **bold** text\n\n```\ncode block\n```",
                'total_duration' => 1.5,
                'response_tokens' => 50
            ]
        ],
        'tags' => [],
        'notes' => '',
    ]);

    $historyComponent = Livewire::test(\App\Livewire\OllamaHistory::class);

    $historyComponent->assertSee("<strong>bold</strong>",false)
        ->assertSee("<pre>",false)
        ->assertSee('<p>', false)
        ->assertDontSee('**bold**');


});
