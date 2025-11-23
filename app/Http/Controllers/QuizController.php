<?php

namespace App\Http\Controllers;

use App\Models\CourseEvents;
use App\Models\QuizAttempt;
use App\Models\QuizAnswer;
use Illuminate\Http\Request;
use Carbon\Carbon;

class QuizController extends Controller
{
    // Start quiz (create attempt ONCE)
    public function attempt(CourseEvents $event)
    {
        if ($event->type !== 'quiz') abort(403);
        $quiz = $event->quiz()->first();

        $attempt = QuizAttempt::firstOrCreate(
            [
                'quiz_id' => $quiz->id,
                'student_id' => auth()->id(),
            ],
            [
                'score' => 0,
                'started_at' => now(), // use raw time
            ]
        );

        return redirect()->route('student.quiz.question', [
            'event' => $event->id,
            'number' => 1
        ]);
    }

    // Show question with FIXED END TIME
    public function question(CourseEvents $event, $number)
    {
        $quiz = $event->quiz()->first();
        $attempt = QuizAttempt::where('quiz_id', $quiz->id)
            ->where('student_id', auth()->id())
            ->firstOrFail();

        $total = $quiz->questions()->count();
        if ($number < 1 || $number > $total) abort(404);

        // FIXED END TIMESTAMP
        $quizEndTimestamp = Carbon::parse($attempt->started_at)
            ->addMinutes($quiz->duration)
            ->timestamp;

        // Timer over?
        if (now()->timestamp >= $quizEndTimestamp) {
            return redirect()->route('student.quiz.finish', $event->id);
        }

        $question = $quiz->questions()->skip($number - 1)->first();

        return view('student.quiz_question', compact(
            'event',
            'question',
            'number',
            'total',
            'quizEndTimestamp',
            'quiz'
        ));
    }

    // Save answer
    public function submitAnswer(Request $request, CourseEvents $event, $number)
    {
        $quiz = $event->quiz;
        $questions = $quiz->questions;
        $question = $questions[$number - 1];

        $attempt = QuizAttempt::where('quiz_id', $quiz->id)
            ->where('student_id', auth()->id())
            ->firstOrFail();

        QuizAnswer::updateOrCreate(
            [
                'quiz_attempt_id' => $attempt->id,
                'quiz_question_id' => $question->id
            ],
            ['quiz_option_id' => $request->option_id]
        );

        if ($number < $questions->count()) {
            return redirect()->route('student.quiz.question', [
                'event' => $event->id,
                'number' => $number + 1
            ]);
        }

        return redirect()->route('student.quiz.finish', $event->id);
    }

    // FINISH QUIZ — POST ONLY
    public function finish(CourseEvents $event)
    {
        $quiz = $event->quiz;
        $attempt = QuizAttempt::where('quiz_id', $quiz->id)
            ->where('student_id', auth()->id())
            ->firstOrFail();

        $correct = $attempt->answers()
            ->whereHas('option', fn($q) => $q->where('is_correct', 1))
            ->count();

        $attempt->update(['score' => $correct]);

        return redirect()->route('student.quiz.result', $event->id);
    }

    // SHOW RESULT
  public function result(CourseEvents $event)
{
    $quiz = $event->quiz;
    $attempt = QuizAttempt::where('quiz_id', $quiz->id)
        ->where('student_id', auth()->id())
        ->firstOrFail();

    $totalQuestions = $quiz->questions()->count();
    $correctAnswers = $attempt->answers()
        ->whereHas('option', fn($q) => $q->where('is_correct', 1))
        ->count();

    $score = round(($correctAnswers / $totalQuestions) * 100);

    return view('student.quiz_result', compact('quiz', 'score'));
}


}
