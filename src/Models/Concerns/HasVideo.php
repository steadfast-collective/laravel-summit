<?php

namespace SteadfastCollective\Summit\Models\Concerns;

use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Http\UploadedFile;
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
    public function uploadVideo($file, $path = null, $type = null): Video
    {
        return resolve(config('summit.video_storage_driver'))
            ->upload($this, $file, $path, $type);
    }
}
