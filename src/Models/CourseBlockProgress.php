<?php

namespace SteadfastCollective\Summit\Models;

use Carbon\CarbonInterval;
use Illuminate\Database\Eloquent\Relations\Pivot;

class CourseBlockProgress extends Pivot
{
    protected $table = 'course_block_user';

    protected $casts = [
        'started_at' => 'datetime',
        'finished_at' => 'datetime',
        'progress' => 'integer',
    ];

    protected $appends = [
        'time_left',
        'progress_percentage'
    ];

    public function getTimeLeftAttribute()
    {
        $totalLength = $this->courseBlock->estimated_length;

        if (!$totalLength) {
            $totalLength = Video::where('model_type', config('summit.course_block_model'))->where('model_id', $this->course_block_id)->sum('video_duration');
        }

        if ($totalLength <= 0) {
            return '0 seconds';
        }

        return CarbonInterval::seconds($totalLength - $this->progress)->cascade()->forHumans();
    }

    public function getProgressPercentageAttribute()
    {
        $totalLength = $this->courseBlock->estimated_length;
        
        if (!$totalLength) {
            $totalLength = Video::where('model_type', config('summit.course_block_model'))->where('model_id', $this->course_block_id)->sum('video_duration');
        }

        if ($totalLength <= 0) {
            return '0%';
        }

        return round($this->progress / $totalLength) . '%';
    }

    public function courseBlock()
    {
        return $this->belongsTo(config('summit.course_block_model'), 'course_block_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
