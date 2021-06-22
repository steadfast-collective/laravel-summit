<?php

namespace SteadfastCollective\Summit\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;
use SteadfastCollective\Summit\Models\Concerns\HasFeaturedImage;

class Course extends Model
{
    use HasFeaturedImage;

    protected $table = 'courses';
    
    protected $guarded = [];

    protected $casts = [
        'estimated_length' => 'integer',
        'start_date' => 'datetime',
        'publish_date' => 'datetime',
    ];

    public function courseBlocks() : HasMany
    {
        return $this->hasMany(CourseBlock::class);
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

    public function getReadableEstimatedLengthAttribute()
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
}
