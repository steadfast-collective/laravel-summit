<?php

namespace SteadfastCollective\Summit\VideoStorage;

use Illuminate\Http\UploadedFile;
use SteadfastCollective\Summit\Contracts\VideoStorageDriver;
use SteadfastCollective\Summit\Models\CourseBlock;
use SteadfastCollective\Summit\Models\Video;

class YouTubeDriver implements VideoStorageDriver
{
    const YOUTUBE_URL = 'https://www.youtube.com/watch?v=';

    public function upload(CourseBlock $courseBlock, $file = null, $path = null, $type = null, $thirdPartyId = null): Video
    {
        return Video::create([
            'third_party_id' => $thirdPartyId,
            'provider' => 'youtube',
        ]);
    }

    public function url(Video $video): ?string
    {
        $video = $video->third_party_id;

        return self::YOUTUBE_URL.$video;
    }
}
