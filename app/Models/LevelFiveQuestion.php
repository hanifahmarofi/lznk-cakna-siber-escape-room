<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LevelFiveQuestion extends Model
{
    use HasFactory;

    // You MUST add 'question_en' here, or Laravel will silently delete it when saving!
    protected $fillable = [
        'question',
        'question_en', 
        'is_true',
    ];
}