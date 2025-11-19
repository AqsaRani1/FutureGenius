<?php
// app/Http/Controllers/StudentAssignmentController.php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use App\Models\CourseEvents;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

namespace App\Http\Controllers;

use App\Models\CourseEvents;
use App\Models\AssignmentSubmission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class StudentAssignmentController extends Controller
{
    public function index()
    {
        $assignments = CourseEvents::where('type', 'assignment')->latest()->get();
        return view('student.assignments.index', compact('assignments'));
    }

    public function show($id)
    {
        $assignment = CourseEvents::findOrFail($id);
        return view('student.assignments.show', compact('assignment'));
    }

    public function submit(Request $request, $id)
    {
        $request->validate([
            'answer_text' => 'nullable|string',
            'file' => 'nullable|file|mimes:pdf,doc,docx,ppt,pptx'
        ]);

        // Check if student already submitted
        $submission = AssignmentSubmission::where('event_id', $id)
            ->where('student_id', Auth::id())
            ->first();

        if (!$submission) {
            $submission = new AssignmentSubmission();
            $submission->event_id = $id;
            $submission->student_id = Auth::id();
        }

        // Replace file logic
        if ($request->hasFile('file')) {

            // Delete old file if exists
            if ($submission->file && Storage::disk('public')->exists($submission->file)) {
                Storage::disk('public')->delete($submission->file);
            }

            // Store new file
            $submission->file = $request->file('file')->store('assignment_submissions', 'public');
        }

        // Replace written answer
        if ($request->answer_text) {
            $submission->answer_text = $request->answer_text;
        }

        $submission->save();

        return back()->with('success', 'Assignment submitted successfully!');
    }

    public function deleteSubmission($id)
    {
        $submission = AssignmentSubmission::where('event_id', $id)
            ->where('student_id', Auth::id())
            ->firstOrFail();

        // Delete file
        if ($submission->file && Storage::disk('public')->exists($submission->file)) {
            Storage::disk('public')->delete($submission->file);
        }

        $submission->delete();

        return back()->with('success', 'Submission deleted!');
    }
}
