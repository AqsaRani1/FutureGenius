@extends('dashboards.student')

@section('content')
    <div
        style="    width: 50%;
    margin: 0 auto;
    text-align: center;
    background: white;
    border-radius: 10px;
    margin-top: 10px;
}">
        <div style="font-size:22px; font-weight:bold; color:red;">
            Time left: <span id="timer"></span>
        </div>

        <div style="font-size:18px; margin:10px 0;">
            Question {{ $number }} / {{ $total }}
        </div>

        {{-- ANSWER FORM --}}
        <form id="answerForm" action="{{ route('student.quiz.answer', [$event->id, $number]) }}" method="POST">
            @csrf

            <div>
                <strong>{{ $question->question }}</strong>
            </div>

            @foreach ($question->options as $option)
                <label style="display:block; margin-top:8px;">
                    <input type="radio" name="option_id" value="{{ $option->id }}">
                    {{ $option->option_text }}
                </label>
            @endforeach

            @if ($number < $total)
                {{-- Only show NEXT button if NOT last question --}}
                <button type="submit"
                    style="
                     margin-top: 20px; background:
                    rgb(67 56 202); color: white; padding: 2px 15px; border-radius: 5px; margin-bottom: 20px; ">Next</button>
            @endif
        </form>

        {{-- FINISH FORM (Only visible on last question) --}}
        @if ($number == $total)
            <form id="finishForm" action="{{ route('student.quiz.finish', $event->id) }}" method="POST">
                @csrf

                <button type="submit" style="margin-top:20px; background:red; color:white;">
                    Finish Quiz
                </button>
            </form>
        @endif

        {{-- AUTO SUBMIT ON TIME UP --}}
        <form id="autoSubmit" action="{{ route('student.quiz.finish', $event->id) }}" method="POST">
            @csrf
        </form>
    </div>
    <script>
        let end = {{ $quizEndTimestamp }};

        function updateTimer() {
            let now = Math.floor(Date.now() / 1000);
            let left = end - now;

            if (left <= 0) {
                document.getElementById("autoSubmit").submit();
                return;
            }

            let m = Math.floor(left / 60);
            let s = left % 60;

            document.getElementById("timer").innerText =
                m + ":" + (s < 10 ? "0" + s : s);
        }

        updateTimer();
        setInterval(updateTimer, 1000);
    </script>
@endsection
