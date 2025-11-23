@extends('dashboards.student')

@section('content')
    <div class="max-w-2xl p-6 mx-auto bg-white rounded shadow">

        <h2 class="mb-4 text-2xl font-bold">{{ $event->title }}</h2>
        <p class="mb-3">{{ $event->description }}</p>

        <p><strong>Starts:</strong> {{ $event->event_date->format('d M Y h:i A') }}</p>
        <p><strong>Ends:</strong> {{ $event->end_date->format('d M Y h:i A') }}</p>

        <hr class="my-4">

        {{-- STATUS HANDLING --}}
        @if ($status == 'upcoming')
            <h3 class="mb-2 text-xl font-bold text-blue-600">Meeting will start soon</h3>
            <p class="mb-3">Please wait for the scheduled time.</p>

            <div id="countdown" class="text-lg font-bold text-red-600"></div>

            <script>
                let start = {{ $event->event_date->timestamp }};

                function tick() {
                    let now = Math.floor(Date.now() / 1000);
                    let left = start - now;

                    if (left <= 0) {
                        location.reload();
                        return;
                    }

                    let h = Math.floor(left / 3600);
                    let m = Math.floor((left % 3600) / 60);
                    let s = left % 60;

                    document.getElementById("countdown").innerText =
                        h + "h " + m + "m " + s + "s remaining";
                }

                tick();
                setInterval(tick, 1000);
            </script>
        @elseif ($status == 'live')
            <h3 class="mb-3 text-xl font-bold text-green-600">The session is LIVE now!</h3>

            <a href="{{ $event->meeting_link }}" target="_blank" class="px-6 py-3 text-white bg-blue-600 rounded shadow">
                Join Meeting
            </a>
        @elseif ($status == 'ended')
            <h3 class="text-xl font-bold text-gray-600">This meeting has ended.</h3>
        @endif
    </div>
@endsection
