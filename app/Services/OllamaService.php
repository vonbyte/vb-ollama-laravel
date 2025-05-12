<?php


namespace App\Services;


use Carbon\Carbon;
use Illuminate\Support\Facades\Http;

class OllamaService
{

    private string $baseUrl;

    public function __construct()
    {
        $this->baseUrl = config('ollama-laravel.url');
    }

    public function listModels()
    {
        try {
            $response = Http::get($this->baseUrl . '/api/tags');
            if ($response->successful()) {
                $models = $response->json('models', []);
                return array_map(function ($model) {
                    $size = $model['size'];
                    $formattedSize = $size > 1000000000
                        ? number_format($model['size'] / 1000000000, 1,) . ' GB'
                        : round($model['size'] / 1000000, 0) . ' MB';

                    $modified = $model['modified_at'] ?? null;
                    $formattedModified = $modified
                        ? Carbon::parse($modified)->diffForHumans()
                        : "unknown";

                    return [
                        'name' => $model['name'],
                        'size' => $formattedSize,
                        'modified' => $formattedModified,
                    ];
                }, $models);
            }
            return [];
        } catch (\Exception $e) {
            return [];
        }
    }
}
