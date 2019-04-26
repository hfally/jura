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

    public function sub_topics()
    {
        return $this->hasMany(SubTopic::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
