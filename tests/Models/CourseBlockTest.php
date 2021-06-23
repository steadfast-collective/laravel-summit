<?php

namespace SteadfastCollective\Summit\Tests\Models;

use Illuminate\Foundation\Testing\RefreshDatabase;
use SteadfastCollective\Summit\Models\Course;
use SteadfastCollective\Summit\Models\CourseBlock;
use SteadfastCollective\Summit\Tests\TestCase;

class CourseBlockTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
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
}
