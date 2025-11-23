<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\InstructorAssignmentController;
use App\Http\Controllers\InstructorController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\StudentAssignmentController;
use App\Http\Controllers\StudentController;
use Illuminate\Support\Facades\Route;





Route::get('/index', function () {
    return view('index');
});

Route::get('/', function () {
    return view('auth.login');
});
Route::prefix('/student')->middleware(['auth', 'role:student'])->group(function () {
    Route::get('/dashboard', [CourseController::class, 'overview'])->name('student.dashboard');
    Route::get('/courses/available', [CourseController::class, 'availableCourses'])->name('courses.available');
    Route::post('/courses/{course}/enroll', [CourseController::class, 'enroll'])->name('courses.enroll');
    Route::get('/courses/my', [CourseController::class, 'myCourses'])->name('courses.my');
    Route::get('/overview', [CourseController::class, 'overview'])->name('overview');
    Route::delete('/courses/{course}/unenroll', [CourseController::class, 'unenroll'])->name('courses.unenroll');
    Route::get('/courses/{course}', [CourseController::class, 'show'])->name('courses.show');
    Route::get('/quiz/{event}/attempt', [QuizController::class, 'attempt'])->name('student.quiz.attempt');
    Route::get('/student/event/{event}', [StudentController::class, 'viewEvent'])
        ->name('student.event.view');

    // Start quiz
    Route::get('/quiz/{event}/attempt', [QuizController::class, 'attempt'])
        ->name('student.quiz.attempt');

    // Show question
    Route::get('/quiz/{event}/question/{number}', [QuizController::class, 'question'])
        ->name('student.quiz.question');

    // Submit answer
    Route::post('/quiz/{event}/question/{number}/submit', [QuizController::class, 'submitAnswer'])
        ->name('student.quiz.answer');

    // Finish quiz (POST only)
    Route::post('/quiz/{event}/finish', [QuizController::class, 'finish'])
        ->name('student.quiz.finish');

    // Show result
    Route::get('/quiz/{event}/result', [QuizController::class, 'result'])
        ->name('student.quiz.result');

    Route::get('/assignment/{event}', [StudentAssignmentController::class, 'show'])
        ->name('student.assignment.view');
    Route::post('/assignment/{event}/submit', [StudentAssignmentController::class, 'submit'])
        ->name('student.assignment.submit');
    Route::post('/assignment/{event}/delete', [StudentAssignmentController::class, 'deleteSubmission'])
        ->name('student.assignment.delete');
    Route::get('assignments', [StudentAssignmentController::class, 'index'])->name('student.assignments.index');
    // Route::get('assignments/{id}', [StudentAssignmentController::class, 'show'])->name('student.assignments.show');
    Route::post('assignments/{id}/submit', [StudentAssignmentController::class, 'submit'])->name('student.assignments.submit');

    // Assignment
    Route::get('/student/event/{event}/assignment', [AssignmentController::class, 'view'])->name('student.assignment.view');
    Route::post('/student/event/{event}/assignment/submit', [AssignmentController::class, 'submit'])->name('student.assignment.submit');

    // Quiz
    Route::get('/student/event/{event}/quiz', [QuizController::class, 'attempt'])->name('student.quiz.attempt');
    Route::post('/student/event/{event}/quiz/{number}', [QuizController::class, 'answer'])->name('student.quiz.answer');
    Route::post('/student/event/{event}/quiz/finish', [QuizController::class, 'finish'])->name('student.quiz.finish');

    // View quiz result
    Route::get('/student/quiz/result/{attempt}', [QuizController::class, 'result'])->name('student.quiz.result');

});

Route::prefix('/instructor')->middleware(['auth', 'role:instructor'])->group(function () {
    Route::get('/dashboard', [InstructorController::class, 'mycourses'])->name('instructor.dashboard');
    Route::get('/mycourses', [InstructorController::class, 'mycourses'])->name('mycourses');
    Route::get('/courses/{id}', [InstructorController::class, 'course'])->name('instructor.course');
    Route::get('/managecourse', [InstructorController::class, 'managecourse'])->name('managecourse');

    Route::post('/courses/{course}/content', [InstructorController::class, 'storeContent'])->name('instructor.content.store');
    Route::post('/courses/{course}/event', [InstructorController::class, 'storeEvent'])->name('instructor.event.store');
    Route::get('/events/{event}/quiz/create', [InstructorController::class, 'createQuiz'])->name('quiz.create');
    Route::post('/events/{event}/quiz/store', [InstructorController::class, 'storeQuiz'])->name('quiz.store');
    Route::get('/quiz/{quiz}/questions', [InstructorController::class, 'showQuizQuestions'])->name('quiz.questions');
    Route::post('/quiz/{quiz}/questions/store', [InstructorController::class, 'storeQuizQuestions'])->name('quiz.questions.store');


    Route::get('instructor/assignments', [InstructorAssignmentController::class, 'index'])->name('instructor.assignments.index');
    Route::get('instructor/assignments/create', [InstructorAssignmentController::class, 'create'])->name('instructor.assignments.create');
    Route::post('instructor/assignments/store', [InstructorAssignmentController::class, 'store'])->name('instructor.assignments.store');
    Route::get('instructor/assignments/{id}/submissions', [InstructorAssignmentController::class, 'submissions'])->name('instructor.assignments.submissions');
    // Show form to create quiz
    Route::get('/course/{course}/quiz/create', [QuizController::class, 'create'])->name('quizcreate');
    Route::post('/course/{course}/quiz/store', [QuizController::class, 'store'])->name('quizstore');

    // Add questions to quiz
    Route::get('/quiz/{quiz}/questions/create', [InstructorController::class, 'createQuestion'])->name('quiz.questions.create');
    Route::post('/quiz/{quiz}/questions/store', [InstructorController::class, 'storeQuestion'])->name('quiz.questionsstore');
    Route::post(
        'instructor/assignments/submissions/{id}/grade',
        [InstructorAssignmentController::class, 'gradeSubmission']
    )->name('instructor.assignment.grade');

    Route::get('/assignments/{event}/submissions', [InstructorController::class, 'assignmentSubmissions'])->name('instructor.assignment.submissions');

    // Route::get('/instructor/submission/{id}', [InstructorController::class, 'gradeAssignment'])->name('instructor.assignment.grade');
    Route::post('/submission/{id}', [InstructorController::class, 'storeGradeAssignment'])->name('instructor.assignment.grade.store');

    // Quizzes
    Route::get('/instructor/quizzes/{event}/attempts', [InstructorController::class, 'quizAttempts'])->name('instructor.quiz.attempts');

    Route::get('/instructor/quiz-attempt/{id}', [InstructorController::class, 'gradeQuiz'])->name('instructor.quiz.grade');


});

Route::prefix('/admin')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/usermanagment', [AdminController::class, 'users'])->name('admin.manageuser');
    Route::get('/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('/users', [AdminController::class, 'index'])->name('admin.users');
    Route::post('/users', [AdminController::class, 'store'])->name('admin.users.store');
    Route::delete('/users/{user}', [AdminController::class, 'destroy'])->name('admin.users.delete');
    Route::put('/users/{user}', [AdminController::class, 'update'])->name('admin.users.update');
    // Route::get('/coursemanagment', [AdminController::class, 'course'])->name('admin.managecourse');
    Route::get('/courses', [CourseController::class, 'index'])->name('admin.course');
    Route::post('/course', [CourseController::class, 'store'])->name('admin.course.store');
    Route::delete('/course/{course}', [CourseController::class, 'destroy'])->name('admin.course.delete');
    Route::put('/course/{course}', [CourseController::class, 'update'])->name('admin.course.update');
    Route::post('/courses/{course}/assign-instructor', [CourseController::class, 'assignInstructor'])
        ->name('courses.assignInstructor');

    Route::get('/courses/{course}/students', [CourseController::class, 'students'])
        ->name('courses.students');
    Route::prefix('admin')->group(function () {
        Route::post('/courses/{course}/assign-instructor', [CourseController::class, 'assignInstructor'])
            ->name('courses.assignInstructor');

        Route::delete('/courses/{course}/remove-instructor/{instructor}', [CourseController::class, 'removeInstructor'])
            ->name('courses.removeInstructor');

        Route::delete('/courses/{course}', [CourseController::class, 'destroy'])
            ->name('courses.destroy');

        Route::get('/courses/{course}/students', [CourseController::class, 'students'])
            ->name('courses.students');
    });
});
Route::get('/dashboard', [AuthenticatedSessionController::class, 'dashboard'])->name('dashboard');

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';