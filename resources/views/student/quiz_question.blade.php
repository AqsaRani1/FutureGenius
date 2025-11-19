@extends('dashboards.student')

@section('content')
    <div class="relative max-w-3xl p-6 mx-auto mt-6 bg-white rounded-lg shadow">
        <div class="flex justify-end mb-4">
            <div id="timerBox" class="px-4 py-2 text-white bg-red-600 rounded">
                Time Left: <span id="timer"></span>
            </div>
        </div>

        <h2 class="mb-4 text-xl font-bold">Question {{ $number }}</h2>
        <p class="mb-6 text-gray-700">{{ $question->question }}</p>

        <form method="POST" action="{{ route('student.quiz.answer', ['event' => $event->id, 'number' => $number]) }}"
            id="quizForm">
            @csrf
            @foreach ($question->options as $opt)
                <label class="block p-3 mb-3 border rounded-lg cursor-pointer hover:bg-gray-100">
                    <input type="radio" name="option_id" value="{{ $opt->id }}" class="mr-2">
                    {{ $opt->option_text }}
                </label>
            @endforeach

            <button type="submit" class="px-4 py-2 mt-4 text-white bg-indigo-600 rounded">
                @if ($number == $quiz->questions->count())
                    Finish Quiz
                @else
                    Next
                @endif
            </button>
        </form>
    </div>

    <script>
        // Timer logic
        let remaining = {{ $remaining }};
        const timerEl = document.getElementById('timer');

        function updateTimerDisplay() {
            let mins = Math.floor(remaining / 60);
            let secs = remaining % 60;
            timerEl.innerHTML = mins + ":" + (secs < 10 ? "0" + secs : secs);
        }

        updateTimerDisplay();

        const interval = setInterval(() => {
            if (remaining <= 0) {
                clearInterval(interval);

                // Auto-submit unanswered question
                const form = document.getElementById('quizForm');

                // Ensure option_id is null if no answer selected
                if (![...form.elements['option_id']].some(r => r.checked)) {
                    let input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'option_id';
                    input.value = '';
                    form.appendChild(input);
                }

                form.submit();
                return;
            }

            remaining--;
            updateTimerDisplay();
        }, 1000);
    </script>
@endsection
