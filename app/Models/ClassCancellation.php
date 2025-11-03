<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClassCancellation extends Model
{
    protected $table = 'class_cancellations';

    protected $fillable = ['schedule_id','teacher_id','mode','reason','canceled_by'];

    public function schedule()
    {
        return $this->belongsTo(Schedule::class, 'schedule_id');
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'teacher_id');
    }

    public function canceledBy()
    {
        return $this->belongsTo(User::class, 'canceled_by');
    }
}
