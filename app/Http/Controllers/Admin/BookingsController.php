<?php

namespace App\Http\Controllers\Admin;

use App\Event;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEventRequest;
use App\Room;
use App\Services\EventService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class BookingsController extends Controller
{
    public function searchRoom(Request $request)
    {
        $rooms = null;
        if($request->filled(['start_time', 'end_time', 'capacity'])) {
            $times = [
                Carbon::parse($request->input('start_time')),
                Carbon::parse($request->input('end_time')),
            ];

            $rooms = Room::where('capacity', '>=', $request->input('capacity'))
                ->whereDoesntHave('events', function ($query) use ($times) {
                    $query->whereBetween('start_time', $times)
                        ->orWhereBetween('end_time', $times)
                        ->orWhere(function ($query) use ($times) {
                            $query->where('start_time', '<', $times[0])
                                ->where('end_time', '>', $times[1]);
                        });
                })
                ->get();
        }

        return view('admin.bookings.search', compact('rooms'));
    }

    public function bookRoom(Request $request, EventService $eventService)
    {
        $request->merge([
            'user_id' => auth()->id()
        ]);

        $request->validate([
            'title'   => 'required',
            'room_id' => 'required',
        ]);

        $room = Room::findOrFail($request->input('room_id'));

        if ($eventService->isRoomTaken($request->all())) {
            return redirect()->back()
                    ->withInput()
                    ->withErrors(['recurring_until' => 'This room is not available until the recurring date you have chosen']);
        }

        if (!auth()->user()->is_admin && !$eventService->chargeHourlyRate($request->all(), $room)) {
            return redirect()->back()
                    ->withInput()
                    ->withErrors(['Please add more credits to your account. <a href="' . route('admin.balance.index') . '">My Credits</a>']);
        }

        $event = Event::create($request->all());

        if ($request->filled('recurring_until')) {
            $eventService->createRecurringEvents($request->all());
        }

        return redirect()->route('admin.systemCalendar')->withStatus('A room has been successfully booked');
    }
}
