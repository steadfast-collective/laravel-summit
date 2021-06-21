<?php

namespace SteadfastCollective\Summit\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;
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
        return $query->where('published_date', '<=', now());
    }

    public function scopeStarted(Builder $query) : Builder
    {
        return $query->where('start_date', '<=', now());
    }

    public function getReadableEstimatedLengthAttribute()
    {
        return Carbon::parse($this->estimatedLength);
    }
}
