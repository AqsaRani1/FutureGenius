<?php

namespace App\Http\Controllers;

use App\Models\CourseEvents;
use App\Models\QuizAttempt;
use App\Models\QuizAnswer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Auth;

class QuizController extends Controller
{
    /**
     * Start the quiz
     */
    public function attempt(CourseEvents $event)
    {
        if ($event->type !== 'quiz') {
            abort(403, 'Not a quiz event.');
        }

        $quiz = $event->quiz()->first();
        if (!$quiz) {
            return back()->with('error', 'Quiz not set for this event.');
        }

        $now = Carbon::now('Asia/Karachi');
        $start = Carbon::parse($event->start, 'Asia/Karachi');
        $end = $event->end_date ? Carbon::parse($event->end_date) : null;

        $isOpen = $end ? $now->between($start, $end) : $now->greaterThanOrEqualTo($start);
        if (!$isOpen) {
            return back()->with('error', 'Quiz not available right now.');
        }

        // Find or create quiz attempt
        $attempt = QuizAttempt::firstOrCreate(
            ['quiz_id' => $quiz->id, 'student_id' => Auth::id()],
            ['score' => 0, 'started_at' => now('Asia/Karachi')]
        );

        // First question
        $number = 1;
        return $this->showQuestion($event, $number, $attempt);
    }

    /**
     * Show a specific question
     */
    public function question(CourseEvents $event, $number)
    {
        $quiz = $event->quiz;
        $attempt = QuizAttempt::where('quiz_id', $quiz->id)
            ->where('student_id', Auth::id())
            ->firstOrFail();

        return $this->showQuestion($event, $number, $attempt);
    }

    /**
     * Common method to show question with timer
     */
    private function showQuestion(CourseEvents $event, $number, QuizAttempt $attempt)
    {
        $quiz = $event->quiz;
        $questions = $quiz->questions;

        if ($number < 1 || $number > $questions->count()) abort(404);

        $question = $questions[$number - 1];

        // Load question start times from JSON
        $qTimes = $attempt->question_started_at ? json_decode($attempt->question_started_at, true) : [];

        // If question not started yet, save start time
        if (!isset($qTimes[$number])) {
            $qTimes[$number] = now('Asia/Karachi')->toDateTimeString();
            $attempt->update(['question_started_at' => json_encode($qTimes)]);
        }

        $questionStarted = Carbon::parse($qTimes[$number]);
        $questionDuration = $quiz->duration ? $quiz->duration * 60 : 60; // default 1 min
        $remaining = $questionDuration - Carbon::now('Asia/Karachi')->diffInSeconds($questionStarted);

        if ($remaining <= 0) {
            return $this->autoSubmitOnTimeout($event, $number, $attempt);
        }

        return view('student.quiz_question', compact('event','quiz','question','number','remaining'));
    }

    /**
     * Store submitted answer
     */
    public function submitAnswer(Request $request, CourseEvents $event, $number)
    {
        $quiz = $event->quiz;
        $questions = $quiz->questions;
        $question = $questions[$number - 1];

        $request->validate([
            'option_id' => 'nullable|exists:quiz_options,id',
        ]);

        $attempt = QuizAttempt::where('quiz_id', $quiz->id)
            ->where('student_id', Auth::id())
            ->firstOrFail();

        QuizAnswer::updateOrCreate(
            [
                'quiz_attempt_id'  => $attempt->id,
                'quiz_question_id' => $question->id
            ],
            [
                'quiz_option_id' => $request->option_id ?? null
            ]
        );

        // Next question or finish
        if ($number < $questions->count()) {
            return redirect()->route('student.quiz.question', [
                'event'  => $event->id,
                'number' => $number + 1
            ]);
        }

        return redirect()->route('student.quiz.result', $event->id);
    }

    /**
     * Auto-submit unanswered question when timer expires
     */
    private function autoSubmitOnTimeout(CourseEvents $event, $number, QuizAttempt $attempt)
    {
        $quiz = $event->quiz;
        $questions = $quiz->questions;
        $question = $questions[$number - 1];

        QuizAnswer::updateOrCreate(
            [
                'quiz_attempt_id' => $attempt->id,
                'quiz_question_id' => $question->id
            ],
            ['quiz_option_id' => null]
        );

        if ($number < $questions->count()) {
            return redirect()->route('student.quiz.question', [
                'event' => $event->id,
                'number' => $number + 1
            ]);
        }

        return redirect()->route('student.quiz.result', $event->id);
    }

    /**
     * Show final quiz result
     */
    public function result(CourseEvents $event)
    {
        $quiz = $event->quiz;

        $attempt = QuizAttempt::where('quiz_id', $quiz->id)
            ->where('student_id', Auth::id())
            ->firstOrFail();

        $answers = $attempt->answers()->with('option')->get();

        $correct = $answers->filter(function ($ans) {
            return $ans->option && $ans->option->is_correct == 1;
        })->count();

        $score = round(($correct / $quiz->questions->count()) * 100);
        $attempt->update(['score' => $score]);

        return view('student.quiz_result', compact('quiz', 'score'));
    }
}