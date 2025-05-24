<?php

namespace App\Livewire;

use App\Services\OllamaHistoryService;
use App\Services\OllamaService;
use Illuminate\Support\Collection;
use Livewire\Attributes\Validate;
use Livewire\Component;

class OllamaHistory extends Component
{
    public ?Collection $history = null;
    public ?string $error = null;


    public function mount(OllamaHistoryService $ollamaHistoryService)
    {
        try {
            $this->history = $ollamaHistoryService->getHistory();
        } catch (\Exception $e) {
            $this->error = $e->getMessage();
            $this->history = null;
        }

    }



    public function render()
    {
        return view('livewire.ollama-history');
    }


}
