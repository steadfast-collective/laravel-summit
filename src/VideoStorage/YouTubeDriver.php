<?php

namespace SteadfastCollective\Summit\VideoStorage;

use Illuminate\Http\UploadedFile;
use SteadfastCollective\Summit\Contracts\VideoStorageDriver;
use SteadfastCollective\Summit\Models\CourseBlock;
use SteadfastCollective\Summit\Models\Video;

class YouTubeDriver
{
    const YOUTUBE_URL = 'https://www.youtube.com/watch?v=';

    public static function url(string $video): ?string
    {
        return self::YOUTUBE_URL.$video;
    }
}
