<?php

namespace SteadfastCollective\Summit\Tests\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use SteadfastCollective\Summit\Models\Course;
use SteadfastCollective\Summit\Models\CourseBlock;
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


    public function course_blocks_have_an_available_scope()
    {
        $course = Course::create([
            'name' => 'How to use Stripe Checkout',
            'slug' => 'how-to-use-stripe-checkout',
            'description' => 'This is a course about Stripe Checkout.',
            'estimated_length' => 3600,
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
            'estimated_length' => 3600,
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
                'available_from' => now()->subWeek(),
            ]
        ]);

        $this->assertCount(1, $availableBlocks = CourseBlock::availableFrom(now())->get());
        $this->assertSame($courseBlocks->first()->id, $availableBlocks->first()->id);
    }
}
