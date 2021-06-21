<?php

namespace SteadfastCollective\Summit\Models;

use Exception;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use SteadfastCollective\Summit\Models\Concerns\HasVideo;

class Course extends Model
{
    use HasVideo;

    protected $table = 'course_blocks';
    
    protected $guarded = [];

    protected $casts = [
        'order' => 'integer',
        'estimated_length' => 'integer',
        'available_from' => 'datetime',
    ];

    public function course() : BelongsTo
    {
        return $this->belongsTo(config('summit.course_model'));
    }

    public function users() : BelongsToMany
    {
        return $this->belongsToMany($this->getAuthModelClass())
            ->withPivot('started_at', 'finished_at', 'progress')
            ->withTimestamps();
    }

    /**
     * Get the User model class to be used.
     *
     * @return mixed
     *
     * @throws Exception
     */
    protected function getAuthModelClass()
    {
        if (config('summit.user_model')) {
            return config('summit.user_model');
        }

        if (!is_null(config('auth.providers.users.model'))) {
            return config('auth.providers.users.model');
        }

        throw new Exception('Could not determine the user model class.');
    }
}
