@extends('dashboards.instructor')

@section('content')
    <style>
        .sub-btn {
            background: blue;
            padding: 5px 10px;
            border-radius: 5px;
            color: white;
            margin-bottom: 10px;
        }

        .tab-pane {
            margin: 20px auto;
        }

        .mb-3 {
            align-items: center;
            display: flex;
            gap: 5%;
        }

        label {
            width: 20%;
        }

        input,
        textarea {
            width: 30%;
        }
    </style>
    <div class="container py-5">
        @if (session('success'))
            <div class="alert alert-success text-succes"
                style="border: 1px solid green; background: rgb(180, 255, 180);padding: 10px;">
                {{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger text-succes"
                style="border: 1px solid green; background: rgb(180, 255, 180);padding: 10px;">{{ session('error') }}</div>
        @endif

        <!-- Course Overview -->
        <div class="mb-5 border-0 shadow-lg card rounded-4">
            <div class="p-4 card-body bg-gradient-light">
                <h2 class="mb-2 fw-bold text-primary">{{ $course->title }}</h2>
                <p class="text-muted">{{ $course->description }}</p>

                <div class="flex mt-4 text-center g-4">
                    <div class="col-md-4">
                        <div class="p-4 bg-white shadow-sm rounded-4 stat-card h-100">
                            <h6 class="fw-semibold text-secondary">📅 Start Date</h6>
                            <p class="mb-0 fw-bold fs-5 text-dark">{{ $course->start_date ?? 'TBA' }}</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="p-4 bg-white shadow-sm rounded-4 stat-card h-100">
                            <h6 class="fw-semibold text-secondary">⏳ Duration</h6>
                            <p class="mb-0 fw-bold fs-5 text-dark">{{ $course->duration ?? 'N/A' }} Days</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="p-4 bg-white shadow-sm rounded-4 stat-card h-100">
                            <h6 class="fw-semibold text-secondary">👩‍🎓 Students</h6>
                            <p class="mb-0 fw-bold fs-5 text-dark">{{ $course->students->count() }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Navigation Tabs -->
        <ul class="flex gap-3 mb-5 nav nav-pills justify-content-center" id="courseTab" role="tablist">
            <li class="nav-item">
                <a class="px-4 py-2 shadow-sm nav-link active fw-semibold" id="content-tab" data-bs-toggle="tab"
                    href="#content" role="tab">
                    📘 Content
                </a>
            </li>
            <li class="nav-item">
                <a class="px-4 py-2 shadow-sm nav-link fw-semibold" id="events-tab" data-bs-toggle="tab" href="#events"
                    role="tab">
                    📅 Events
                </a>
            </li>
            <li class="nav-item">
                <a class="px-4 py-2 shadow-sm nav-link fw-semibold" id="students-tab" data-bs-toggle="tab" href="#students"
                    role="tab">
                    👨‍🎓 Students
                </a>
            </li>
        </ul>

        <!-- Tab Content -->
        <div class="tab-content" id="courseTabContent">

            <!-- Content Tab -->
            <div class="tab-pane fade show active" id="content" role="tabpanel">
                <div class="px-4 py-2 text-white section-header bg-primary rounded-top-4">
                    <h5 class="mb-0">➕ Manage Course Lessons</h5>
                </div>
                <div class="mb-4 border-0 shadow card rounded-bottom-4">
                    <div class="card-body">
                        <form method="POST" action="{{ route('instructor.content.store', $course->id) }}"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Lesson Title</label>
                                <input type="text" name="title" class="shadow-sm form-control rounded-2" required>
                                @error('title')
                                    <span class="text-danger " style="font: 12px">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Description</label>
                                <textarea name="description" class="shadow-sm form-control rounded-2"></textarea>
                                @error('description')
                                    <span class="text-danger " style="font: 12px">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Upload File</label>
                                <input type="file" name="file" class="shadow-sm form-control rounded-2">
                                @error('file')
                                    <span class="text-danger " style="font: 12px">{{ $message }}</span>
                                @enderror
                            </div>
                            <button type="submit" class="shadow-sm btn btn-success sub-btn">Add Lesson</button>
                        </form>

                    </div>
                </div>

                <h5 class="mb-3 fw-bold text-secondary">📂 Existing Lessons</h5>
                <div class="row g-3">
                    @forelse($course->contents as $content)
                        <div class="col-md-4">
                            <div class="border-0 shadow-sm card h-100 rounded-4 lesson-card">
                                <div class="card-body d-flex flex-column">
                                    <h6 class="fw-bold text-primary">{{ $content->title }}</h6>
                                    <a href="{{ asset('storage/' . $content->file) }}" target="_blank"
                                        class="mt-auto btn btn-outline-primary btn-sm">
                                        View File
                                    </a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-muted">No lessons added yet.</p>
                    @endforelse
                </div>
            </div>

            <!-- Events Tab -->
            <div class="tab-pane fade" id="events" role="tabpanel">
                <div class="px-4 py-2 text-white section-header bg-info rounded-top-4">
                    <h5 class="mb-0">📅 Manage Events</h5>
                </div>
                <div class="mb-4 border-0 shadow card rounded-bottom-4">
                    <div class="card-body">
                        <form method="POST" action="{{ route('instructor.event.store', $course->id) }}"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Event Title</label>
                                <input type="text" name="title" class="shadow-sm form-control rounded-2" required>
                                @error('title')
                                    <span class="text-danger " style="font: 12px">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Event Description</label>
                                <textarea name="description" class="shadow-sm form-control rounded-2"></textarea>
                                @error('description')
                                    <span class="text-danger " style="font: 12px">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Event Date & Time</label>
                                <input type="datetime-local" name="event_date" class="shadow-sm form-control rounded-2"
                                    required>
                                @error('event_date')
                                    <span class="text-danger " style="font: 12px">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">End Date & Time</label>
                                <input type="datetime-local" name="end_date" class="shadow-sm form-control rounded-2"
                                    required>
                                @error('end_date')
                                    <span class="text-danger " style="font: 12px">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Upload File</label>
                                <input type="file" name="file_path" class="shadow-sm form-control rounded-2">
                                @error('file')
                                    <span class="text-danger " style="font: 12px">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Meeting Link (for Live Session)</label>
                                <input type="url" name="meeting_link" class="shadow-sm form-control rounded-2"
                                    placeholder="https://...">
                                @error('meeting_link')
                                    <span class="text-danger" style="font-size: 12px">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Event Type</label>
                                <select name="type" class="shadow-sm form-control rounded-2" required>
                                    <option value="assignment">📝 Assignment</option>
                                    <option value="quiz">🧪 Quiz</option>
                                    <option value="live_session">🎥 Live Session</option>
                                    <option value="other">📌 Other</option>
                                </select>
                                @error('type')
                                    <span class="text-danger " style="font: 12px">{{ $message }}</span>
                                @enderror
                            </div>
                            <button type="submit" class="shadow-sm btn btn-primary sub-btn">Add Event</button>
                        </form>

                    </div>
                </div>

                <h5 class="mb-3 fw-bold text-secondary">📌 Upcoming Events</h5>
                <div class="row g-3">
                    @forelse($course->upcomingEvents as $event)
                        <div class="col-md-4">
                            <div class="border-0 shadow-sm card h-100 rounded-4 event-card">
                                <span class="badge bg-secondary">
                                    @if ($event->type === 'assignment')
                                        📝 Assignment
                                    @elseif($event->type === 'quiz')
                                        🧪 Quiz
                                    @elseif($event->type === 'live_session')
                                        🎥 Live Session
                                    @else
                                        📌 Other
                                    @endif
                                </span>
                            </div>
                            <div class="card-body">
                                <h6 class="fw-bold text-primary">{{ $event->title }}</h6>
                                <p class="mb-0 text-muted">📅
                                    {{ \Carbon\Carbon::parse($event->event_date)->format('d M, Y h:i A') }}</p>

                                @if ($event->type === 'quiz')
                                    <a href="{{ route('quiz.create', $event->id) }}" class="mt-2 btn btn-sm btn-success">
                                        Create Quiz
                                    </a>
                                @endif
                            </div>
                        </div>
                    @empty
                        <p class="text-muted">No upcoming events scheduled.</p>
                    @endforelse

                </div>

            </div>

            <!-- Students Tab -->
            <div class="tab-pane fade" id="students" role="tabpanel">
                <div class="px-4 py-2 text-white section-header bg-success rounded-top-4">
                    <h5 class="mb-0">👩‍🎓 Enrolled Students</h5>
                </div>
                <div class="border-0 shadow-sm card rounded-bottom-4">
                    <div class="list-group list-group-flush">
                        @forelse($course->students as $student)
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <span class="fw-semibold">{{ $student->name }}</span><br>
                                    <small class="text-muted">{{ $student->email }}</small>
                                </div>
                                <span class="badge bg-success">Student</span>
                            </div>
                        @empty
                            <p class="p-3 text-muted">No students enrolled yet.</p>
                        @endforelse
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="grading" role="tabpanel">
                <div class="px-4 py-2 text-white section-header bg-warning rounded-top-4">
                    <h5 class="mb-0">📝 Assignment & Quiz Grading</h5>
                </div>

                <div class="border-0 shadow-sm card rounded-bottom-4">
                    <div class="card-body">
                        <h5 class="mt-3 fw-bold text-secondary" style="font-weight: bold; font-size: 30px;">📌 Assignment
                            Submissions</h5>
                        @foreach ($course->events->where('type', 'assignment') as $event)
                            <h6 class="mt-3 fw-bold">{{ $event->title }}</h6>
                            <a href="{{ route('instructor.assignment.submissions', $event->id) }}" target="_blank"
                                class="btn btn-sm btn-primary" style="color: blue">View submission</a>
                        @endforeach
                    </div>
                    <hr>

                    <h5 class="mt-4 fw-bold text-secondary" style="font-weight: bold; font-size: 30px;">🧪 Quiz Attempts
                    </h5>
                    @foreach ($course->events->where('type', 'quiz') as $event)
                        <h6 class="mt-3 fw-bold">{{ $event->title }}</h6>

                        <a href="{{ route('instructor.quiz.attempts', $event->id) }}" target="_blank"
                            class="btn btn-sm btn-primary" style="color: blue">View Attempts</a>
                    @endforeach

                </div>
            </div>
        </div>

    </div>
    </div>

    <!-- Custom CSS -->
    <style>
        .col-md-4 {
            width: 33.33%
        }

        .stat-card {
            transition: transform 0.2s ease-in-out, box-shadow 0.3s;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .lesson-card,
        .event-card {
            transition: all 0.3s ease-in-out;
        }

        .lesson-card:hover,
        .event-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.12);
        }

        .nav-pills .nav-link {
            border-radius: 30px;
            background: #f8f9fa;
            color: #495057;
            transition: all 0.3s;
        }

        .nav-pills .nav-link.active {
            background: #0d6efd;
            color: #fff;
        }

        .nav-pills .nav-link:hover {
            background: #e9ecef;
        }

        .section-header {
            border-radius: 12px 12px 0 0;
            background: linear-gradient(135deg, #ffbb56, #faec70);
            color: black;
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.2);
            margin-bottom: 10px;
        }
    </style>
@endsection
