{{-- @extends('dashboards.instructor')
@section('content')
    <h3>Add Questions to Quiz: {{ $quiz->title }}</h3>

    <form method="POST" action="{{ route('quiz.questions.store', $quiz->id) }}">
        @csrf
        <div id="questions-container">
            <div class="question-block">
                <label>Question:</label>
                <input type="text" name="questions[0][question]" required>

                <label>Option 1:</label>
                <input type="text" name="questions[0][options][0][text]" required>
                <input type="hidden" name="questions[0][options][0][is_correct]" value="0">
                <label>Correct?</label>
                <input type="checkbox" name="questions[0][options][0][is_correct]" value="1">

                <label>Option 2:</label>
                <input type="text" name="questions[0][options][1][text]" required>
                <input type="hidden" name="questions[0][options][1][is_correct]" value="0">
                <label>Correct?</label>
                <input type="checkbox" name="questions[0][options][1][is_correct]" value="1">
            </div>
        </div>

        <button type="submit">Save Questions</button>
    </form>
@endsection --}}
@extends('dashboards.instructor')
@section('content')
    <h3>Add Question to Quiz: {{ $quiz->title }}</h3>

    @if (session('success'))
        <div style="color:green">{{ session('success') }}</div>
    @endif

    <form method="POST" action="{{ route('quiz.questionsstore', $quiz->id) }}">
        @csrf
        <label>Question:</label>
        <input type="text" name="question" required>

        <h5>Options:</h5>
        @for ($i = 0; $i < 4; $i++)
            <label>Option {{ $i + 1 }}:</label>
            <input type="text" name="options[{{ $i }}][text]" required>
            <input type="hidden" name="options[{{ $i }}][is_correct]" value="0">
            <label>Correct?</label>
            <input type="checkbox" name="options[{{ $i }}][is_correct]" value="1"><br>
        @endfor

        <button type="submit">Save Question & Add Another</button>
    </form>

    <a href="{{ route('instructor.course', $quiz->event->course->id) }}">Finish Adding Questions</a>
@endsection
