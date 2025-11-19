<?php
// app/Http/Controllers/InstructorAssignmentController.php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use App\Models\CourseEvents;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InstructorAssignmentController extends Controller
{
    // public function index()
    // {
    //     $assignments = courseEvents::where('instructor_id', Auth::id())->latest()->get();
    //     return view('instructor.assignments.index', compact('assignments'));
    // }

    // public function create()
    // {
    //     return view('instructor.assignments.create');
    // }


    public function submissions($assignment_id)
    {
        $assignment = CourseEvents::with('submissions.student')->findOrFail($assignment_id);
        return view('instructor.assignments.submissions', compact('assignment'));
    }
    public function gradeSubmission(Request $request, $submission_id)
{
    $request->validate([
        'grade' => 'required|integer|min:0|max:100',
        'feedback' => 'nullable|string'
    ]);

    $submission = AssignmentSubmission::findOrFail($submission_id);

    $submission->grade = $request->grade;
    $submission->feedback = $request->feedback;
    $submission->save();

    return back()->with('success', 'Submission graded successfully');
}

}