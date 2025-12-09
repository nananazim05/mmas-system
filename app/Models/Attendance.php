<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    // KITA TAMBAH INI: Senarai kolom yang dibenarkan
    protected $fillable = [
        'meeting_id',
        'user_id',
        'guest_email',
        'scanned_at',
        'status',
    ];

    // Hubungan (Optional, tapi bagus ada)
    public function meeting()
    {
        return $this->belongsTo(Meeting::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}