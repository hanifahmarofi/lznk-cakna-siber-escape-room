<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MainframeSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'total_minutes',
        'seconds_per_question',
        'streak_threshold_seconds',
        'streak_bonus_percent',
        'max_mistakes',
    ];
}