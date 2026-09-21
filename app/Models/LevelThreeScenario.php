<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LevelThreeScenario extends Model
{
    use HasFactory;

    protected $fillable = [
        'platform',
        'sender_name',
        'message',
        'explanation',
        'is_threat',
    ];
}