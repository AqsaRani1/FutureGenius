@extends('dashboards.instructor')
@section('content')
    <div class="container mx-auto px-4 mt-10">
        <h2 class="text-2xl font-bold text-indigo-700 mb-6 flex items-center">
            📘 My Assigned Courses
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse(auth()->user()->coursesTaught as $course)
                <div class="bg-white rounded-2xl shadow-lg hover:shadow-2xl transition p-5 flex flex-col">
                    <!-- SVG Thumbnail -->
                    <div class="relative h-40 w-full rounded-lg overflow-hidden flex items-center justify-center"
                        style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">

                        <svg xmlns="http://www.w3.org/2000/svg" class="w-16 h-16 text-white opacity-90" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            @php
                                $icons = [
                                    '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6l4 2"/>', // clock
                                    '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M12 5v14"/>', // plus
                                    '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2h6v2m2 4H7a2 2 0 01-2-2V7a2 2 0 012-2h4l2-2h4a2 2 0 012 2v12a2 2 0 01-2 2z"/>', // book
                                    '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7h18M3 12h18M3 17h18"/>', // bars
                                    '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"/>', // play
                                ];
                                $icon = $icons[array_rand($icons)];
                            @endphp
                            {!! $icon !!}
                        </svg>

                        <span class="absolute top-2 left-2 bg-indigo-600 text-white text-xs px-3 py-1 rounded-full shadow">
                            Instructor
                        </span>
                    </div>

                    <!-- Course Content -->
                    <div class="flex-1 mt-4">
                        <h3 class="text-lg font-bold text-gray-800">{{ $course->title }}</h3>
                        <p class="text-gray-600 text-sm mt-2">
                            {{ Str::limit($course->description, 90) }}
                        </p>

                        <div class="mt-4 space-y-2 text-sm text-gray-700">
                            <p>📅 <strong>Start:</strong> {{ $course->start_date ?? 'TBA' }}</p>
                            <p>⏳ <strong>Duration:</strong> {{ $course->duration ?? 'N/A' }} Days</p>
                            <p>👨‍🎓 <strong>Enrolled:</strong> {{ $course->students->count() ?? 0 }} students</p>
                        </div>
                    </div>

                    <!-- Action -->
                    <div class="mt-6">
                        <a href="{{ route('instructor.course', $course->id) }}"
                            class="block w-full text-center bg-indigo-600 text-white py-2 rounded-lg font-medium hover:bg-indigo-700 transition">
                            Manage Course
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-3">
                    <div class="bg-yellow-100 text-yellow-800 p-5 rounded-xl text-center">
                        📭 You don’t have any assigned courses yet
                    </div>
                </div>
            @endforelse
        </div>
    </div>
@endsection
