<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];
    protected $with = ['topics'];

    public function topics()
    {
        return $this->hasMany(Topic::class);
    }
}