<?php

namespace App\Http\Controllers;

use App\Course;
use App\Topic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CourseController extends Controller
{
    /**
     * Get all courses with their topics and
     * corresponding sub topics
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $courses = Course::all();

        return response()->json([
            'status' => 200,
            'count' => $courses->count(),
            'data' => $courses
        ]);
    }

    /**
     * Get details of a particular course
     *
     * @param integer $course_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($course_id)
    {
        $course = Course::find($course_id);

        // If course wasn't found abort request as 404
        if (!$course) {
            abort(404);
        }

        return response()->json([
            'status' => 200,
            'data' => $course
        ]);
    }

    /**
     * Create a course
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request)
    {
        // Validate request bag
        $validator = Validator::make($request->all(), [
            'title' => 'required|min:5|unique:courses',
            'course_code' => 'required|min:3|unique:courses',
            'description' => 'required|min:10',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'message' => 'Bad request',
                'errors' => $validator->errors()
            ]);
        }

        // Create course if validation passes
        try {
            $course = Course::create([
                'title' => $request->title,
                'course_code' => $request->course_code,
                'description' => $request->description
            ]);

            $response = [
                'status' => 200,
                'course' => $course
            ];
        } catch (\Exception $e) {
            // Log error

            // create response
            $response = [
                'status' => 500,
                'message' => 'Something went wrong'
            ];
        } finally {
            return response()->json($response);
        }
    }

    /**
     * Update a particular course
     *
     * @param $course_id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update($course_id, Request $request)
    {
        $course = Course::find($course_id);

        // If course wasn't found abort request as 404
        if (!$course) {
            abort(404);
        }

        // Validate request bag (At least one of the fields must be provided)
        $validator = Validator::make($request->all(), [
            'title' => "required_without_all:course_code,description|min:5|unique:courses,title,$course->id",
            'course_code' => "required_without_all:title,description|min:3|unique:courses,course_code,$course->id",
            'description' => 'required_without_all:title,course_code|min:10',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'message' => 'Bad request',
                'errors' => $validator->errors()
            ]);
        }

        // Update Course details
        try {
            $course->update([
                'title' => $request->title ?? $course->title,
                'course_code' => $request->course_code ?? $course->course_code,
                'description' => $request->description ?? $course->description,
            ]);

            $response = [
                'status' => 200,
                'course' => $course
            ];
        } catch (\Exception $e) {
            // Log error

            // create response
            $response = [
                'status' => 500,
                'message' => 'Something went wrong'
            ];
        } finally {
            return response()->json($response);
        }
    }

    /**
     * Delete an existing course and all its related topics
     *
     * @param $course_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete($course_id)
    {
        $course = Course::find($course_id);

        // If course wasn't found abort request as 404
        if (!$course) {
            abort(404);
        }

        try {
            // Delete course and its topics
            DB::transaction(function () use ($course) {

                // Delete all topics belonging to course
                Topic::where('course_id', $course->id)->delete();

                // Delete the course
                $course->delete();
            });

            $response = [
                'status' => 200,
                'message' => 'Course and its related topics deleted!'
            ];
        } catch (\Exception $e) {
            // Log error

            // create response
            $response = [
                'status' => 500,
                'message' => 'Something went wrong'
            ];
        } finally {
            return response()->json($response);
        }
    }
}