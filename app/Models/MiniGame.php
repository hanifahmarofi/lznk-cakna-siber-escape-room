<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MiniGame extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'game_type',
        'base_score',
        'instruction',
        'game_data',
        'is_active',
        'sort_order',
    ];

    // Tell Laravel to automatically cast the game_data column to an array
    protected $casts = [
        'game_data' => 'array',
        'is_active' => 'boolean',
    ];
}