<?php

namespace SteadfastCollective\Summit\Contracts;

use SteadfastCollective\Summit\Models\CourseBlock;
use SteadfastCollective\Summit\Models\Video;

interface VideoStorageDriver
{
    public function upload(CourseBlock $courseBlock, $file, string $path = ''): Video;
}
