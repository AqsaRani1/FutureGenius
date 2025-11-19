@extends('dashboards.student')

@section('content')
    <div class="max-w-3xl p-6 mx-auto mt-10 text-center bg-white rounded-lg shadow">
        <h2 class="mb-3 text-2xl font-bold">{{ $quiz->title }}</h2>
        <p class="mb-6 text-gray-600">Your Score</p>
        <p class="text-5xl font-extrabold text-indigo-600">{{ $score }}%</p>
    </div>
@endsection
