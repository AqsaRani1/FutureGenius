@extends('dashboards.instructor')
@section('content')
    <h2>Grade Assignment - {{ $submission->student->name }}</h2>

    <p><strong>Event:</strong> {{ $submission->event->title }}</p>
    <p><strong>Submitted At:</strong> {{ $submission->created_at }}</p>

    @if ($submission->file)
        <p><a href="{{ asset($submission->file) }}" target="_blank">Download Submission</a></p>
    @endif

    <form method="POST" action="{{ route('instructor.assignment.grade.store', $submission->id) }}">
        @csrf

        <label>Grade (0-100)</label>
        <input type="number" name="grade" value="{{ $submission->grade }}" required>

        <label>Feedback</label>
        <textarea name="feedback">{{ $submission->feedback }}</textarea>

        <button type="submit">Save</button>
    </form>
@endsection
