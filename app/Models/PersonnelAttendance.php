<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PersonnelAttendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'date',
        'check_in_time',
        'check_out_time',
        'status',
        'latitude',
        'longitude',
        'description'
    ];

    protected $casts = [
        'date' => 'date',

    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
