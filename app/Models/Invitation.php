<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invitation extends Model
{
    use HasFactory;

    protected $fillable = [
        'meeting_id',
        'user_id',
        'guest_name',
        'guest_email',
        'status',
    ];

    
    // 1. Satu Jemputan milik satu Meeting
    public function meeting()
    {
        return $this->belongsTo(Meeting::class);
    }

    // 2. Satu Jemputan milik satu User (Staf)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}