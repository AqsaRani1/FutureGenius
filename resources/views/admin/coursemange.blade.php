@extends('dashboards.admin')

@section('content')
    <div class="flex justify-between items-center m-5">
        <h2 class="text-2xl font-bold">Manage Courses</h2>
        <button onclick="openAddCourseModal()" class="bg-indigo-600 text-white px-4 py-2 rounded">+ Add Course</button>
    </div>

    @if (session('success'))
        <div class="bg-green-200 text-green-800 p-2 rounded mb-4">{{ session('success') }}</div>
    @endif

    @if (session('error'))
        <div class="bg-red-200 text-red-800 p-2 rounded mb-4">{{ session('error') }}</div>
    @endif

    <table class="bg-white rounded shadow m-5 w-full">
        <thead>
            <tr class="bg-gray-100 text-left">
                <th class="py-2 px-4">Title</th>
                <th class="py-2 px-4">Description</th>
                <th class="py-2 px-4">Instructors</th>
                <th class="py-2 px-4">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($courses as $course)
                <tr class="border-b">
                    <td class="py-2 px-4">{{ $course->title }}</td>
                    <td class="py-2 px-4">{{ $course->description }}</td>
                    <td class="py-2 px-4">
                        @forelse ($course->instructors as $instructor)
                            <span class="inline-block bg-indigo-100 text-indigo-800 px-2 py-1 rounded text-sm">
                                {{ $instructor->name }} <form
                                    action="{{ route('courses.removeInstructor', [$course->id, $instructor->id]) }}"
                                    method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:underline">Remove</button>
                                </form>
                            </span>
                        @empty
                            <span class="text-gray-500">No instructor assigned</span>
                        @endforelse
                    </td>
                    <td class="py-2 px-4 space-x-2" style="font-size: 14px; display:flex;">
                        <!-- Assign Instructor -->
                        <button onclick="openAssignInstructorModal({{ $course->id }})"
                            class="bg-yellow-500 text-white px-2 py-1 rounded">
                            Assign Instructor
                        </button>

                        <!-- View Students -->
                        <a href="{{ route('courses.students', $course->id) }}"
                            class="bg-green-600 text-white px-2 py-1 rounded">
                            View Students
                        </a>
                        <form action="{{ route('courses.destroy', $course->id) }}" method="POST"
                            onsubmit="return confirm('Are you sure you want to delete this course?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-500 text-white px-1 py-1 rounded">Delete</button>
                        </form>


                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Add Course Modal -->
    <div id="courseModal" class="hidden fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
        <div class="bg-white p-6 rounded-lg w-1/3">
            <h3 class="text-xl font-semibold mb-4">Add New Course</h3>

            <form action="{{ route('admin.course.store') }}" method="POST">
                @csrf

                <div class="mb-4">
                    <label class="block mb-1">Title</label>
                    <input type="text" name="title" class="w-full border rounded p-2" required>
                </div>

                <div class="mb-4">
                    <label class="block mb-1">Description</label>
                    <textarea name="description" class="w-full border rounded p-2"></textarea>
                </div>

                <div class="flex justify-end space-x-2">
                    <button type="button" onclick="document.getElementById('courseModal').classList.add('hidden')"
                        class="px-4 py-2 bg-gray-300 rounded">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded">Save</button>
                </div>
                <div class="mb-4">
                    <label class="form-label">Start Date</label>
                    <input type="date" name="start_date" class="form-control" required>
                </div>

                <div class="mb-4">
                    <label class="form-label">Duration (days)</label>
                    <input type="number" name="duration" class="form-control" min="1" required>
                </div>

            </form>
        </div>
    </div>

    <!-- Assign Instructor Modal -->
    <div id="assignInstructorModal" class="hidden fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
        <div class="bg-white p-6 rounded-lg w-1/3">
            <h3 class="text-xl font-semibold mb-4">Assign Instructor</h3>

            <form id="assignInstructorForm" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block mb-1">Select Instructor</label>
                    <select name="instructor_id" class="w-full border rounded p-2" required>
                        @foreach (\App\Models\User::where('role', 'instructor')->get() as $instructor)
                            <option value="{{ $instructor->id }}">{{ $instructor->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="flex justify-end space-x-2">
                    <button type="button"
                        onclick="document.getElementById('assignInstructorModal').classList.add('hidden')"
                        class="px-4 py-2 bg-gray-300 rounded">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded">Assign</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openAddCourseModal() {
            document.getElementById('courseModal').classList.remove('hidden');
        }

        function openAssignInstructorModal(courseId) {
            let form = document.getElementById('assignInstructorForm');
            form.action = "/admin/courses/" + courseId + "/assign-instructor";
            document.getElementById('assignInstructorModal').classList.remove('hidden');
        }
    </script>
@endsection
