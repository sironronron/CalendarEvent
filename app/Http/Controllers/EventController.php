<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventDay;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        $date = Carbon::now();

        $default_start_date = Carbon::now()->firstOfMonth();
        $default_end_date = Carbon::now()->endOfMonth();
        $default_date_range = CarbonPeriod::create($default_start_date, $default_end_date);

        $date_with_events_range = [];

        foreach ($default_date_range->toArray() as $range) {
            $date_range = [];

            $date_range['date'] = Carbon::parse($range)->format('d D');

            $event = Event::where('user_id', $user->id)
                ->select('id', 'name', 'start_date', 'end_date', 'created_at')
                ->with(['event_days' => function ($query) {
                    $query->select('event_id', 'day');
                }])
                ->first();

            if ($event != null && $event->end_date >= Carbon::parse($range)->format('Y-m-d'))
                foreach ($event->event_days as $day) {
                    if ($day->day === Carbon::parse($range)->format('w')) {
                        $date_range['event'] = $event->name;
                    }
                }

            array_push($date_with_events_range, $date_range);
        }

        return Inertia::render('Dashboard', [
            'current_date' => fn () => $date->format('M Y'),
            'date_with_events_range' => fn () => $date_with_events_range
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'event_name' => 'required|max:191|string',
            'start_date' => 'required|string',
            'end_date' => 'required|string',
            'days' => 'required'
        ]);

        try {
            $user = Auth::user();

            // Check if there's an existing event
            $event_exists = Event::where('user_id', $user->id)->first();

            if ($event_exists != null) {
                $event_exists->delete();
            }

            $event = Event::create([
                'user_id' => $user->id,
                'name' => $request->event_name,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
            ]);

            foreach ($request->days as $day) {
                EventDay::create([
                    'event_id' => $event->id,
                    'day' => $day
                ]);
            }

            $request->session()->flash('flash.banner', 'Successfully added new event!');
            $request->session()->flash('flash.bannerStyle', 'success');

            return redirect()->back();
        } catch (\Exception $e) {
            if (getenv('APP_DEBUG') == true) {
                dd($e);
            }

            Log::error($e);

            $request->session()->flash('flash.banner', 'Something went wrong');
            $request->session()->flash('flash.bannerStyle', 'danger');

            return redirect()->back();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function show(Event $event)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function edit(Event $event)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Event $event)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function destroy(Event $event)
    {
        //
    }
}
