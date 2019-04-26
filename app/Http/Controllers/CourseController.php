<?php

namespace App\Http\Controllers;

use App\Course;
use App\SubTopic;
use App\Topic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CourseController extends Controller
{
    /**
     * Get all courses with their topics and corresponding sub topics
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function all_courses()
    {
        $courses = Course::all();

        return response()->json([
            'status' => 200,
            'count' => $courses->count(),
            'data' => $courses
        ]);
    }

    /**
     * Get all topics under a particular course
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
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

    /**
     * Get all sub topics under a particular topic
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sub_topics(Request $request)
    {
        // Validate Request
        $validator = Validator::make($request->all(), [
            'topic_id' => 'required|exists:topics,id'
        ]);

        if ($validator->fails()) {
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

    /**
     * Get details of a course
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function showCourse(Request $request)
    {
        // Validate Request
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:courses'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validator->errors(),
                'message' => 'Bad request.'
            ]);
        }

        return response()->json([
            'status' => 200,
            'data' => Course::find($request->id)
        ]);
    }

    /**
     * Get details of a topic
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function showTopic(Request $request)
    {
        // Validate Request
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:topics'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validator->errors(),
                'message' => 'Bad request.'
            ]);
        }

        return response()->json([
            'status' => 200,
            'data' => Topic::find($request->id)
        ]);
    }

    /**
     * Get details of a sub topic
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function showSubTopic(Request $request)
    {
        // Validate Request
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:sub_topics'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validator->errors(),
                'message' => 'Bad request.'
            ]);
        }

        return response()->json([
            'status' => 200,
            'data' => SubTopic::find($request->id)
        ]);
    }
}
