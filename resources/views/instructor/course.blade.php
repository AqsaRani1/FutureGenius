@extends('dashboards.instructor')

@section('content')
    <div class="container mt-5">
        <h2 class="mb-4">{{ $course->title }} - Management</h2>

        <div class="row">
            <!-- Left: Content -->
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">📘 Course Contents</div>
                    <div class="card-body">
                        @foreach ($course->contents as $content)
                            <div class="mb-3 p-2 border rounded bg-light">
                                <strong>{{ $content->title }}</strong>
                                <p>{{ $content->description }}</p>
                                @if ($content->file_path)
                                    <a href="{{ asset('storage/' . $content->file_path) }}" target="_blank"
                                        class="btn btn-sm btn-secondary">View File</a>
                                @endif
                            </div>
                        @endforeach
                        <form method="POST" action="{{ route('instructor.content.store', $course->id) }}"
                            enctype="multipart/form-data">
                            @csrf
                            <input type="text" name="title" class="form-control mb-2" placeholder="Content Title"
                                required>
                            <textarea name="description" class="form-control mb-2" placeholder="Description"></textarea>
                            <input type="file" name="file" class="form-control mb-2">
                            <button type="submit" class="btn btn-success">Add Content</button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Right: Events & Students -->
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-header bg-warning">📅 Course Events</div>
                    <div class="card-body">
                        @foreach ($course->events as $event)
                            <div class="mb-3 p-2 border rounded bg-light">
                                <strong>{{ $event->title }}</strong> - <em>{{ $event->event_date }}</em>
                                <p>{{ $event->description }}</p>
                            </div>
                        @endforeach
                        <form method="POST" action="{{ route('instructor.event.store', $course->id) }}">
                            @csrf
                            <input type="text" name="title" class="form-control mb-2" placeholder="Event Title"
                                required>
                            <textarea name="description" class="form-control mb-2" placeholder="Event Description"></textarea>
                            <input type="datetime-local" name="event_date" class="form-control mb-2" required>
                            <button type="submit" class="btn btn-primary">Add Event</button>
                        </form>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header bg-info text-white">👩‍🎓 Enrolled Students</div>
                    <div class="card-body">
                        @forelse($course->students as $student)
                            <p class="mb-1">{{ $student->name }} ({{ $student->email }})</p>
                        @empty
                            <p>No students enrolled yet.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
