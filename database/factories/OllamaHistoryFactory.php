<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\OllamaHistory>
 */
class OllamaHistoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
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
    }
}
