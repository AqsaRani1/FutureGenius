@extends('dashboards.student')

@section('content')
    <style>
        /* Custom Styling */
        .course-card {
            border-radius: 16px;
            overflow: hidden;
            background: #ffffff;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
        }

        .course-header {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: #fff;
            padding: 30px;
        }

        .course-header h2 {
            font-weight: 700;
            font-size: 1.8rem;
        }

        .course-header p {
            opacity: 0.9;
            font-size: 1rem;
        }

        .course-actions button {
            border-radius: 8px;
            font-weight: 600;
            padding: 10px;
        }

        .details-box {
            background: #f8f9fb;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 15px;
            transition: 0.3s;
        }

        .details-box:hover {
            background: #eef1f8;
            transform: translateY(-2px);
        }

        .details-box strong {
            color: #374151;
        }

        .badge {
            font-size: 0.85rem;
            padding: 6px 12px;
            border-radius: 6px;
        }

        .back-btn {
            border-radius: 8px;
            font-weight: 500;
        }
    </style>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">

                <!-- Course Card -->
                <div class="shadow course-card">
                    <div class="row g-0">

                        <!-- Left Section (Header) -->
                        <div class="col-md-6 course-header d-flex flex-column justify-content-center">
                            <h2>{{ $course->title }}</h2>
                            <p>{{ $course->description }}</p>

                            <div class="mt-4 course-actions">
                                @if (auth()->user()->coursesEnrolled->contains($course->id))
                                    <form action="{{ route('courses.unenroll', $course->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger w-100">❌ Unenroll</button>
                                    </form>
                                @else
                                    <form action="{{ route('courses.enroll', $course->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-light text-primary w-100">🚀 Enroll
                                            Now</button>
                                    </form>
                                @endif
                            </div>
                        </div>

                        <!-- Right Section (Details) -->
                        <div class="p-4 col-md-6">
                            <h5 class="mb-3 fw-bold text-dark">📌 Course Details</h5>

                            <div class="details-box">
                                <strong>👨‍🏫 Instructor(s):</strong><br>
                                @forelse($course->instructors as $instructor)
                                    <span class="badge bg-primary">{{ $instructor->name }}</span>
                                @empty
                                    <span class="text-muted">No instructor assigned</span>
                                @endforelse
                            </div>

                            <div class="details-box">
                                <strong>📅 Start Date:</strong><br>
                                <span>{{ $course->start_date ?? 'Not defined' }}</span>
                            </div>

                            <div class="details-box">
                                <strong>⏳ Duration:</strong><br>
                                <span>{{ $course->duration ?? 'Not specified' }} Days</span>
                            </div>

                            <div class="details-box">
                                <strong>📖 Status:</strong><br>
                                <span class="badge bg-success">Active</span>
                            </div>

                            <div class="mt-4 d-flex justify-content-end">
                                <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary back-btn">⬅ Back</a>
                            </div>
                        </div>

                    </div>
                </div>
                <!-- End Course Card -->

            </div>
        </div>
        <div class="mt-5">
            <h3 class="mb-3 fw-bold">📌 Course Activities</h3>

            @foreach ($course->events as $event)
                @php
                    $now = now();

                    $start = $event->start ? \Carbon\Carbon::parse($event->start) : null;
                    $end = $event->end_date ? \Carbon\Carbon::parse($event->end_date) : null;

                    // Quiz Open Status
                    $isQuizOpen = $event->type === 'quiz' ? $start && $start <= $now && (!$end || $now <= $end) : false;

                    $submission = $event->submission ?? null;
                    $attempt = $event->attempt ?? null;
                @endphp

                <div class="details-box">
                    <strong>{{ ucfirst($event->type) }}:</strong> {{ $event->title }}

                    <br>
                    <small class="text-muted">
                        {{ $event->event_date ? \Carbon\Carbon::parse($event->event_date)->format('M d, H:i') : '' }}
                        @if ($end)
                            → {{ $end->format('M d, H:i') }}
                        @endif
                    </small>

                    <div class="mt-2">

                        {{-- Assignment --}}
                        @if ($event->type === 'assignment')
                            {{-- If submitted --}}
                            @if ($submission)
                                <div class="text-success fw-bold">
                                    ✔ Submitted on {{ $submission->created_at->format('M d, H:i') }}
                                </div>

                                {{-- If graded --}}
                                @if ($submission->grade !== null)
                                    <div class="mt-1">
                                        <strong>Grade:</strong> {{ $submission->grade }} / 100 <br>
                                        <strong>Feedback:</strong> {{ $submission->feedback ?? 'No feedback' }}
                                    </div>
                                @else
                                    <div class="text-warning">⏳ Waiting for grading</div>
                                @endif
                            @else
                                {{-- If not submitted --}}
                                <a href="{{ route('student.assignment.view', $event->id) }}"
                                    class="text-primary fw-bold">Submit Assignment</a>
                            @endif


                            {{-- Quiz --}}
                        @elseif ($event->type === 'quiz')
                            @if (!$event->quiz)
                                <span class="text-danger">No question added yet</span>
                            @elseif ($attempt)
                                <div class="text-success fw-bold">
                                    ✔ Attempted on {{ $attempt->created_at->format('M d, H:i') }}
                                </div>
                                <div>
                                    <strong>Score:</strong> {{ $attempt->score }}
                                </div>
                                {{-- <a href="{{ route('student.quiz.result', $attempt->id) }}" class="text-primary fw-bold">
                                    View Result
                                </a> --}}
                            @elseif ($isQuizOpen)
                                <a href="{{ route('student.quiz.attempt', $event->id) }}" class="text-primary fw-bold">
                                    Attempt Quiz
                                </a>
                            @else
                                <span class="text-danger">Quiz Closed</span>
                            @endif


                            {{-- Live Session --}}
                        @elseif ($event->type === 'live_session')
                            @php
                                $canJoin = $event->meeting_link && $now >= $start && (!$end || $now <= $end);
                            @endphp

                            @if ($canJoin)
                                <a href="{{ $event->meeting_link }}" target="_blank" class="fw-bold text-primary">
                                    Join Live Session
                                </a>
                            @elseif (!$event->meeting_link)
                                <span class="text-muted">No link added</span>
                            @else
                                <span class="text-muted">Not started yet</span>
                            @endif
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

    </div>
@endsection
