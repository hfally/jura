<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Topic extends Model
{

    protected  static function boot()
    {
        parent::boot();

        // Update course id of all children topics if parent topic is updated
        self::updated(function ($topic) {
            // Run a loop because this event will not be triggered on a mass-update
            foreach ($topic->sub_topics as $sub_topic) {
                $sub_topic->update([
                    'course_id' => $topic->course_id
                ]);
            }
        });

        // Delete all children topics if parent topic is being deleted
        self::deleting(function ($topic) {
            // Run a loop because this event will not be triggered on a mass-update
            foreach ($topic->sub_topics as $sub_topic) {
                $sub_topic->delete();
            }
        });
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    protected $with = 'sub_topics';

    /**
     * all sub topics relationship
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function sub_topics()
    {
        return $this->hasMany(Topic::class);
    }

    /**
     * Parent Topic if any
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function topic()
    {
        return $this->belongsTo(Topic::class);
    }

    /**
     * Course topic belongs to
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
