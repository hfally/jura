<?php

namespace App\Http\Controllers;

use App\Topic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class TopicController extends Controller
{
    /**
     * Get details of a particular topic
     *
     * @param integer $topic_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($topic_id)
    {
        $topic = Topic::find($topic_id);

        // If topic wasn't found abort request as 404
        if(!$topic) {
            abort(404);
        }

        // persist Course details
        $topic->course_details = [
            'title' => $topic->course->title,
            'description' => $topic->course->description,
        ];

        // Remove `course` property added due to eloquent
        unset($topic->course);

        // Persist Previous topic details if any
        if($topic->topic) {
            $topic->parent_topic = [
                'id' => $topic->topic->id,
                'name' => $topic->topic->name,
            ];
        }

        // Remove `topic` property added due to eloquent
        unset($topic->topic);

        return response()->json([
            'status' => 200,
            'data' => $topic
        ]);
    }

    public function create(Request $request)
    {
        $parents_table = str_plural($request->parent);

        // Validate request bag
        $validator = Validator::make($request->all(), [
            'parent' => 'bail | required | in:course,topic',
            'parent_id' => "required | exists:$parents_table,id",
            'name' => [
                'required',
                'min:2',
                Rule::unique('topics', 'name')->where(function ($query) use($request) {
                    // If parent is topic, validate against all immediate children of the topic
                    $condition = $query->where("{$request->parent}_id", $request->parent_id);

                    // if parent is course, validate against only immediate course's topics
                    if ($request->parent == 'course') {
                        $condition = $condition->whereNull('topic_id');
                    }

                    return $condition;
                })
            ],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'message' => 'Bad request',
                'errors' => $validator->errors()
            ]);
        }

        // Create topic if validation passes
        try {
            // Dynamically get the parent table (topics or courses)
            $parent = DB::table($parents_table)->find($request->parent_id);

            $topic = Topic::create([
                'name' => $request->name,
                'course_id' => ($request->parent == 'topic' ? $parent->course_id : $request->parent_id),
                'topic_id' => ($request->parent == 'topic' ? $request->parent_id : null)
            ]);

            $response = [
                'status' => 200,
                'topic' => $topic
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