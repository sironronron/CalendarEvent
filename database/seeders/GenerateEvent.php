<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\EventDay;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class GenerateEvent extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $event = Event::create([
            'user_id' => 1,
            'name' => "Test Event",
            'start_date' => Carbon::now()->firstOfMonth()->format('Y-m-d'),
            'end_date' => Carbon::now()->endOfMonth()->format('Y-m-d')
        ]);

        EventDay::create([
            'event_id' => $event->id,
            'day' => 'monday'
        ]);

        EventDay::create([
            'event_id' => $event->id,
            'day' => 'tuesday'
        ]);

        EventDay::create([
            'event_id' => $event->id,
            'day' => 'wednesday'
        ]);
    }
}
