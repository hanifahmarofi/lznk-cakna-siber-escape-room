<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 
        'title_en', 
        'description', 
        'pass_mark', 
        'description_en', 
        'video_url', 
        'video_path', 
        'video_description', 
        'video_duration', 
        'scenario', 
        'is_active'
    ];

    /**
     * A Room contains many scenarios/questions (The Nodes)
     */
    public function questions()
    {
        return $this->hasMany(Question::class);
    }
}