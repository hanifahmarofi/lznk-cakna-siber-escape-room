<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserScore extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 
        'room_id', 
        'score', 
        'history'
    ];

    /**
     * Automatically cast the JSON history column to a PHP Array
     */
    protected $casts = [
        'history' => 'array',
    ];

    /**
     * Relationship: This score belongs to a User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relationship: This score belongs to a Room (Module)
     */
    public function room()
    {
        return $this->belongsTo(Room::class);
    }
}