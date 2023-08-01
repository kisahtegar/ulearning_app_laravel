<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Course;

class CourseController extends Controller
{
    // Return all the course list.
    public function courseList() {
        try {
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
        } catch (\Throwable $throw) {
            // error handling
            return response()->json([
                'code' => 500,
                'msg' => 'The column does not exist or you have syntax errors',
                'data' => $throw->getMessage()
            ], 500);
        }
    }
    
    // Return all the course detail.
    public function courseDetail(Request $request) {
        // course id
        $id = $request->id;

        try {
            // select the fields and using get method to get the course list.
            $result = Course::select(
                'id',
                'name', 
                'user_token',
                'description',
                'price',
                'lesson_num',
                'video_length',
                'thumbnail',
                'downloadable_res'
            )->where(
                'id', '=', $id
            )->first();

            // then return the result.
            return response()->json([
                'code' => 200,
                'msg' => 'My course detail is here',
                'data' => $result
            ], 200);
        } catch (\Throwable $throw) {
            // error handling
            return response()->json([
                'code' => 500,
                'msg' => 'The column does not exist or you have syntax errors',
                'data' => $throw->getMessage()
            ], 500);
        }
    }
}
