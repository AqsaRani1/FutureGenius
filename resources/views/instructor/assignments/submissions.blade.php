@extends('dashboards.instructor')

@section('content')
    <div class="max-w-4xl mx-auto mt-6">
        <h2 class="mb-4 text-2xl font-bold">Submissions for: {{ $event->title }}</h2>

        <div class="p-4 bg-white rounded-lg shadow">
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr>
                        <th class="px-4 py-2 text-left">Student</th>
                        <th class="px-4 py-2 text-left">Submitted File</th>
                        <th class="px-4 py-2 text-left">Text Answer</th>
                        <th class="px-4 py-2 text-left">Grade</th>
                        <th class="px-4 py-2 text-left">Action</th>
                    </tr>
                </thead>
                {{-- @dd($submission) --}}
                <tbody class="divide-y divide-gray-100">
                    @foreach ($submission as $sub)
                        <tr>
                            <td class="px-4 py-2">{{ $sub->student->name }}</td>

                            <td class="px-4 py-2">
                                @if ($sub->file)
                                    <a href="{{ asset('storage/' . $sub->file) }}" target="_blank"
                                        class="text-indigo-600 underline">View File</a>
                                @else
                                    <span class="text-gray-500">No file</span>
                                @endif
                            </td>

                            <td class="px-4 py-2">
                                {{ $sub->answer_text ? Str::limit($sub->answer_text, 40) : '—' }}
                            </td>

                            <td class="px-4 py-2">
                                @if ($sub->grade !== null)
                                    <span class="font-semibold text-green-600">{{ $sub->grade }}/100</span>
                                @else
                                    <span class="text-gray-400">Not graded</span>
                                @endif
                            </td>

                            <td class="px-4 py-2">
                                <!-- Grade Button -->
                                <button
                                    onclick="openGradeModal({{ $sub->id }}, '{{ $sub->grade }}', `{{ $sub->feedback }}`)"
                                    class="px-3 py-1 text-white bg-indigo-600 rounded hover:bg-indigo-700">
                                    Grade
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>

            </table>
        </div>
    </div>

    <!-- GRADE MODAL -->
    <div id="gradeModal" class="fixed inset-0 flex items-center justify-center hidden bg-gray-900 bg-opacity-50">
        <div class="w-full max-w-lg p-6 bg-white rounded-lg shadow">

            <h3 class="mb-4 text-xl font-bold">Grade Submission</h3>

            <form id="gradeForm" method="POST">
                @csrf

                <div class="mb-4">
                    <label class="block mb-1 font-semibold text-gray-700">Grade (0 - 100):</label>
                    <input type="number" name="grade" id="gradeInput" class="w-full border-gray-300 rounded-lg"
                        min="0" max="100" required>
                </div>

                <div class="mb-4">
                    <label class="block mb-1 font-semibold text-gray-700">Feedback:</label>
                    <textarea name="feedback" id="feedbackInput" class="w-full border-gray-300 rounded-lg" rows="4"></textarea>
                </div>

                <div class="flex justify-end space-x-2">
                    <button type="button" onclick="document.getElementById('gradeModal').classList.add('hidden')"
                        class="px-4 py-2 text-white bg-gray-500 rounded">
                        Cancel
                    </button>

                    <button type="submit" class="px-4 py-2 text-white bg-indigo-600 rounded hover:bg-indigo-700">
                        Save Grade
                    </button>
                </div>
            </form>

        </div>
    </div>


    <script>
        function openGradeModal(id, grade, feedback) {
            document.getElementById('gradeModal').classList.remove('hidden');

            document.getElementById('gradeForm').action =
                `/instructor/assignments/submissions/${id}/grade`;

            document.getElementById('gradeInput').value = grade ?? '';
            document.getElementById('feedbackInput').value = feedback ?? '';
        }
    </script>
@endsection
