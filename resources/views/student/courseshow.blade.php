@extends('dashboards.student')

@section('content')
    <style>
        /* Custom Styling */
        .course-card {
            border-radius: 16px;
            overflow: hidden;
            background: #ffffff;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
        }

        .course-header {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: #fff;
            padding: 30px;
        }

        .course-header h2 {
            font-weight: 700;
            font-size: 1.8rem;
        }

        .course-header p {
            opacity: 0.9;
            font-size: 1rem;
        }

        .course-actions button {
            border-radius: 8px;
            font-weight: 600;
            padding: 10px;
        }

        .details-box {
            background: #f8f9fb;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 15px;
            transition: 0.3s;
        }

        .details-box:hover {
            background: #eef1f8;
            transform: translateY(-2px);
        }

        .details-box strong {
            color: #374151;
        }

        .badge {
            font-size: 0.85rem;
            padding: 6px 12px;
            border-radius: 6px;
        }

        .back-btn {
            border-radius: 8px;
            font-weight: 500;
        }
    </style>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">

                <!-- Course Card -->
                <div class="course-card shadow">
                    <div class="row g-0">

                        <!-- Left Section (Header) -->
                        <div class="col-md-6 course-header d-flex flex-column justify-content-center">
                            <h2>{{ $course->title }}</h2>
                            <p>{{ $course->description }}</p>

                            <div class="course-actions mt-4">
                                @if (auth()->user()->coursesEnrolled->contains($course->id))
                                    <form action="{{ route('courses.unenroll', $course->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger w-100">❌ Unenroll</button>
                                    </form>
                                @else
                                    <form action="{{ route('courses.enroll', $course->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-light text-primary w-100">🚀 Enroll
                                            Now</button>
                                    </form>
                                @endif
                            </div>
                        </div>

                        <!-- Right Section (Details) -->
                        <div class="col-md-6 p-4">
                            <h5 class="fw-bold mb-3 text-dark">📌 Course Details</h5>

                            <div class="details-box">
                                <strong>👨‍🏫 Instructor(s):</strong><br>
                                @forelse($course->instructors as $instructor)
                                    <span class="badge bg-primary">{{ $instructor->name }}</span>
                                @empty
                                    <span class="text-muted">No instructor assigned</span>
                                @endforelse
                            </div>

                            <div class="details-box">
                                <strong>📅 Start Date:</strong><br>
                                <span>{{ $course->start_date ?? 'Not defined' }}</span>
                            </div>

                            <div class="details-box">
                                <strong>⏳ Duration:</strong><br>
                                <span>{{ $course->duration ?? 'Not specified' }} Days</span>
                            </div>

                            <div class="details-box">
                                <strong>📖 Status:</strong><br>
                                <span class="badge bg-success">Active</span>
                            </div>

                            <div class="d-flex justify-content-end mt-4">
                                <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary back-btn">⬅ Back</a>
                            </div>
                        </div>

                    </div>
                </div>
                <!-- End Course Card -->

            </div>
        </div>
    </div>
@endsection
