<?php

namespace App\Http\Controllers;

use App\Course;
use App\Topic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CourseController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function all_courses()
    {
        $courses = Course::all();

        return response()->json([
            'status' => 200,
            'count' => $courses->count(),
            'data' => $courses
        ]);
    }

    public function topics(Request $request)
    {
        // Validate Request
        $validator = Validator::make($request->all(), [
            'course_id' => 'required|exists:courses,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validator->errors(),
                'message' => 'Bad request.'
            ]);
        }

        $course = Course::find($request->course_id);
        $topics = $course->topics;

        return response()->json([
            'status' => 200,
            'count' => $topics->count(),
            'data' => $topics
        ]);
    }

    public function sub_topics(Request $request)
    {
        // Validate Request
        $validator = Validator::make($request->all(), [
            'topic_id' => 'required|exists:topics,id'
        ]);

        if($validator->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validator->errors(),
                'message' => 'Bad request.'
            ]);
        }

        $topic = Topic::find($request->topic_id);
        $sub_topics = $topic->sub_topics;

        return response()->json([
            'status' => 200,
            'count' => $sub_topics->count(),
            'data' => $sub_topics
        ]);
    }
}
