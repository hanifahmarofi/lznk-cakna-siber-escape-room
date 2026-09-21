<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GameProgress extends Model
{
    use HasFactory;

    protected $table = 'game_progresses';

    protected $fillable = [
        'user_id',
        'total_score',
        'level_1_completed', 'level_1_score', 'level_1_correct', 'level_1_incorrect',
        'level_2_completed', 'level_2_score', 'level_2_correct', 'level_2_incorrect',
        'level_3_completed', 'level_3_score', 'level_3_correct', 'level_3_incorrect',
        'level_4_completed', 'level_4_score', 'level_4_correct', 'level_4_incorrect',
        'level_5_completed', 'level_5_score', 'level_5_correct', 'level_5_incorrect',
        'level_6_completed', 'level_6_score', 'level_6_correct', 'level_6_incorrect',
        'completed_minigames',
        'level_1_wrong_answers',
        'level_2_wrong_answers',
        'level_3_wrong_answers',
        'level_4_wrong_answers',
        'level_5_wrong_answers',
        'level_6_wrong_answers',
        'custom_room_scores', // 🔥 NEW DYNAMIC COLUMN
        'custom_room_history',
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function hasPassedCustomRoom($roomId, $passingMark)
    {
        // Decode scores safely
        $scores = is_array($this->custom_room_scores) 
            ? $this->custom_room_scores 
            : json_decode($this->custom_room_scores, true) ?? [];

        // Check if the user has a score for this room AND if it meets the dynamic passing mark
        return isset($scores[$roomId]) && $scores[$roomId] >= $passingMark;
    }

    protected $casts = [
        'completed_minigames' => 'array',
        'level_1_wrong_answers' => 'array',
        'level_2_wrong_answers' => 'array',
        'level_3_wrong_answers' => 'array',
        'level_4_wrong_answers' => 'array',
        'level_5_wrong_answers' => 'array',
        'level_6_wrong_answers' => 'array',
        'custom_room_scores' => 'array', // 🔥 CAST AS ARRAY
        'custom_room_history' => 'array',
    ];
}