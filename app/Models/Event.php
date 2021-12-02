<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'name', 'start_date', 'end_date',
        'weekdays'
    ];

    public function event_days()
    {
        return $this->hasMany(EventDay::class, 'event_id');
    }
}
