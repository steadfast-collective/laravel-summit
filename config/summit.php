<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Course Model
    |--------------------------------------------------------------------------
    |
    | Which class should we reference as the course model? It's used
    | to store and retrieve the courses.
    |
    */

    'course_model' => \SteadfastCollective\Summit\Models\Course::class,

    /*
    |--------------------------------------------------------------------------
    | Course Block Model
    |--------------------------------------------------------------------------
    |
    | Which class should we reference as the course block model? It's used
    | to store and retrieve the course blocks.
    |
    */

    'course_block_model' => \SteadfastCollective\Summit\Models\CourseBlock::class,

    /*
    |--------------------------------------------------------------------------
    | User Model
    |--------------------------------------------------------------------------
    |
    | Which class should we reference as the user model? It'll be used when
    | associating course blocks with users (for tracking their progress).
    | If null, we'll fallback to the model used in your `auth.php` file.
    |
    */

    'user_model' => null,

    /*
    |--------------------------------------------------------------------------
    | Featured Image Disk
    |--------------------------------------------------------------------------
    |
    | Where should we store featured images for your courses? By default, we'll
    | use the default Filesystem from your `filesystems.php` config. If you're
    | using Laravel Vapor, we'll presume you're wanting to use S3.
    |
    */

    'featured_image_disk' => env('FILESYSTEM_DRIVER', 'public'),

    /*
    |--------------------------------------------------------------------------
    | Video Storage
    |--------------------------------------------------------------------------
    |
    | Where should we retrieve and upload your videos? We have built-in
    | support for Laravel's Filesystem and api.video
    |
    */

    'video_storage_driver' => \SteadfastCollective\Summit\VideoStorage\FilesystemDriver::class,

    // Only required with the `FilesystemDriver`
    'video_storage_disk' => env('FILESYSTEM_DRIVER', 'public'),

];
