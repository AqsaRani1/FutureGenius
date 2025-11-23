<?php

namespace App\Http\Controllers;

use App\Models\CourseEvents;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function dashboard(){
        $courses = auth()->user()->coursesEnrolled()->with(['events' => function($q) {
    $q->where('event_date', '<=', now())
      ->where(function($q2) {
          $q2->whereNull('end_date')->orWhere('end_date', '>=', now());
      })
      ->orderBy('event_date');
}])->get();

        return view('dashboards.student');
    }
public function viewEvent(CourseEvents $event)
{
    if ($event->type !== 'live_session') {
        abort(404, 'Not a live session');
    }

    $now = now();

    $status = 'upcoming';

    if ($event->event_date && $event->end_date) {

        if ($now->lt($event->event_date)) {
            $status = 'upcoming';
        } elseif ($now->between($event->event_date, $event->end_date)) {
            $status = 'live';
        } else {
            $status = 'ended';
        }

    }

    return view('student.live_session', compact('event', 'status'));
}

}
