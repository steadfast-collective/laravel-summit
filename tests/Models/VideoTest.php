<?php

namespace SteadfastCollective\Summit\Tests\Models;

use Illuminate\Foundation\Testing\RefreshDatabase;
use SteadfastCollective\Summit\Models\Video;
use SteadfastCollective\Summit\Tests\TestCase;

class VideoTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_get_readable_size_where_size_is_in_gigabytes()
    {
        $video = Video::create([
            'size' => 2500000000,
        ]);

        $this->assertSame($video->readable_size, '2.33 GB');
    }

    /** @test */
    public function can_get_readable_size_where_size_is_in_megabytes()
    {
        $video = Video::create([
            'size' => 16000000,
        ]);

        $this->assertSame($video->readable_size, '15.26 MB');
    }

    /** @test */
    public function can_get_readable_size_where_size_is_in_kilobytes()
    {
        $video = Video::create([
            'size' => 2000,
        ]);

        $this->assertSame($video->readable_size, '1.95 KB');
    }

    /** @test */
    public function can_get_readable_size_where_size_is_in_bytes()
    {
        $video = Video::create([
            'size' => 57,
        ]);

        $this->assertSame($video->readable_size, '57 bytes');
    }

    /** @test */
    public function can_get_readable_size_where_size_is_1_byte()
    {
        $video = Video::create([
            'size' => 1,
        ]);

        $this->assertSame($video->readable_size, '1 byte');
    }

    /** @test */
    public function can_get_readable_size_where_size_is_0_bytes()
    {
        $video = Video::create([
            'size' => 0,
        ]);

        $this->assertSame($video->readable_size, '0 bytes');
    }

    /** @test */
    public function can_get_duration_for_humans_where_length_is_in_seconds()
    {
        $video = Video::create([
            'video_duration' => 25,
        ]);

        $this->assertSame($video->duration_for_humans, '25 seconds');
    }

    /** @test */
    public function can_get_duration_for_humans_where_length_is_in_minutes()
    {
        $video = Video::create([
            'video_duration' => 95,
        ]);

        $this->assertSame($video->duration_for_humans, '1 minute 35 seconds');
    }

    /** @test */
    public function can_get_duration_for_humans_where_length_is_in_hours()
    {
        $video = Video::create([
            'video_duration' => 6300,
        ]);

        $this->assertSame($video->duration_for_humans, '1 hour 45 minutes');
    }
}
