<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClassCancellation extends Model
{
    protected $table = 'public.class_cancellations';
    // 'cancelled_at' debe ser manejado por $timestamps si existe
    public $timestamps = true; 
    const CREATED_AT = 'cancelled_at'; // Asumiendo que 'cancelled_at' es tu 'created_at'
    const UPDATED_AT = null; // Asumiendo que no hay 'updated_at'

    protected $fillable = [
        'schedule_id',
        'cancellation_type', // 'cancelled' o 'virtual'
        'reason',
        'cancelled_at'
    ];

    protected $casts = [
        'cancelled_at' => 'datetime',
    ];

    /**
     * Relación inversa con Schedule (una cancelación pertenece a un horario)
     */
    public function schedule()
    {
        return $this->belongsTo(Schedule::class, 'schedule_id');
    }
}