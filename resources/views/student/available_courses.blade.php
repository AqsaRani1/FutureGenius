@extends('dashboards.student')

@section('content')
    <div class="container mx-auto mt-8">
        <h2 class="text-2xl font-bold mb-6">Available Courses</h2>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @forelse($courses as $course)
                @php
                    $colors = [
                        'bg-gradient-to-r from-pink-300 to-pink-500',
                        'bg-gradient-to-r from-blue-300 to-blue-500',
                        'bg-gradient-to-r from-green-300 to-green-500',
                        'bg-gradient-to-r from-yellow-300 to-yellow-500',
                        'bg-gradient-to-r from-purple-300 to-purple-500',
                        'bg-gradient-to-r from-red-300 to-red-500',
                    ];
                    $randomColor = $colors[array_rand($colors)];
                @endphp

                <div class="p-6 rounded-xl shadow-lg text-white {{ $randomColor }}">
                    <h3 class="text-lg font-bold mb-2">{{ $course->title }}</h3>
                    <p class="mb-4">{{ $course->description }}</p>

                    <form action="{{ route('courses.enroll', $course->id) }}" method="POST">
                        @csrf
                        <button type="submit"
                            class="bg-white text-blue-600 font-semibold px-4 py-2 rounded-lg hover:bg-blue-100 transition">
                            ✅ Enroll
                        </button>
                    </form>
                </div>
            @empty
                <p class="text-gray-600 col-span-3">No courses available right now 🎉</p>
            @endforelse
        </div>
    </div>
@endsection
