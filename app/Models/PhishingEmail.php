<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PhishingEmail extends Model
{
    use HasFactory;

    protected $fillable = [
        'sender_name_or_address',
        'subject',
        'body',
        'is_phishing',
    ];
}