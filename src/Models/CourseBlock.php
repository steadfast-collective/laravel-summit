<?php

namespace SteadfastCollective\Summit\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;
use SteadfastCollective\Summit\Models\Concerns\HasAuthModel;
use SteadfastCollective\Summit\Models\Concerns\HasVideo;

class CourseBlock extends Model
{
    use HasVideo, HasAuthModel;

    protected $table = 'course_blocks';

    protected $guarded = [];

    protected $casts = [
        'order'            => 'integer',
        'estimated_length' => 'integer',
        'available_from'   => 'datetime',
    ];

    public function course(): BelongsTo
    {
        return $this->belongsTo(config('summit.course_model'), 'course_id');
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany($this->getAuthModelClass(), 'course_block_user', 'course_block_id')
            ->using(CourseBlockProgress::class)
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

    public function getFormattedEstimatedLengthAttribute()
    {
        $seconds = $this->estimated_length;

        $units = collect([
            'h'   => floor($seconds / 60 / 60),
            'm'   => floor(($seconds % (60 * 60)) / 60),
            's'   => $seconds % 60,
        ]);

        return $units
            ->reject(function ($value, $key) {
                return $value <= 0 && $key === 'h';
            })
            ->map(function ($item, $key) use ($seconds) {
                if ($key === 's' || ($key === 'm' && $seconds >= 3600)) {
                    return sprintf('%02d', $item);
                }

                return $item;
            })
            ->implode(':');
    }

    public function getExcerptAttribute()
    {
        return Str::limit($this->description, 280);
    }
}
