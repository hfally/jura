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
        \Illuminate\Support\Facades\DB::table('sub_topics')->truncate();

        factory(\App\Course::class, 50)->create()->each(function ($course) {
            factory(\App\Topic::class, 10)->create(['course_id' => $course->id])->each(function ($topic) {
                factory(\App\SubTopic::class, 10)->create(['topic_id' => $topic->id]);
            });
        });
    }
}
