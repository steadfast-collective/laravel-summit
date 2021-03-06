<?php

namespace SteadfastCollective\Summit\Tests\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use stdClass;
use SteadfastCollective\ApiVideo\Facades\ApiVideo;
use SteadfastCollective\Summit\Models\Course;
use SteadfastCollective\Summit\Models\CourseBlock;
use SteadfastCollective\Summit\Models\Video;
use SteadfastCollective\Summit\Tests\TestCase;
use SteadfastCollective\Summit\VideoStorage\ApiVideoDriver;
use SteadfastCollective\Summit\VideoStorage\FilesystemDriver;

class CourseBlockTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function course_block_belongs_to_course()
    {
        $course = Course::create([
            'name' => 'Laravel Crash Course',
            'slug' => 'laravel-crash-course',
        ]);

        $courseBlock = $course->courseBlocks()->create([
            'title' => 'Eloquent',
            'slug' => 'eloquent',
            'estimated_length' => 50,
        ]);

        $this->assertTrue($courseBlock->course() instanceof BelongsTo);
        $this->assertSame($courseBlock->id, $course->id);
    }

    /** @test */
    public function course_block_belongs_to_many_users()
    {
        $course = Course::create([
            'name' => 'Laravel Crash Course',
            'slug' => 'laravel-crash-course',
        ]);

        $courseBlock = $course->courseBlocks()->create([
            'title' => 'Migrations',
            'slug' => 'migrations',
            'estimated_length' => 50,
        ]);

        $user = User::create([
            'name' => 'Test',
            'email' => 'test@steadfastcollective.com',
            'password' => Hash::make('password'),
        ]);

        $courseBlock->users()->attach($user, [
            'started_at' => now(),
            'progress' => 25,
        ]);

        $this->assertTrue($courseBlock->users() instanceof BelongsToMany);
        $this->assertSame($courseBlock->users->first()->id, $user->id);
    }

    /** @test */
    public function course_block_morph_many_videos()
    {
        $course = Course::create([
            'name' => 'Laravel Crash Course',
            'slug' => 'laravel-crash-course',
        ]);

        $courseBlock = $course->courseBlocks()->create([
            'title' => 'Notifications',
            'slug' => 'notifications',
            'estimated_length' => 50,
        ]);

        $video = $courseBlock->videos()->create([
            'video_duration' => 50,
        ]);

        $this->assertTrue($courseBlock->videos() instanceof MorphMany);
        $this->assertSame($courseBlock->videos->first()->id, $video->id);
    }

    /** @test */
    public function can_attach_video_to_course_block()
    {
        $course = Course::create([
            'name' => 'Laravel Crash Course',
            'slug' => 'laravel-crash-course',
        ]);

        $courseBlock = $course->courseBlocks()->create([
            'title' => 'Broadcasting',
            'slug' => 'broadcasting',
            'estimated_length' => 50,
        ]);

        $video = Video::create([
            'video_duration' => 50,
        ]);

        $attachVideo = $courseBlock->attachVideo($video);

        $this->assertSame($courseBlock->videos->first()->id, $video->id);
    }

    /** @test */
    public function can_detach_video_from_course_block()
    {
        $course = Course::create([
            'name' => 'Laravel Crash Course',
            'slug' => 'laravel-crash-course',
        ]);

        $courseBlock = $course->courseBlocks()->create([
            'title' => 'Events',
            'slug' => 'events',
            'estimated_length' => 50,
        ]);

        $video = $courseBlock->videos()->create([
            'video_duration' => 50,
        ]);

        $this->assertSame($courseBlock->videos->count(), 1);
        $this->assertSame($courseBlock->videos->first()->id, $video->id);

        $detachVideo = $courseBlock->detachVideo($video);

        $this->assertSame($courseBlock->videos()->count(), 0);
        $this->assertNull($courseBlock->videos()->get()->first());
    }

    /** @test */
    public function can_get_all_videos()
    {
        $course = Course::create([
            'name' => 'Laravel Crash Course',
            'slug' => 'laravel-crash-course',
        ]);

        $courseBlock = $course->courseBlocks()->create([
            'title' => 'Forge',
            'slug' => 'forge',
            'estimated_length' => 50,
        ]);

        $videoOne = $courseBlock->videos()->create([
            'video_duration' => 25,
        ]);

        $videoTwo = $courseBlock->videos()->create([
            'video_duration' => 25,
        ]);

        $getVideos = $courseBlock->getVideos();

        $this->assertTrue($getVideos instanceof Collection);
        $this->assertSame($getVideos->count(), 2);

        $this->assertTrue(in_array($videoOne->id, $getVideos->pluck('id')->toArray()));
        $this->assertTrue(in_array($videoTwo->id, $getVideos->pluck('id')->toArray()));
    }

    /** @test */
    public function can_get_first_video()
    {
        $course = Course::create([
            'name' => 'Laravel Crash Course',
            'slug' => 'laravel-crash-course',
        ]);

        $courseBlock = $course->courseBlocks()->create([
            'title' => 'Horizon',
            'slug' => 'horizon',
            'estimated_length' => 50,
        ]);

        $videoOne = $courseBlock->videos()->create([
            'video_duration' => 25,
            'created_at' => now()->subMinute(),
        ]);

        $videoTwo = $courseBlock->videos()->create([
            'video_duration' => 25,
            'created_at' => now(),
        ]);

        $getFirstVideo = $courseBlock->getFirstVideo();

        $this->assertTrue($getFirstVideo instanceof Video);
        $this->assertSame($getFirstVideo->id, $videoOne->id);
    }

    /** @test */
    public function can_get_last_video()
    {
        $course = Course::create([
            'name' => 'Laravel Crash Course',
            'slug' => 'laravel-crash-course',
        ]);

        $courseBlock = $course->courseBlocks()->create([
            'title' => 'Telescope',
            'slug' => 'telescope',
            'estimated_length' => 50,
        ]);

        $videoOne = $courseBlock->videos()->create([
            'video_duration' => 25,
            'created_at' => now()->subMinute(),
        ]);

        $videoTwo = $courseBlock->videos()->create([
            'video_duration' => 25,
            'created_at' => now(),
        ]);

        $getLastVideo = $courseBlock->getLastVideo();

        $this->assertTrue($getLastVideo instanceof Video);
        $this->assertSame($getLastVideo->id, $videoTwo->id);
    }

    /** @test */
    public function can_upload_video_to_filesystem_from_uploaded_file()
    {
        Config::set('summit.video_storage_driver', FilesystemDriver::class);
        Config::set('summit.video_storage_disk', 'public');

        $course = Course::create([
            'name' => 'Laravel Crash Course',
            'slug' => 'laravel-crash-course',
        ]);

        $courseBlock = $course->courseBlocks()->create([
            'title' => 'Mix',
            'slug' => 'mix',
            'estimated_length' => 50,
        ]);

        $uploadedFile = UploadedFile::fake()->create('mix-video.mp4', 95, 'video/mp4');

        $uploadVideo = $courseBlock->uploadVideo($uploadedFile);

        $this->assertFileExists(Storage::disk(config('summit.videos_disk'))->path($uploadVideo->file_path));
    }

    /** @test */
    public function can_upload_video_to_filesystem_from_uploaded_file_with_specific_path()
    {
        Config::set('summit.video_storage_driver', FilesystemDriver::class);
        Config::set('summit.video_storage_disk', 'public');

        $course = Course::create([
            'name' => 'Laravel Crash Course',
            'slug' => 'laravel-crash-course',
        ]);

        $courseBlock = $course->courseBlocks()->create([
            'title' => 'Mix',
            'slug' => 'mix',
            'estimated_length' => 50,
        ]);

        $uploadedFile = UploadedFile::fake()->create('mix-video.mp4', 95, 'video/mp4');

        $uploadVideo = $courseBlock->uploadVideo($uploadedFile, 'course-videos');

        $this->assertStringContainsString('course-videos', $uploadVideo->file_path);
        $this->assertFileExists(Storage::disk(config('summit.videos_disk'))->path($uploadVideo->file_path));
    }

    /** @test */
    public function can_upload_video_to_filesystem_from_uploaded_file_with_file_type()
    {
        Config::set('summit.video_storage_driver', FilesystemDriver::class);
        Config::set('summit.video_storage_disk', 'public');

        $course = Course::create([
            'name' => 'Laravel Crash Course',
            'slug' => 'laravel-crash-course',
        ]);

        $courseBlock = $course->courseBlocks()->create([
            'title' => 'Mix',
            'slug' => 'mix',
            'estimated_length' => 50,
        ]);

        $uploadedFile = UploadedFile::fake()->create('mix-video.mp4', 95, 'video/mp4');

        $uploadVideo = $courseBlock->uploadVideo($uploadedFile, null, 'some/random-type');

        $this->assertFileExists(Storage::disk(config('summit.videos_disk'))->path($uploadVideo->file_path));

        $this->assertSame($uploadVideo->file_type, 'some/random-type');
    }

    /** @test */
    public function can_upload_video_to_filesystem_from_path()
    {
        Config::set('summit.video_storage_driver', FilesystemDriver::class);
        Config::set('summit.video_storage_disk', 'public');

        $course = Course::create([
            'name' => 'Laravel Crash Course',
            'slug' => 'laravel-crash-course',
        ]);

        $courseBlock = $course->courseBlocks()->create([
            'title' => 'Mix',
            'slug' => 'mix',
            'estimated_length' => 50,
        ]);

        $uploadedFile = UploadedFile::fake()->create('mix-video.mp4', 95, 'video/mp4');

        $uploadVideo = $courseBlock->uploadVideo($uploadedFile);

        $this->assertFileExists(Storage::disk(config('summit.videos_disk'))->path($uploadVideo->file_path));
    }

    /** @test */
    public function can_upload_video_to_api_video_from_video_id()
    {
        Config::set('summit.video_storage_driver', ApiVideoDriver::class);

        $course = Course::create([
            'name' => 'Laravel Crash Course',
            'slug' => 'laravel-crash-course',
        ]);

        $courseBlock = $course->courseBlocks()->create([
            'title' => 'Mix',
            'slug' => 'mix',
            'estimated_length' => 50,
        ]);

        require_once __DIR__ . '/../__fixtures__/ApiVideo.php';

        $video = new stdClass();
        $video->videoId = 'abcd1234';

        ApiVideo::shouldReceive('getVideo')->andReturn($video);

        $uploadVideo = $courseBlock->uploadVideo('abcd1234');

        $this->assertDatabaseHas('videos', [
            'file_path' => 'abcd1234',
        ]);
    }

    /** @test */
    public function can_upload_video_to_api_video_from_video_id_with_specific_path()
    {
        $this->markTestIncomplete("There's nothing different when uploading videos with a path.");
    }

    /** @test */
    public function can_upload_video_to_api_video_from_video_id_with_file_type()
    {
        Config::set('summit.video_storage_driver', ApiVideoDriver::class);

        $course = Course::create([
            'name' => 'Laravel Crash Course',
            'slug' => 'laravel-crash-course',
        ]);

        $courseBlock = $course->courseBlocks()->create([
            'title' => 'Mix',
            'slug' => 'mix',
            'estimated_length' => 50,
        ]);

        require_once __DIR__ . '/../__fixtures__/ApiVideo.php';

        $video = new stdClass();
        $video->videoId = 'zzz999';

        ApiVideo::shouldReceive('getVideo')->andReturn($video);

        $uploadVideo = $courseBlock->uploadVideo('zzz999', null, 'some/random-type');

        $this->assertDatabaseHas('videos', [
            'file_path' => 'zzz999',
            'file_type' => 'some/random-type',
        ]);
    }

    public function course_blocks_have_an_available_scope()
    {
        $course = Course::create([
            'name' => 'How to use Stripe Checkout',
            'slug' => 'how-to-use-stripe-checkout',
            'description' => 'This is a course about Stripe Checkout.',
            'start_date' => now()->addWeeks(2),
            'publish_date' => now()->subDay(),
        ]);

        $courseBlocks = $course->courseBlocks()->createMany([
            [
                'title' => 'Installing Stripe CLI',
                'description' => 'How to get started with Stripe development using the Stripe CLI.',
                'download_file_path' => null,
                'estimated_length' => 600,
                'order' => 1,
                'available_from' => now(),
            ],
            [
                'title' => 'Creating Stripe Checkout Session',
                'description' => 'How to create a new Stripe Checkout Session.',
                'download_file_path' => null,
                'estimated_length' => 3600,
                'order' => 2,
                'available_from' => now()->addWeek(),
            ]
        ]);

        $this->assertCount(1, $availableBlocks = CourseBlock::available()->get());
        $this->assertSame($courseBlocks->first()->id, $availableBlocks->first()->id);
    }

    /** @test */
    public function course_blocks_have_an_available_from_scope()
    {
        $course = Course::create([
            'name' => 'How to use Stripe Checkout',
            'slug' => 'how-to-use-stripe-checkout',
            'description' => 'This is a course about Stripe Checkout.',
            'start_date' => now()->addWeeks(2),
            'publish_date' => now()->subDay(),
        ]);

        $courseBlocks = $course->courseBlocks()->createMany([
            [
                'title' => 'Installing Stripe CLI',
                'slug' => 'installing-stripe-cli',
                'description' => 'How to get started with Stripe development using the Stripe CLI.',
                'download_file_path' => null,
                'estimated_length' => 600,
                'order' => 1,
                'available_from' => now(),
            ],
            [
                'title' => 'Creating Stripe Checkout Session',
                'slug' => 'creating-stripe-checkout-session',
                'description' => 'How to create a new Stripe Checkout Session.',
                'download_file_path' => null,
                'estimated_length' => 3600,
                'order' => 2,
                'available_from' => now()->subWeek(),
            ]
        ]);

        $this->assertCount(1, $availableBlocks = CourseBlock::availableFrom(now())->get());
        $this->assertSame($courseBlocks->first()->id, $availableBlocks->first()->id);
    }
}
