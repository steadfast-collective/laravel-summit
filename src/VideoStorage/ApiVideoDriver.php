<?php

namespace SteadfastCollective\Summit\VideoStorage;

use Illuminate\Http\UploadedFile;
use SteadfastCollective\Summit\Contracts\VideoStorageDriver;
use SteadfastCollective\Summit\Models\CourseBlock;
use SteadfastCollective\Summit\Models\Video;

class ApiVideoDriver implements VideoStorageDriver
{
    public function upload(CourseBlock $courseBlock, $file, $path = null, $type = null): Video
    {
        if ($file instanceof UploadedFile) {
            throw new \Exception("The api.video storage driver can't upload an `UploadedFile`. Please pass in a Video ID instead.");
        }

        if (! class_exists('SteadfastCollective\ApiVideo\Facades\ApiVideo')) {
            throw new \Exception("To use api.video, please install our api.video package: `composer require steadfastcollective/laravel-api-video`");
        }

        $getVideo = \SteadfastCollective\ApiVideo\Facades\ApiVideo::getVideo($file);

        return Video::create([
            'file_path' => $getVideo->videoId,
            'file_type' => $type ?? null,
            'size' => null,
            'video_duration' => null,
        ]);
    }

    public function url(Video $video): ?string
    {
        $getVideo = \SteadfastCollective\ApiVideo\Facades\ApiVideo::getVideo($video->file_path);

        if (! $getVideo) {
            return null;
        }

        return $video->assets['player'];
    }
}
