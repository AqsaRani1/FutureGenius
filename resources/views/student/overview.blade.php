@extends('dashboards.student')

@section('content')
    <div class="container mx-auto mt-10 space-y-8">

        <!-- Student Stats -->
        <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
            <div class="p-6 text-white shadow-md bg-gradient-to-r from-indigo-500 to-indigo-700 rounded-xl">
                <h3 class="text-lg font-semibold">📚 Enrolled Courses</h3>
                <p class="mt-2 text-3xl font-bold">{{ auth()->user()->coursesEnrolled->count() }}</p>
            </div>

            <div class="p-6 text-white shadow-md bg-gradient-to-r from-green-400 to-green-600 rounded-xl">
                <h3 class="text-lg font-semibold">✅ Completed Courses</h3>
                <p class="mt-2 text-3xl font-bold">{{ $completedCourses ?? 0 }}</p>
            </div>

            <div class="p-6 text-white shadow-md bg-gradient-to-r from-yellow-400 to-orange-500 rounded-xl">
                <h3 class="text-lg font-semibold">⭐ Progress</h3>
                <p class="mt-2 text-3xl font-bold">{{ $progress ?? 0 }}%</p>
            </div>
        </div>

        <!-- Enrolled Courses -->
        <div>
            <h2 class="mb-4 text-2xl font-bold">Your Courses</h2>
            <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                @forelse(auth()->user()->coursesEnrolled as $course)
                    @php
                        $colors = [
                            'bg-red-200',
                            'bg-green-200',
                            'bg-blue-200',
                            'bg-yellow-200',
                            'bg-purple-200',
                            'bg-pink-200',
                        ];
                        $randomColor = $colors[array_rand($colors)];
                    @endphp

                    <div class="p-6 rounded-lg shadow-lg {{ $randomColor }}">
                        <h3 class="mb-2 text-lg font-bold">{{ $course->title }}</h3>
                        <p class="mb-3 text-sm text-gray-700">{{ Str::limit($course->description, 100) }}</p>

                        <div class="flex items-center justify-between">
                            <a href="{{ route('courses.show', $course->id) }}"
                                class="px-3 py-1 text-sm text-white bg-indigo-600 rounded hover:bg-indigo-700">
                                View Details
                            </a>
                            <span class="text-xs text-gray-600"> {{ $course->start_date ?? 'No date' }}</span>
                        </div>
                    </div>
                @empty
                    <p>You are not enrolled in any courses yet </p>
                @endforelse
            </div>
        </div>

        <!-- Upcoming Activities -->
        <div>
            <h2 class="mb-4 text-2xl font-bold">Upcoming Activities</h2>
            <ul class="space-y-3">
                @php
                    $now = \Carbon\Carbon::now('Asia/Karachi'); // Current time in Asia/Karachi
                @endphp

                @foreach ($courses as $course)
                    @php
                        // Filter only upcoming events (start <= now <= end or no end date)
                        $upcomingEvents = $course->events->filter(function ($event) use ($now) {
                            $start = \Carbon\Carbon::parse($event->start, 'Asia/Karachi');
                            $end = $event->end_date ? \Carbon\Carbon::parse($event->end_date, 'Asia/Karachi') : null;

                            // Include event if it is ongoing or has not started yet
                            if ($end) {
                                return $now <= $end; // before end date
                            }

                            return true; // no end date, include it
                        });

                    @endphp

                    {{-- Skip course if no upcoming events --}}
                    @if ($upcomingEvents->isEmpty())
                        @continue
                    @endif

                    <h3 class="mb-2 text-xl font-semibold">{{ $course->title }}</h3>

                    @foreach ($upcomingEvents as $event)
                        @php
                            $start = \Carbon\Carbon::parse($event->start, 'Asia/Karachi');
                            $end = $event->end_date ? \Carbon\Carbon::parse($event->end_date, 'Asia/Karachi') : null;

                            // Determine if quiz is open
                            $isOpen =
                                $event->type === 'quiz'
                                    ? $start <= $now && (!$end || $now <= $end) // only "Available Now" if it's ongoing
        : true;

// Check if student already attempted the quiz
$attempted =
    $event->type === 'quiz' && $event->quiz
        ? \App\Models\QuizAttempt::where('quiz_id', $event->quiz->id)
            ->where('student_id', Auth::id())
            ->exists()
        : false;

$status = $event->type === 'quiz' ? ($isOpen ? 'Available Now' : 'Closed') : '';
                        @endphp

                        <li class="flex items-center justify-between p-4 mb-2 bg-gray-100 rounded-lg shadow-sm">
                            <span>
                                @if ($event->type == 'assignment')
                                    📝
                                @elseif ($event->type == 'quiz')
                                    📊
                                @elseif ($event->type == 'live')
                                    💻
                                @endif
                                {{ $event->title }}

                                @if ($event->type == 'quiz')
                                    <small class="ml-2 text-sm text-gray-500">
                                        @if ($status == 'Available Now')
                                            - Available Now
                                        @else
                                            - Opens {{ $start->format('M d, H:i') }}
                                            @if ($end)
                                                - Ends {{ $end->format('M d, H:i') }}
                                            @endif
                                        @endif
                                    </small>
                                @endif
                            </span>

                            <span>
                                @if ($event->type == 'assignment')
                                    <a href="{{ route('student.assignment.view', $event->id) }}"
                                        class="text-sm text-indigo-600">View</a>
                                @elseif($event->type == 'quiz')
                                    @if (!$event->quiz)
                                        <span class="text-gray-500">No Question Added Yet!</span>
                                    @elseif($attempted)
                                        <span class="text-gray-500">Already Attempted</span>
                                    @elseif(!$isOpen)
                                        <span class="text-gray-500">Quiz Closed</span>
                                    @else
                                        <a href="{{ route('student.quiz.attempt', $event->id) }}"
                                            class="text-indigo-600">Attempt Quiz</a>
                                    @endif
                                @elseif($event->type == 'live_session' && $event->meeting_link)
                                    <a href="{{ $event->meeting_link }}" target="_blank"
                                        class="text-sm text-indigo-600">Join</a>
                                @endif
                            </span>
                        </li>
                    @endforeach
                @endforeach
            </ul>

        </div>


        <!-- Strengths & Weaknesses -->
        {{-- <div>
            <h2 class="mb-4 text-2xl font-bold">Your Learning Insights</h2>
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <div class="p-6 bg-green-100 rounded-lg shadow-md">
                    <h3 class="text-lg font-semibold">💪 Strengths</h3>
                    <ul class="pl-5 mt-2 text-gray-700 list-disc">
                        <li>Good at problem-solving</li>
                        <li>Strong quiz performance</li>
                        <li>Active participation in discussions</li>
                    </ul>
                </div>
                <div class="p-6 bg-red-100 rounded-lg shadow-md">
                    <h3 class="text-lg font-semibold">⚡ Weaknesses</h3>
                    <ul class="pl-5 mt-2 text-gray-700 list-disc">
                        <li>Needs improvement in assignments</li>
                        <li>Low consistency in practice</li>
                    </ul>
                </div>
            </div>
        </div> --}}

    </div>
@endsection
