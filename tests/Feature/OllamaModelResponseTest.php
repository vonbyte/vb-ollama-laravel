<?php

use App\Livewire\OllamaComparison;
use App\Livewire\OllamaModelResponse;
use App\Services\OllamaService;

it('displays the model name and size', function () {

    Livewire::test(OllamaModelResponse::class, [
        'model' => 'llama3.2:3b',
        'size' => '2.0 GB',
        'response' => 'Test Response',
        'duration' => 1.75,
    ])
        ->assertSee('llama3.2:3b')
        ->assertSee('2.0 GB');

});

it('displays the response time correctly', function () {
    $component = Livewire::test(OllamaModelResponse::class, [
        'model' => 'llama3.2:3b',
        'size' => '2.0 GB',
        'response' => 'Test Response',
        'duration' => 1.75,
    ]);
    $component->assertSee('1.75s');
});

it('applies fast time class for quick responses', function () {
    $component = Livewire::test(OllamaModelResponse::class, [
        'model' => 'llama3.2:3b',
        'response' => 'Fast Response',
        'duration' => 1.5,
    ]);
    $component->assertSee('result-card__time--fast');
});

it('applies medium time class for average responses', function () {
    $component = Livewire::test(OllamaModelResponse::class, [
        'model' => 'llama3.2:3b',
        'response' => 'Average Response',
        'duration' => 3.5,
    ]);
    $component->assertSee('result-card__time--medium');
});

it('applies slow time class for slow responses', function () {
    $component = Livewire::test(OllamaModelResponse::class, [
        'model' => 'llama3.2:3b',
        'response' => 'Slow Response',
        'duration' => 5.5,
    ]);
    $component->assertSee('result-card__time--slow');
});

it('formats markdown content correctly', function () {
    $markdownText = "# Heading\n\n**Bold text**\n\n```php\necho 'code';\n```";

    $component = Livewire::test(OllamaModelResponse::class, [
        'model' => 'model1',
        'response' => $markdownText,
        'duration' => 1.5
    ]);

    $component->assertSee('<h1>Heading</h1>', false)
        ->assertSee('<strong>Bold text</strong>', false)
        ->assertSee('<code class="language-php">echo \'code\';', false);
});

it('displays token count if available', function () {
    $component = Livewire::test(OllamaModelResponse::class, [
        'model' => 'model1',
        'response' => 'Test Response',
        'duration' => 1.5,
        'tokenCount' => 42
    ]);

    $component->assertSee('42 tokens');
});
