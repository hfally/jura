<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SubTopic extends Model
{
    protected $guarded = [];

    public function topic()
    {
        return $this->belongsTo(Topic::class);
    }
}
