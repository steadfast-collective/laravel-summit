<?php

namespace SteadfastCollective\Summit\VideoStorage;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use SteadfastCollective\Summit\Contracts\VideoStorageDriver;
use SteadfastCollective\Summit\Models\CourseBlock;
use SteadfastCollective\Summit\Models\Video;

class ApiVideoDriver implements VideoStorageDriver
{
    public function upload(CourseBlock $courseBlock, $file, string $path = ''): Video
    {
        if ($file instanceof UploadedFile) {
            throw new \Exception("The api.video storage driver can't upload an `UploadedFile`. Please pass in a Video ID instead.");
        }

        if (! class_exists('SteadfastCollective\ApiVideo\ApiVideo')) {
            throw new \Exception("To use api.video, please install our api.video package: `composer require steadfastcollective/laravel-api-video`");
        }

        $getVideo = \SteadfastCollective\ApiVideo\Facades\ApiVideo::getVideo($file);

        // TODO
        return Video::create([
            'file_path' => null,
            'file_name' => null,
            'file_type' => null,
            'size' => null,
            'video_duration' => null,
        ]);
    }
}
