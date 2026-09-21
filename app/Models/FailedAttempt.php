<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FailedAttempt extends Model
{
    protected $fillable = ['user_id', 'room_number'];

    // Link it back to the user who failed
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
