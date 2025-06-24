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
