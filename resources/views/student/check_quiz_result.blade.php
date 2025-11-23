@extends('dashboards.student')

@section('content')
    <div class="p-6">

        <h2 class="mb-3 text-xl font-bold">Quiz Result</h2>

        <p><strong>Quiz:</strong> {{ $event->title }}</p>

        <p><strong>Score:</strong> {{ $attempt->score }} / {{ $attempt->quiz->questions->count() }}</p>

        <p><strong>Attempted At:</strong> {{ $attempt->created_at }}</p>

    </div>
@endsection
