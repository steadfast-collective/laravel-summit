<?php

namespace SteadfastCollective\Summit\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Video extends Model
{
    protected $table = 'videos';
    
    protected $guarded = [];

    protected $casts = [
        'size' => 'integer',
        'video_duration' => 'integer',
        'publish_date' => 'datetime',
    ];

    public function model(): MorphTo
    {
        return $this->morphTo();
    }
}
