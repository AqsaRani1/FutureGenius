@extends('dashboards.instructor')
@section('content')
    <div style="width: 60%;
    margin: 0 auto;
    background: white;
    margin-top: 20px;
    padding: 1.5rem">
        <h2>{{ $event->title }} - Quiz Attempts</h2>

        <table class="min-w-full divide-y divide-gray-200">
            <thead>
                <tr>
                    <th class="px-4 py-2 text-left">Student</th>
                    <th class="px-4 py-2 text-left">Score</th>
                    <th class="px-4 py-2 text-left">Attempted At</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach ($attempts as $a)
                    @foreach ($a->attempts as $ap)
                        {{-- @dd($ap) --}}
                        <tr>
                            <td class="px-4 py-2">{{ $ap->student->name }}</td>
                            <td class="px-4 py-2">{{ $ap->score ?? '-' }}</td>
                            <td class="px-4 py-2">{{ $ap->submitted_at ?? $ap->created_at }}</td>
                        </tr>
                    @endforeach
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
