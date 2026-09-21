<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $fillable = [
        'room_id', 
        'text', 
        'text_en', 
        'level', 
        'video_url', 
        'video_path', 
        'video_description', 
        'video_duration'
    ];

    /**
     * The Room this scenario belongs to.
     */
    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    /**
     * The Choices (Options) available on this specific screen.
     */
    public function options()
    {
        return $this->hasMany(Option::class);
    }
}