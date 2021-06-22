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

    /**
     * Get the Readable Size of a Video file.
     *
     * @return string
     */
    public function getReadableSizeAttribute() : string
    {
        return $this->getHumanReadableSize($this->size);
    }

    public function getDurationForHumansAttribute()
    {
        return \Carbon\CarbonInterval::seconds($this->video_duration)->cascade()->forHumans();
    }

    /**
     * @param int $sizeInBytes
     *
     * @return string Formatted Filesize, e.g. "113.24 MB".
     */
    protected function getHumanReadableSize(int $sizeInBytes) : string
    {
        if ($sizeInBytes >= 1073741824) {
            return number_format($sizeInBytes / 1073741824, 2).' GB';
        }
        
        if ($sizeInBytes >= 1048576) {
            return number_format($sizeInBytes / 1048576, 2).' MB';
        }
        
        if ($sizeInBytes >= 1024) {
            return number_format($sizeInBytes / 1024, 2).' KB';
        }
        
        if ($sizeInBytes > 1) {
            return $sizeInBytes.' bytes';
        }
        
        if ($sizeInBytes == 1) {
            return '1 byte';
        }
       
        return '0 bytes';
    }
}
