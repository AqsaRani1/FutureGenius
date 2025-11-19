<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function dashboard(){
        $courses = auth()->user()->coursesEnrolled()->with(['events' => function($q) {
    $q->where('event_date', '<=', now())
      ->where(function($q2) {
          $q2->whereNull('end_date')->orWhere('end_date', '>=', now());
      })
      ->orderBy('event_date');
}])->get();

        return view('dashboards.student');
    }

}