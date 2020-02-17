<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Studio extends Model
{
    protected $guarded = [];

    public function branch() {
        return $this->belongsTo(Studio::class);
    }
}
