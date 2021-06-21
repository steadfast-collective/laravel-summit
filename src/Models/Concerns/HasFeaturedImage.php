<?php

namespace SteadfastCollective\Summit\Models\Concerns;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

trait HasFeaturedImage
{
   
    /**
     * Update the model's featured image.
     *
     * @param  \Illuminate\Http\UploadedFile  $image
     * @return void
     */
    public function updateFeaturedImage(UploadedFile $image)
    {
        tap($this->featured_image_path, function ($previous) use ($image) {
            $this->forceFill([
                'featured_image_path' => $image->storePublicly(
                    'featured-images',
                    ['disk' => $this->featuredImageDisk()]
                ),
            ])->save();

            if ($previous) {
                Storage::disk($this->featuredImageDisk())->delete($previous);
            }
        });
    }

    /**
     * Delete the models's featured image.
     *
     * @return void
     */
    public function deleteFeaturedImage()
    {
        Storage::disk($this->featuredImageDisk())->delete($this->featured_image_path);

        $this->forceFill([
            'featured_image_path' => null,
        ])->save();
    }

    /**
     * Get the URL to the model's featured image.
     *
     * @return string
     */
    public function getFeaturedImageUrlAttribute()
    {
        return $this->featured_image_path
                    ? Storage::disk($this->featuredImageDisk())->url($this->featured_image_path)
                    : null;
    }


    /**
     * Get the disk that featured images should be stored on.
     *
     * @return string
     */
    protected function featuredImageDisk()
    {
        return isset($_ENV['VAPOR_ARTIFACT_NAME']) ? 's3' : config('summit.featured_image_disk', 'public');
    }
}
