<?php

namespace SteadfastCollective\Summit\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use SteadfastCollective\Summit\Models\Concerns\HasFeaturedImage;

class Course extends Model
{
    use HasFeaturedImage;

    protected $table = 'courses';

    protected $guarded = [];

    protected $casts = [
        'start_date' => 'datetime',
        'publish_date' => 'datetime',
    ];

    public function courseBlocks() : HasMany
    {
        return $this->hasMany(config('summit.course_block_model'), 'course_id');
    }

    public function scopePublished(Builder $query) : Builder
    {
        return $query
            ->whereNotNull('publish_date')
            ->where('publish_date', '<=', now());
    }

    public function scopeStarted(Builder $query) : Builder
    {
        return $query
            ->whereNotNull('start_date')
            ->where('start_date', '<=', now());
    }

    public function scopeWithProgress(Builder $query, int $userId) : Builder
    {
        return $query
            ->leftJoin('course_blocks', 'courses.id', '=', 'course_blocks.course_id')
            ->leftJoin('course_block_user', function ($join) use ($userId) {
                $join->on('course_block_user.course_block_id', '=', 'course_blocks.id')
                        ->where('course_block_user.user_id', '=', $userId)
                        ->whereNotNull('finished_at');
            })
            ->groupBy('courses.id')
            ->select('courses.*', DB::raw('COUNT(course_block_user.user_id) as user_progress_count'), DB::raw('ROUND(((COUNT(course_block_user.user_id) / COUNT(course_blocks.id)) * 100)) as user_progress_percentage'));
    }

    public function getUserProgressPercentageAttribute($value)
    {
        return $value.'%';
    }

    public function getReadableEstimatedLengthAttribute() : string
    {
        $seconds = $this->estimated_length;

        $units = collect([
            'hour'   => floor($seconds / 60 / 60),
            'minute' => floor(($seconds % (60 * 60)) / 60),
            'second' => $seconds % 60,
        ]);

        return $units
            ->filter(function ($value) {
                return $value > 0;
            })
            ->map(function ($item, $key) {
                return "{$item} ".Str::plural($key, $item);
            })
            ->implode(' ');
    }

    public function getShortReadableEstimatedLengthAttribute() : string
    {
        $seconds = $this->estimated_length;

        $units = collect([
            'h'   => floor($seconds / 60 / 60),
            'm'   => floor(($seconds % (60 * 60)) / 60),
        ]);

        return $units
            ->filter(function ($value) {
                return $value > 0;
            })
            ->map(function ($item, $key) {
                return $item.$key;
            })
            ->implode(' ');
    }

    public function getProgressPercentageAttribute(): string
    {
        if (Auth::guest()) {
            return '0%';
        }

        $totalCourseBlocks = $this->courseBlocks()->count();

        $finishedCourseBlocks = CourseBlockProgress::query()
            ->whereHas('courseBlock', function ($query) {
                return $query->where('course_id', $this->id);
            })
            ->where('user_id', Auth::user()->id)
            ->where('finished_at', '!=', null)
            ->count();

        $progress = ($finishedCourseBlocks / $totalCourseBlocks) * 100;

        return round($progress) . '%';
    }

    public function getEstimatedLengthAttribute()
    {
        return $this->courseBlocks()->sum('estimated_length');
    }

    public function getChaptersCountAttribute()
    {
        return $this->courseBlocks()->where('type', 'CHAPTER')->count();
    }

    public function getExcerptAttribute() : string
    {
        return Str::limit($this->description, 280);
    }
}
