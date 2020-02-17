<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Schedules extends Model
{
    protected $guarded = [];

    protected $with = ['movie', 'studio'];

    public function movie() {
        return $this->belongsTo(Movie::class);
    }

    public function studio() {
        return $this->belongsTo(Studio::class);
    }
}
