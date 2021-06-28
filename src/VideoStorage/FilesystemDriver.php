<?php

namespace SteadfastCollective\Summit\VideoStorage;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use SteadfastCollective\Summit\Contracts\VideoStorageDriver;
use SteadfastCollective\Summit\Models\CourseBlock;
use SteadfastCollective\Summit\Models\Video;

class FilesystemDriver implements VideoStorageDriver
{
    public function upload(CourseBlock $courseBlock, $file, $path = null, $type = null): Video
    {
        if ($file instanceof UploadedFile) {
            $filePath = $file->storeAs("{$path}/{$file->getFilename()}", $file->getFilename(), [
                'disk' => config('summit.videos_disk'),
            ]);

            return $courseBlock->videos()->create([
                'file_path' => $filePath,
                'file_type' => $type ?? $file->getMimeType(),
            ]);
        }

        // Otherwise, assume we already have the file uploaded somewhere & just copy it instead (useful for Vapor)
        Storage::disk(config('summit.videos_disk'))->copy($file, $filePath = "{$path}/{$file}");

        return $courseBlock->videos()->create([
            'file_path' => $filePath,
            'file_type' => $type ?? Storage::disk(config('summit.videos_disk'))->mimeType($filePath),
        ]);
    }
}
