<?php

namespace SteadfastCollective\Summit\Models\Concerns;

// use App\Library\ApiVideo;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\MorphMany;
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
        return $this->videos()->save($video);
    }

    /**
     * @param Video|null $video
     *
     * @return mixed
     *
     * @throws Exception
     */
    public function detachVideo(Video $video = null)
    {
        // TODO: API.VIDEO package
        // $apiVideo = new ApiVideo;

        if (! is_null($video)) {
            // $apiVideo->deleteVideo($video->api_video_id);

            return $video->delete();
        }

        // $this->videos()->each(function ($video) use ($apiVideo) {
        //     $apiVideo->deleteVideo($video->api_video_id);
        // });

        return $this->videos()->delete();
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
}
