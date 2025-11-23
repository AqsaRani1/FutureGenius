<?php

namespace App\Http\Controllers;

use App\Models\AssignmentSubmission;
use App\Models\CourseEvents;
use App\Models\QuizAttempt;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function dashboard()
    {
        $courses = auth()->user()->coursesEnrolled()->with([
            'events' => function ($q) {
                $q->where('event_date', '<=', now())
                    ->where(function ($q2) {
                        $q2->whereNull('end_date')->orWhere('end_date', '>=', now());
                    })
                    ->orderBy('event_date');
            }
        ])->get();

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

    public function eventDetail(CourseEvents $event)
    {
        return view('student.event_detail', compact('event'));
    }

    // -------------------------------
    // ASSIGNMENT RESULT FOR STUDENT
    // -------------------------------
    public function assignmentResult(CourseEvents $event)
    {
        $submission = AssignmentSubmission::where('event_id', $event->id)
            ->where('student_id', auth()->id())
            ->first();

        if (!$submission) {
            return back()->with('error', 'No submission found');
        }

        return view('student.assignment_result', compact('event', 'submission'));
    }

    // -------------------------------
    // QUIZ RESULT FOR STUDENT
    // -------------------------------
    public function quizResult(CourseEvents $event)
    {
        $attempt = QuizAttempt::with('quiz', 'student')
            ->where('quiz_id', $event->quiz->id ?? null)
            ->where('student_id', auth()->id())
            ->first();

        if (!$attempt) {
            return back()->with('error', 'No quiz attempt found');
        }

        return view('student.quiz_result', compact('event', 'attempt'));
    }
}
