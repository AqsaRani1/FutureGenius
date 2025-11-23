@extends('dashboards.student')


@section('content')
<div class="p-6">

    <h2 class="mb-3 text-xl font-bold">Assignment Result</h2>

    <p><strong>Assignment:</strong> {{ $event->title }}</p>

    <p><strong>Submitted At:</strong> {{ $submission->created_at }}</p>

    <p>
        <strong>File:</strong>
        @if ($submission->file)
        <a href="{{ asset('storage/' . $submission->file) }}" target="_blank" class="text-indigo-600 underline">View
            File</a>
        @else
        <span>No file uploaded</span>
        @endif
    </p>

    <p><strong>Grade:</strong> {{ $submission->grade ?? 'Not graded yet' }}</p>

    <p><strong>Feedback:</strong> {{ $submission->feedback ?? 'No feedback yet' }}</p>

</div>
@endsection