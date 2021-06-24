<?php

namespace SteadfastCollective\Summit\Models;

use Carbon\CarbonInterval;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Pivot;

class CourseBlockProgress extends Pivot
{
    protected $casts = [
        'started_at' => 'datetime',
        'finished_at' => 'datetime',
        'progress' => 'integer',
    ];

    protected $appends = [
        'time_left',
    ];

    public function getTimeLeftAttribute()
    {
        $videoLength = $this->courseBlock->getVideos()->pluck('video_duration')->sum();

        return CarbonInterval::seconds($videoLength - $this->progress)->cascade()->forHumans();
    }

    public function courseBlock()
    {
        return $this->belongsTo(CourseBlock::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
