<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [
        'naziv_rada',
        'naziv_rada_engleski',
        'zadatak_rada',
        'tip_studija',
        'user_id',
        'status',
        'accepted_student_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function acceptedStudent()
    {
        return $this->belongsTo(User::class, 'accepted_student_id');
    }

}
