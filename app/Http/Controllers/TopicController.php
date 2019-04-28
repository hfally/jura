<?php

namespace App\Http\Controllers;

use App\Topic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class TopicController extends Controller
{
    /*
    |---------------------------------------------------
    | Topic Controller
    |---------------------------------------------------
    | This controller is in charge of creating, updating
    | and deleting of specified topics.
    |
    | It is also responsible for getting specified topics;
    | to the lowest level.
    |
    */

    /**
     * CourseController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

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
        if (!$topic) {
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
        if ($topic->topic) {
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

    /**
     * Create a new topic
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request)
    {
        $parents_table = str_plural($request->parent);

        // Validate Parent column
        $validator = Validator::make($request->all(), [
            'parent' => 'required|in:course,topic',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'message' => 'Bad request',
                'errors' => $validator->errors(),
            ]);
        }

        // Validate other request field
        $validator = Validator::make($request->all(), [
            'parent_id' => "required|exists:$parents_table,id",
            'name' => [
                'required',
                'min:2',
                Rule::unique('topics', 'name')->where(function ($query) use ($request) {
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

    /**
     * Update specified topic
     *
     * @param $topic_id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update($topic_id, Request $request)
    {
        $topic = Topic::find($topic_id);

        // If topic wasn't found abort request as 404
        if (!$topic) {
            abort(404);
        }

        // Validate Parent column
        $validator = Validator::make($request->all(), [
            'parent' => 'required_without:name|required_with:parent_id|in:course,topic',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'message' => 'Bad request',
                'errors' => $validator->errors(),
            ]);
        }

        // Validate other request fields
        $validator = Validator::make($request->all(), [
            'parent_id' => "bail|required_with:parent|required_without:name|exists:{$request->parent}s,id" .
                ($request->parent == 'topic' ? "|not_in:$topic->id" : ''),
            'name' => [
                'required_without_all:parent,parent_id',
                'min:2',
                Rule::unique('topics', 'name')->where(function ($query) use ($request, $topic) {
                    // Derive which column and id to validate against
                    $column = ($request->parent ?? ($topic->topic_id ? 'topic' : 'course')) . '_id';
                    $id = $request->parent_id ?? ($topic->topic_id ?? $topic->course_id);

                    if (!in_array($column, ['topic_id', 'course_id'])) {
                        return;
                    }

                    // If parent is topic, validate against all immediate children of the topic
                    $condition = $query->where($column, $id)->where('id', '!=', $topic->id);

                    // if parent is course, validate against only immediate course's topics
                    if ($column == 'course_id') {
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
                'errors' => $validator->errors(),
            ]);
        }

        // Update topic
        try {
            $updated_topic = DB::transaction(function () use ($topic, $request) {
                $data = [
                    'name' => $request->name ?? $topic->name
                ];

                if ($request->has('parent')) {
                    // Dynamically get the parent table (topics or courses)
                    $parent = DB::table($request->parent . 's')->find($request->parent_id);

                    // Update corresponding parent
                    $data['course_id'] = ($request->parent == 'topic' ? $parent->course_id : $request->parent_id);
                    $data['topic_id'] = ($request->parent == 'topic' ? $request->parent_id : null);
                }

                // Update instance of topic
                $topic->update($data);

                // Re-retrieve to get updated records (because of chained records)
                return Topic::find($topic->id);
            });

            $response = [
                'status' => 200,
                'topic' => $updated_topic
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

    public function delete($topic_id)
    {
        $topic = Topic::find($topic_id);

        // If topic wasn't found abort request as 404
        if (!$topic) {
            abort(404);
        }

        try {
            // Delete topic and sub-topics
            DB::transaction(function () use ($topic) {
                // Delete the topic (Sub topic is deleted with an event in the model)
                $topic->delete();
            });

            $response = [
                'status' => 200,
                'message' => 'Topic and its related topics deleted!'
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