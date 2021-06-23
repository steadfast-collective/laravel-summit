<?php

namespace SteadfastCollective\Summit\Tests\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use SteadfastCollective\Summit\Models\Course;
use SteadfastCollective\Summit\Tests\TestCase;

class CourseBlockTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function course_block_belongs_to_course()
    {
        $course = Course::create([
            'name' => 'Laravel Crash Course',
            'slug' => 'laravel-crash-course',
            'estimated_length' => 50,
        ]);

        $courseBlock = $course->courseBlocks()->create([
            'title' => 'Eloquent',
            'estimated_length' => 50,
        ]);

        $this->assertTrue($courseBlock->course() instanceof BelongsTo);
        $this->assertSame($courseBlock->id, $course->id);
    }

    /** @test */
    public function course_block_belongs_to_many_users()
    {
        //
    }

    /** @test */
    public function can_scope_to_available_from()
    {
        //
    }

    /** @test */
    public function course_block_has_many_videos()
    {
        //
    }

    /** @test */
    public function can_attach_video_to_course_block()
    {
        //
    }

    /** @test */
    public function can_detach_video_from_course_block()
    {
        //
    }

    /** @test */
    public function can_get_all_videos()
    {
        //
    }

    /** @test */
    public function can_get_first_video()
    {
        //
    }

    /** @test */
    public function can_get_last_video()
    {
        //
    }
}
