<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Option extends Model
{
    use HasFactory;

    protected $fillable = [
        'question_id', 
        'text', 
        'text_en', 
        'points', 
        'feedback', 
        'next_question_id'
    ];

    /**
     * The current scenario/question this button belongs to.
     */
    public function question()
    {
        return $this->belongsTo(Question::class, 'question_id');
    }

    /**
     * The branching path: The destination scenario this button routes the player to.
     * If this is null, it means clicking this option ends the game!
     */
    public function nextQuestion()
    {
        return $this->belongsTo(Question::class, 'next_question_id');
    }
}