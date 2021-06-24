<?php

namespace SteadfastCollective\Summit\Models\Concerns;

use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use SteadfastCollective\Summit\Models\Video;

trait HasVideo
{
    public function videos() : MorphMany
    {
        return $this->morphMany(Video::class, 'model');
    }

    /**
     * @param Video $video
     *
     * @return mixed
     */
    public function attachVideo(Video $video)
    {
        $this->videos()->save($video);
    }

    /**
     * @param Video|null $video
     *
     * @return mixed
     *
     * @throws Exception
     */
    public function detachVideo(Video $video)
    {
        $video->delete();
    }

    public function getVideos() : Collection
    {
        return $this->videos()->get();
    }

    public function getFirstVideo() : Video
    {
        return $this->videos()->first();
    }

    public function getLastVideo() : Video
    {
        return $this->videos()->latest()->first();
    }

    /**
     * @param UploadedFile|string $file
     * @param string $path
     *
     * @return Video
     */
    public function uploadVideo($file, string $path = ''): Video
    {
        if ($file instanceof UploadedFile) {
            $filePath = $file->storeAs("{$path}/{$file->getFilename()}", $file->getFilename(), [
                'disk' => config('summit.videos_disk'),
            ]);

            return $this->videos()->create([
                'file_path' => $filePath,
                'file_name' => $file->getFilename(),
                'file_type' => $file->getMimeType(),
            ]);
        }

        // TODO: use a different config variable to decide if we want to use api.video
        if (config('summit.videos_disk') === 'API_VIDEO') {

        }

        Storage::disk(config('summit.videos_disk'))->copy();
    }
}
