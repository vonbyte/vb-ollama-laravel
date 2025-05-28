<?php

namespace App\Livewire;

use App\Services\OllamaHistoryService;
use App\Services\OllamaService;
use Illuminate\Support\Str;
use Livewire\Attributes\Validate;
use Livewire\Component;

class OllamaComparison extends Component
{
    public array $models = [];
    public array $results = [];
    public string $tags = '';
    #[Validate('max:255')]
    public ?string $notes = "";
    public bool $loading = false;
    public bool $refreshingModels = false;
    public ?string $error = null;

    #[Validate('required|array|max:4')]
    public array $selectedModels = [];

    #[Validate('required')]
    public string $prompt = "";


    public function mount(OllamaService $ollamaService)
    {
        try {
            $this->models = $ollamaService->listModels();
        } catch (\Exception $e) {
            $this->error = $e->getMessage();
            $this->models = [];
        }

    }

    public function compare(OllamaService $ollamaService, OllamaHistoryService $ollamaHistoryService)
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
               $ollamaHistoryService->saveComparison([
                   'prompt' => $this->prompt,
                   'models' => $this->selectedModels,
                   'results' => $response['results'],
                   'tags' => explode(', ',$this->tags) ?? [],
                   'notes' => $this->notes,
               ]);
            } else {
                $this->error = $response['error'] ?? 'An error occurred while processing the prompt';
            }
        } catch (\Exception $e) {
            $this->error = $e->getMessage();
        } finally {
            $this->loading = false;
        }
    }

    public function refreshModels(OllamaService $ollamaService)
    {
        $this->refreshingModels = true;
        try {
            $this->models = $ollamaService->listModels();
            if ($this->error && !$this->results) {
                $this->error = null;
            }
        } catch (\Exception $e) {
            $this->error = $e->getMessage();
            $this->models = [];
        } finally {
            $this->refreshingModels = false;
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
