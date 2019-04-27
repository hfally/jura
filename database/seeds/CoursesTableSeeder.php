<?php

use Illuminate\Database\Seeder;

class CoursesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \Illuminate\Support\Facades\DB::table('courses')->truncate();
        \Illuminate\Support\Facades\DB::table('topics')->truncate();

        factory(\App\Course::class, 5)->create()->each(function ($course) {
            factory(\App\Topic::class, 3)->create(['course_id' => $course->id])->each(function ($topic) use ($course) {
                factory(\App\Topic::class, 2)->create(['topic_id' => $topic->id, 'course_id' => $course->id])->each(function ($topic) use ($course) {
                    factory(\App\Topic::class, 1)->create(['topic_id' => $topic->id, 'course_id' => $course->id]);
                });
            });
        });
    }
}
