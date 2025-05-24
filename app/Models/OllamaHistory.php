<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OllamaHistory extends Model
{
    /** @use HasFactory<\Database\Factories\OllamaHistoryFactory> */
    use HasFactory;

    protected $fillable = ['prompt', 'results', 'models','tags'];

    protected $casts = [
        'results' => 'array',
        'models' => 'array',
        'tags' => 'array',
    ];
}
