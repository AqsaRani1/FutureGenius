<?php

namespace App\Http\Controllers;

use App\Models\course;
use App\Models\CourseEvents;
use Illuminate\Http\Request;
 use Carbon\Carbon;
 use App\Models\Quiz;
use App\Models\QuizQuestion;
use App\Models\QuizOption;
class InstructorController extends Controller
{
    public function dashboard()
    {


        return view('dashboards.instructor');
    }
    public function course($id)
    {
          $course = Course::with(['contents', 'events', 'students'])->findOrFail($id);

    // Filter upcoming events only
    $course->upcomingEvents = $course->events->filter(function($event) {
        return Carbon::parse($event->event_date)->isFuture();
    });

    return view('instructor.managecourse', compact('course'));
    }
    // public function managecourse()
    // {
    //     $courses = auth()->user()->coursesTaught;
    //     return view('instructor.managecourse', compact('courses'));
    // }

    public function mycourses()
    {
        $courses = auth()->user()->coursesTaught;
        return view('instructor.mycourses', compact('courses'));
    }
    public function storeContent(Request $request, course $course)
    {
        $request->validate([
            'title' => 'required|string',
            'description' => 'nullable|string',
            'file' => 'nullable|file'
        ]);

        $path = $request->file ? $request->file->store('course_files') : null;

        $course->contents()->create([
            'title' => $request->title,
            'description' => $request->description,
            'file_path' => $path
        ]);

        return back()->with('success', 'Content added successfully');
    }

  public function storeEvent(Request $request, Course $course)
{
    $request->validate([
        'title' => 'required|string',
        'description' => 'nullable|string',
        'event_date' => 'required|date',
        'end_date' => 'nullable|date|after_or_equal:event_date',
        'start' => 'nullable|date|after_or_equal:event_date',
        'type' => 'required|in:assignment,quiz,live_session',
        'file_path' => 'nullable|file|max:2048',          // assignment file
        'meeting_link' => 'nullable|url',           // live session
    ]);

    if ($request->hasFile('file_path')) {
    $file = $request->file('file_path');

    // Clean filename (optional: remove spaces, special chars)
    $filename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
    $extension = $file->getClientOriginalExtension();
    $fullName = $filename . '.' . $extension;

    // Store in 'public/assignments'
    $path = $file->storeAs('assignments', $fullName, 'public');
} else {
    $path = null;
}

    $course->events()->create([
        'title' => $request->title,
        'description' => $request->description,
        'event_date' => $request->event_date,
        'end_date' => $request->end_date,
        'type' => $request->type,
        'file_path' => $path,
        'meeting_link' => $request->meeting_link,
        'start' =>$request->start_date,
    ]);

    return back()->with('success', 'Event created successfully');
}



public function createQuiz(CourseEvents $event) {
    return view('instructor.quiz.create', compact('event'));
}

public function storeQuiz(Request $request, CourseEvents $event) {
    $request->validate(['title' => 'required|string']);
    $quiz = Quiz::create([
        'course_event_id' => $event->id,
        'title' => $request->title,
    ]);
    return redirect()->route('quiz.questions', $quiz->id)->with('success', 'Quiz created. Add questions now.');
}

public function showQuizQuestions(Quiz $quiz) {
    return view('instructor.quiz.questions', compact('quiz'));
}

public function storeQuizQuestions(Request $request, Quiz $quiz) {
    $request->validate([
        'questions.*.question' => 'required|string',
        'questions.*.options.*.text' => 'required|string',
        'questions.*.options.*.is_correct' => 'required|in:0,1'
    ]);

    foreach ($request->questions as $qData) {
        $question = QuizQuestion::create([
            'quiz_id' => $quiz->id,
            'question' => $qData['question'],
            'type' => 'mcq',
        ]);

        foreach ($qData['options'] as $option) {
            QuizOption::create([
                'quiz_question_id' => $question->id,
                'option_text' => $option['text'],
                'is_correct' => (bool)$option['is_correct'], // cast to boolean
            ]);
        }
    }

    return redirect()->route('instructor.course', $quiz->event->course_id)
                     ->with('success', 'Questions added successfully.');
}

public function create(Course $course)
{
    return view('quiz.create', compact('course'));
}

public function store(Request $request, Course $course)
{
    $request->validate([
        'title' => 'required|string',
        'description' => 'nullable|string',
    ]);

    $quiz = Quiz::create([
        'course_id' => $course->id,
        'title' => $request->title,
        'description' => $request->description,
    ]);

    // Redirect to add first question
    return redirect()->route('quiz.questions.create', $quiz->id)
                     ->with('success', 'Quiz created! Add questions now.');
}

public function createQuestion(Quiz $quiz)
{
    return view('instructor.quiz.questions', compact('quiz'));
}

public function storeQuestion(Request $request, Quiz $quiz)
{
    $request->validate([
        'question' => 'required|string',
        'options.*.text' => 'required|string',
        'options.*.is_correct' => 'required|in:0,1',
    ]);

    $question = QuizQuestion::create([
        'quiz_id' => $quiz->id,
        'question' => $request->question,
        'type' => 'mcq',
    ]);

    foreach ($request->options as $option) {
        QuizOption::create([
            'quiz_question_id' => $question->id,
            'option_text' => $option['text'],
            'is_correct' => (bool)$option['is_correct'],
        ]);
    }

    // Stay on same page with new empty form
    return redirect()->route('quiz.questions.create', $quiz->id)
                     ->with('success', 'Question added! Add another or finish.');
}

// public function courseevents($id)
// {
//     $course = Course::with(['contents', 'events', 'students'])->findOrFail($id);

//     // Filter upcoming events only
//     $course->upcomingEvents = $course->events->filter(function($event) {
//         return Carbon::parse($event->event_date)->isFuture();
//     });

//     return view('instructor.managecourse', compact('course'));
// }

}