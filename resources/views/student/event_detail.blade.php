@extends('dashboards.student')


@section('content')
    <div class="p-6">

        <h2 class="mb-3 text-xl font-bold">{{ $event->title }}</h2>

        <p class="mb-4 text-gray-600">{{ $event->description }}</p>

        <p><strong>Type:</strong> {{ ucfirst($event->type) }}</p>

        <p><strong>Start:</strong> {{ $event->start }}</p>

        @if ($event->end_date)
            <p><strong>End:</strong> {{ $event->end_date }}</p>
        @endif

        <hr class="my-4">

        {{-- Assignment --}}
        @if ($event->type === 'assignment')
            <a href="{{ route('student.assignment.result', $event->id) }}" class="px-4 py-2 text-white bg-indigo-600 rounded">
                View Assignment Result
            </a>
        @endif

        {{-- Quiz --}}
        @if ($event->type === 'quiz')
            <a href="{{ route('student.quiz.result', $event->id) }}" class="px-4 py-2 text-white bg-indigo-600 rounded">
                View Quiz Result
            </a>
        @endif

    </div>
@endsection
