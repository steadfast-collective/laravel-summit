<?php

namespace SteadfastCollective\Summit\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use SteadfastCollective\Summit\Models\Concerns\HasAuthModel;
use SteadfastCollective\Summit\Models\Concerns\HasVideo;

class CourseBlock extends Model
{
    use HasVideo, HasAuthModel;

    protected $table = 'course_blocks';

    protected $guarded = [];

    protected $casts = [
        'order' => 'integer',
        'estimated_length' => 'integer',
        'available_from' => 'datetime',
    ];

    public function course(): BelongsTo
    {
        return $this->belongsTo(config('summit.course_model'));
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany($this->getAuthModelClass())
            ->withPivot('started_at', 'finished_at', 'progress')
            ->withTimestamps();
    }

    public function scopeAvailable(Builder $query): Builder
    {
        return $query
            ->whereNotNull('available_from')
            ->where('available_from', '<=', now());
    }

    public function scopeAvailableFrom(Builder $query, $dateTime): Builder
    {
        return $query
            ->whereNotNull('available_from')
            ->where('available_from', '>=', $dateTime);
    }
}
