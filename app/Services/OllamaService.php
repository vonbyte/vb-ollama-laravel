<?php


namespace App\Services;


use Carbon\Carbon;
use Cloudstudio\Ollama\Facades\Ollama;
use Illuminate\Support\Facades\Log;

class OllamaService
{

    public function __construct()
    {
    }

    public function listModels()
    {
        try {
            $response = Ollama::models();
            $models = $response['models'] ?? [];
            return array_map(function ($model) {

                $modified = $model['modified_at'] ?? null;
                $formattedModified = $modified
                    ? Carbon::parse($modified)->diffForHumans()
                    : "unknown";

                return [
                    'name' => $model['name'] ?? '',
                    'size' => $this->formatSize($model['size'] ?? 0),
                    'modified' => $formattedModified,
                ];
            }, $models);
        } catch (\Exception $e) {
            return [];
        }
    }

    public function processPrompt(string $prompt, array $models, $options = [])
    {
        $results = [];
        $success = true;
        $error = null;

        foreach ($models as $model) {
            try {
                $startTime = microtime(true);

                $response = Ollama::model($model)
                    ->prompt($prompt)
                    ->options($options)
                    ->ask();

                $endTime = microtime(true);
                $duration = $endTime - $startTime;

                if (isset($response['response'])) {
                    $results[] = [
                        'model' => $model,
                        'response' => $response['response'],
                        'total_duration' => $this->extractDuration($response, $duration),
                    ];
                } else {
                    $success = false;
                    $error = 'No response from model';
                    Log::warning("Ollama model $model returned no response");
                    break;
                }
            } catch (\Exception $e) {
                $success = false;
                $error = $e->getMessage();
                Log::error("Failed to process prompt with model $model: " . $e->getMessage());
                break;
            }
        }
        return [
            'success' => $success,
            'results' => $results,
            'error' => $error
        ];
    }

    private function extractDuration(array $response, float $measuredDuration): float
    {
        return isset($response['total_duration'])
            ? $response['total_duration'] / 1_000_000_000
            : $measuredDuration;
    }

    private function formatSize($bytes)
    {
        if ($bytes >= 1_000_000_000) {
            return number_format($bytes / 1_000_000_000, 1) . ' GB';
        }

        if ($bytes >= 1_000_000) {
            return round($bytes / 1_000_000) . ' MB';
        }

        if ($bytes >= 1_000) {
            return round($bytes / 1_000) . ' KB';
        }

        return $bytes . ' B';
    }
}
