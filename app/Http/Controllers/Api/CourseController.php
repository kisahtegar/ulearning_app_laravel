<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Course;

class CourseController extends Controller
{
    // Return all the course list.
    public function courseList() {
        // select the fields and using get method to get the course list.
        $result = Course::select('name', 'thumbnail', 'lesson_num', 'price', 'id')->get();
        // We can use like this to..
        // $result = Course::get(['name', 'thumbnail', 'lesson_num', 'price', 'id']);

        // then return the result.
        return response()->json([
            'code' => 200,
            'msg' => 'My course list is here',
            'data' => $result
        ], 200);
    }
}
