<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Incident extends Model
{
    protected $fillable = ['teacher_id','room_id','description','status','reported_by','resolved_by','resolved_at'];
    protected $casts = ['resolved_at' => 'datetime'];
}
