<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Topic extends Model
{
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
