@extends('dashboards.instructor')
@section('content')
    <h3>Create Quiz for Event: {{ $event->title }}</h3>
    <form method="POST" action="{{ route('quiz.store', $event->id) }}">
        @csrf
        <label>Quiz Title</label>
        <input type="text" name="title" required>
        <button type="submit">Create Quiz</button>
    </form>
@endsection
