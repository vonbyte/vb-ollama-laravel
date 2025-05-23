<?php


namespace App\Http\Controllers;


use App\Services\OllamaService;
use Illuminate\Http\Request;

class OllamaComparisonController
{

    public function __construct(private readonly OllamaService $ollamaService)
    {
    }

    public function compare(Request $request)
    {
        $modelData = $this->ollamaService->listModels();

        $models = $modelData ?? [];

        return view('ollama.comparison', [
            'models' => $models,
        ]);

    }

}
