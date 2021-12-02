<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventDay extends Model
{
    use HasFactory;

    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'event_id', 'day'
    ];

    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id');
    }
}
