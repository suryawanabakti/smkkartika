<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeachingAttendance extends Model
{
    protected $fillable = [
        'teacher_id',
        'schedule_id',
        'date',
        'subject',
        'class_id',
        'check_in_time',
        'notes'
    ];

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function schedule()
    {
        return $this->belongsTo(TeacherSchedule::class, 'schedule_id');
    }

    public function classRoom()
    {
        return $this->belongsTo(ClassRoom::class, 'class_id');
    }}
