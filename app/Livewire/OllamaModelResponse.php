<?php

namespace App\Livewire;

use Illuminate\Support\Str;
use Livewire\Component;

class OllamaModelResponse extends Component
{
    public string $model = "";
    public ?string $size = null;
    public ?float $duration = null;

    public string $response = "";
    public ?int $tokenCount = null;

    public function getTimeClassProperty():string
    {
        $class = "result-card__time";
        if ($this->duration < 2) {
            return $class .  ' result-card__time--fast';
        }
        if ($this->duration > 5) {
            return $class .  ' result-card__time--slow';
        }
        return $class .  ' result-card__time--medium';
    }

    public function getFormattedModelResponseProperty()
    {
        // Handle line breaks properly for markdown
        $text = preg_replace('/\n(?!\n)/', "  \n", $this->response); // Single \n becomes markdown break

        return Str::markdown($text);
    }


    public function render()
    {
        return view('livewire.ollama-model-response');
    }
}
