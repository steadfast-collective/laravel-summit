<?php

namespace SteadfastCollective\Summit\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use SteadfastCollective\Summit\Models\Video;

class AttachedVideo
{
    use Dispatchable, SerializesModels;

    public Video $video;

    public function __construct(Video $video)
    {
        $this->video = $video;
    }
}
