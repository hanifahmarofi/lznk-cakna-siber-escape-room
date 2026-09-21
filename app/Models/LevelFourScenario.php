<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LevelFourScenario extends Model
{
    use HasFactory;

    protected $fillable = [
        'url',
        'explanation',
        'title',
        'clues',
        'image_path',
        'has_ssl',
        'is_phishing',
    ];
}