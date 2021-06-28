<h1 align="center">Summit</h1>

<p align="center">
    <a href="https://packagist.org/packages/steadfastcollective/summit">
        <img src="https://img.shields.io/packagist/dt/steadfastcollective/summit" alt="Total Downloads">
    </a>
    <a href="https://packagist.org/packages/steadfastcollective/summit">
        <img src="https://img.shields.io/packagist/v/steadfastcollective/summit" alt="Latest Stable Version">
    </a>
    <a href="https://packagist.org/packages/steadfastcollective/summit">
        <img src="https://img.shields.io/packagist/l/steadfastcollective/summit" alt="License">
    </a>
</p>

## Introduction

This package powers [Steadfast Collective](https://steadfastcollective.com)'s Summit platform and other apps. It provides a starting point for building video course applications.

## Documentation

### Installation

1. Install via Composer

```
composer require steadfastcollective/laravel-summit
```

2. Run the `summit:install` command to publish Summit's files:

```
php artisan summit:install
```

### Migrations

During installation, you'll get the option to publish Summit's migrations. These migrations create a few tables (`courses`, `course_blocks` and `videos`). These tables are used for some of Summit's built-in [models](#models). Feel free to add any columns to these as you wish.

### Extending Models

Summit provides a set of models for things like Courses, Course Blocks and Videos. By default, these models live inside the Summit package. However, there may be cases where you want to add fillable properties, attributes or simply just want to override something we've done. And that's perfectly okay!

The recommended approach to doing this would be to create your own model file, like `app/Models/Course.php`. Inside that, instead of extending the `Model` class provided by Eloquent, extend the built-in Summit model. There's a demo of this below:

```php
<?php

namespace App\Models;

use SteadfastCollective\Summit\Models\Course as SummitCourse;

class Course extends SummitCourse
{
    protected $appends = [
        'hello_world',
    ];

    public function getHelloWorldAttribute()
    {
        return 'Hello World!';
    }
}
```

### Laravel Nova

If you're using Laravel Nova within your application, you can optionally publish our base Nova resources. They're included as part of the `summit:install` command. However, they will only be shown if we detect Nova is installed.

```
php artisan summit:install
```

The Nova Resources will be published into your `app/Nova` directory. Once published, you can customise them to your heart's content.

### Video Storage

There's a couple of different places you can store your videos. Out of the box, we currently support uploading to [Laravel's Filesystem](https://laravel.com/docs/master/filesystem) and to [api.video](https://api.video/).

#### Laravel Filesystem

```php
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
```

When you configure the filesystem driver, be sure to also configure a `video_storage_disk`. This will be the disk (from your `config/filesystems.php`) you wish to be use for storage.

When uploading video with the `uploadVideo` method, you may pass in either an `UploadedFile` (which is the output of `$request->file('video')`) or you can pass a string containing the path to the video (useful if you're using Laravel Vapor).

```php
$courseBlock->uploadVideo($request->file('video'), 'course-videos');
```

#### api.video

```php
/*
|--------------------------------------------------------------------------
| Video Storage
|--------------------------------------------------------------------------
|
| Where should we retrieve and upload your videos? We have built-in
| support for Laravel's Filesystem and api.video
|
*/

'video_storage_driver' => \SteadfastCollective\Summit\VideoStorage\ApiVideoDriver::class,
```

When using our built-in api.video integration, you'll need to pull in our [`steadfastcollective/laravel-api-video`](https://github.com/steadfast-collective/laravel-api-video) package. If you don't we'll just throw an exception to tell you off (just kidding, of course).

Unlike our Filesystem driver, you can only pass in a Video ID string to the `uploadVideo` method when using api.video. The Video ID should be returned once the video has been uploaded successfully client-side.

```php
$courseBlock->uploadVideo($request->get('api_video_id'));
```

## Contributing

Before contributing, please read the our [contibutors guide](CONTRIBUTING.md).

## Security

If you discover any security related issues, please email [dev@steadfastcollective.com](mailto:dev@steadfastcollective.com) instead of using the issue tracker.
