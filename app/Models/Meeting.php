<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Meeting extends Model
{
    use HasFactory;

    protected $fillable = [
        'organizer',
        'title',
        'date',
        'start_time',
        'end_time',
        'venue',
        'activity_type',
        'qr_code_string',
        'status',
    ];

    // Hubungan: Meeting milik seorang Organizer (User)
    public function organizer()
    {
        return $this->belongsTo(User::class, 'organizer_id');
    }

    // Hubungan: Meeting ada ramai Jemputan (Invitations)
    public function invitations()
    {
        return $this->hasMany(Invitation::class);
    }

    // Hubungan: Meeting ada ramai Peserta Hadir (Attendances) 
    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }
}
