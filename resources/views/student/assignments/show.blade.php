@extends('dashboards.student')

@section('content')

    @php
        $now = \Carbon\Carbon::now('Asia/Karachi'); // Current time in Asia/Karachi
        $due = $assignment->end_date ? \Carbon\Carbon::parse($assignment->end_date) : null;
        $isPastDue = $due ? $now->greaterThan($due) : false;

        $submission = $assignment->submissions->where('student_id', Auth::id())->first();
    @endphp
    @if (session('success'))
        <div class="alert alert-success text-succes"
            style="border: 1px solid green; background: rgb(180, 255, 180);padding: 10px;">
            {{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger text-succes"
            style="border: 1px solid green; background: rgb(180, 255, 180);padding: 10px;">{{ session('error') }}</div>
    @endif
    <div class="max-w-3xl p-6 mx-auto mt-6 bg-white rounded-lg shadow">

        <h2 class="mb-3 text-2xl font-bold">{{ $assignment->title }}</h2>

        <p class="mb-4 text-gray-600">{{ $assignment->description }}</p>

        @if ($assignment->file)
            <a href="{{ asset('storage/' . $assignment->file) }}" class="text-indigo-600 underline">
                Download Assignment File
            </a>
        @endif

        <p class="mt-4 text-sm text-gray-500">
            Due: {{ $assignment->end_date ? $due->format('M d, Y H:i') : 'No due date' }}
        </p>

        <hr class="my-4">

        {{-- If Submitted --}}
        @if ($submission)
            <h3 class="mb-2 text-lg font-semibold">Your Submission</h3>

            {{-- Submitted File --}}
            @if ($submission->file)
                <a href="{{ asset('storage/' . $submission->file) }}" target="_blank" class="text-indigo-600 underline">
                    View Submitted File
                </a>
            @endif

            {{-- Submitted Text --}}
            @if ($submission->answer_text)
                <p class="mt-2 text-gray-700">{{ $submission->answer_text }}</p>
            @endif

            <hr class="my-4">

            {{-- Grade --}}
            @if ($submission->grade !== null)
                <p class="font-semibold text-green-600">Grade: {{ $submission->grade }}/100</p>
                <p class="mt-1 text-gray-700">Feedback: {{ $submission->feedback }}</p>
            @else
                <p class="italic text-gray-500">Not graded yet</p>
            @endif

            {{-- Allow update only before due date --}}
            @if (!$isPastDue)
                <hr class="my-4">

                <h3 class="mb-2 text-lg font-semibold">Update Submission</h3>

                <form method="POST" action="{{ route('student.assignment.submit', $assignment->id) }}"
                    enctype="multipart/form-data">

                    @csrf

                    <div class="mb-4">
                        <label class="block mb-1 font-semibold">Replace File</label>
                        <input type="file" name="file" class="w-full border-gray-300 rounded-lg">
                    </div>

                    <div class="mb-4">
                        <label class="block mb-1 font-semibold">Or Update Written Answer</label>
                        <textarea name="answer_text" rows="4" class="w-full border-gray-300 rounded-lg"></textarea>
                    </div>

                    <button class="px-4 py-2 text-white bg-indigo-600 rounded hover:bg-indigo-700">
                        Update Submission
                    </button>
                </form>

                {{-- Delete Submission --}}
                <form action="{{ route('student.assignment.delete', $assignment->id) }}" method="POST" class="mt-4">
                    @csrf
                    <button class="px-4 py-2 text-white bg-red-600 rounded hover:bg-red-700">
                        Delete Submission
                    </button>
                </form>
            @else
                <p class="mt-3 text-sm font-semibold text-red-600">
                    Submission is closed. You cannot edit after due date.
                </p>
            @endif
        @else
            {{-- Not submitted --}}
            @if ($isPastDue)
                <p class="font-semibold text-red-600">Submission closed — You missed the deadline.</p>
            @else
                <form method="POST" action="{{ route('student.assignment.submit', $assignment->id) }}"
                    enctype="multipart/form-data">

                    @csrf

                    <div class="mb-4">
                        <label class="block mb-1 font-semibold">Upload File</label>
                        <input type="file" name="file" class="w-full border-gray-300 rounded-lg">
                    </div>

                    <div class="mb-4">
                        <label class="block mb-1 font-semibold">Or Write Your Answer</label>
                        <textarea name="answer_text" rows="4" class="w-full border-gray-300 rounded-lg"></textarea>
                    </div>

                    <button class="px-4 py-2 text-white bg-indigo-600 rounded hover:bg-indigo-700">
                        Submit Assignment
                    </button>
                </form>
            @endif
        @endif

    </div>

@endsection
