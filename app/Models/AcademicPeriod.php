<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AcademicPeriod extends Model
{
    protected $table = 'academic_periods';

    protected $fillable = [
        'name',
        'status',
        'start_date',
        'end_date',
    ];

    // status values: draft, active, closed
}
