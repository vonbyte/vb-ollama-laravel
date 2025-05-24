<?php


namespace App\Services;


use App\Models\OllamaHistory;

class OllamaHistoryService
{

    public function __construct()
    {
    }

    public function saveComparison(array $comparisonData)
    {
        return OllamaHistory::create([
            'prompt' => $comparisonData['prompt'],
            'results' => $comparisonData['results'],
            'models' => $comparisonData['models'],
        ]);

    }

    public function getHistory()
    {
        return OllamaHistory::all();
    }
}
