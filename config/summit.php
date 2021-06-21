<?php

return [

    /*
     * The course model class that should be
     * used to store and retrieve the courses.
     */
    'course_model' => \SteadfastCollective\Summit\Models\Course::class,
    
    /*
     * The user model class that should be used when associating course
     * blocks with users (progress tracker). If null, the default user
     * provider from your Laravel authentication configuration will
     * be used.
     */
    'user_model' => null,
    
    'featured_image_disk' => env('FILESYSTEM_DRIVER', 'public'),
];
