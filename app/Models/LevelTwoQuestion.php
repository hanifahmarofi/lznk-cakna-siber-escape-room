<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LevelTwoQuestion extends Model
{
    use HasFactory;

    protected $fillable = [
        'category',
        'question',
        'option_1',
        'option_2',
        'option_3',
        'option_4',
        'correct_answer',
    ];
}