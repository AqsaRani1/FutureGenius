<?php

namespace App\Http\Controllers;

use App\Models\course;
use App\Models\User;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function index()
    {
        $courses = course::with('instructors')->get();
        return view('admin.coursemange', compact('courses'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'duration' => 'required|integer|min:1',
        ]);



        course::create([
            'title' => $request->title,
            'description' => $request->description,
            'start_date' => $request->start_date,
            'duration' => $request->duration,
        ]);


        return back()->with('success', 'Course created successfully');
    }


    public function assignInstructor(Request $request, course $course)
    {
        $request->validate([
            'instructor_id' => 'required|exists:users,id'
        ]);


        $instructor = User::findOrFail($request->instructor_id);
        if ($instructor->role !== 'instructor') {
            return back()->with('error', 'User must be an instructor');
        }


        $course->instructors()->syncWithoutDetaching([$instructor->id]);


        return back()->with('success', 'Instructor assigned successfully');
    }


    public function students(Course $course)
    {
        $students = $course->students; // via relation
        return view('admin.courses.students', compact('course', 'students'));
    }
    public function removeInstructor(Course $course, User $instructor)
    {
        if ($instructor->role !== 'instructor') {
            return back()->with('error', 'User is not an instructor');
        }

        $course->instructors()->detach($instructor->id);

        return back()->with('success', 'Instructor removed successfully');
    }

    public function destroy(Course $course)
    {
        // remove relationships before deleting
        $course->instructors()->detach();
        $course->students()->detach();

        $course->delete();

        return back()->with('success', 'Course deleted successfully');
    }
    public function availableCourses()
    {
        $student = auth()->user();

        // Get courses student is NOT enrolled in
        $courses = Course::whereDoesntHave('students', function ($q) use ($student) {
            $q->where('users.id', $student->id);
        })->get();

        return view('student.available_courses', compact('courses'));
    }

    public function enroll(Course $course)
    {
        $student = auth()->user();

        if ($student->role !== 'student') {
            return back()->with('error', 'Only students can enroll in courses');
        }

        $course->students()->syncWithoutDetaching([$student->id]);

        return back()->with('success', 'Enrolled successfully!');
    }

    public function myCourses()
    {
        $student = auth()->user();
        $courses = $student->coursesEnrolled()->get();

        return view('student.my_courses', compact('courses'));
    }

    public function unenroll(Course $course)
    {
        $student = auth()->user();

        $course->students()->detach($student->id);

        return back()->with('success', 'Unenrolled successfully!');
    }
    public function student()
    {
        return $this->belongsToMany(User::class, 'enrollments', 'course_id', 'student_id');
    }
    public function overview()
    {
        $now = now('Asia/Karachi');

        $courses = auth()->user()
            ->coursesEnrolled()
            ->with([
                'events' => function ($q) use ($now) {

                    $q->where(function ($q2) use ($now) {
                        $q2
                            ->where('start', '>', $now)
                            ->orWhere(function ($q3) use ($now) {
                                $q3->where('start', '<=', $now)
                                    ->where(function ($q4) use ($now) {
                                        $q4->where('end_date', '>=', $now)
                                            ->orWhereNull('end_date'); // include ongoing with no end
                                    });
                            });
                    })
                        ->with('quiz')
                        ->orderBy('start');
                }
            ])
            ->get();

        return view('student.overview', compact('courses', 'now'));
    }


    public function show(Course $course)
    {
        $course->load([
            'instructors',
            'events.quiz', // quiz relation if exists
            'events' => function ($q) {
                $q->orderBy('event_date');
            }
        ]);

        $studentId = auth()->id();

        // Load assignment submissions + quiz attempts for this student
        foreach ($course->events as $event) {

            // Assignment submission
            if ($event->type === 'assignment') {
                $event->submission = \App\Models\AssignmentSubmission::where('event_id', $event->id)
                    ->where('student_id', $studentId)
                    ->first();
            }

            // Quiz attempt
            if ($event->type === 'quiz' && $event->quiz) {
                $event->attempt = \App\Models\QuizAttempt::where('quiz_id', $event->quiz->id)
                    ->where('student_id', $studentId)
                    ->first();
            }
        }

        return view('student.courseshow', compact('course'));
    }


}
