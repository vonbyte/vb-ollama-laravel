<?php

namespace App\Livewire;

use App\Services\OllamaService;
use Livewire\Attributes\Validate;
use Livewire\Component;

class OllamaComparison extends Component
{
    public array $models = [];
    public array $results = [];
    public bool $loading = false;
    public ?string $error = null;

    #[Validate('required|array|max:4')]
    public array $selectedModels = [];

    #[Validate('required')]
    public string $prompt = "";


    public function mount(OllamaService $ollamaService)
    {
        $this->models = $ollamaService->listModels();
    }

    public function compare(OllamaService $ollamaService)
    {
        $this->validate();

        $this->results = [];
        $this->error = null;
        $this->loading = true;

        try {
            $response = $ollamaService
                ->processPrompt($this->prompt, $this->selectedModels);
            if ($response['success']) {
                $this->results = $response['results'];
            } else {
                $this->error = $response['error'] ?? 'An error occurred while processing the prompt';
            }
        } catch (\Exception $e) {
            $this->error = $e->getMessage();
        } finally {
            $this->loading = false;
        }
    }

    public function getCharCountProperty()
    {
        return strlen($this->prompt);
    }

    public function render()
    {
        return view('livewire.ollama-comparison');
    }


}
