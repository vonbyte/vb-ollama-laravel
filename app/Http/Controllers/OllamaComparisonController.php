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

    public function process(Request $request)
    {
        $validatedData = $request->validate([
            'prompt' => 'required',
            'models' => 'required|array|max:4',
        ]);

        $result = $this->ollamaService->processPrompt($validatedData['prompt'], $validatedData['models']);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json($result);
        }

        return redirect()->back()
            ->with('success', 'Prompt processed successfully.')
            ->with('results', $result);
    }

}
