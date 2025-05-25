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

        return view('ollama.comparison');

    }

    public function history(Request $request)
    {
        return view('ollama.history');
    }

}
