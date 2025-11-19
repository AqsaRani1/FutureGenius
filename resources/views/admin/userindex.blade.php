@extends('dashboards.admin')



@section('content')
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 m-5">
        <div class="bg-white p-4 rounded shadow">Total Users: {{ $totalUsers }}</div>
        <div class="bg-white p-4 rounded shadow">Students: {{ $totalStudents }}</div>
        <div class="bg-white p-4 rounded shadow">Instructors: {{ $totalInstructors }}</div>
        <div class="bg-white p-4 rounded shadow">Courses: {{ $totalCourses }}</div>
    </div>
@endsection
