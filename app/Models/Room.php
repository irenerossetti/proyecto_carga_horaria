<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    protected $table = 'public.rooms';
    protected $fillable = ['name', 'capacity', 'type'];
    public $timestamps = false;

    /**
     * Relación uno a muchos con Schedule (un aula puede tener muchos horarios)
     */
    public function schedules()
    {
        return $this->hasMany(Schedule::class, 'room_id');
    }
}