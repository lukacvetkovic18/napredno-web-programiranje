<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentTaskApplication extends Model
{
    protected $fillable = [
        'student_id',
        'task_id',
        'priority',
    ];

    // Define relationships
    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function task()
    {
        return $this->belongsTo(Task::class, 'task_id');
    }
}
