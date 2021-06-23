<?php

namespace SteadfastCollective\Summit\Tests\Models;

use Illuminate\Foundation\Testing\RefreshDatabase;
use SteadfastCollective\Summit\Models\Course;
use SteadfastCollective\Summit\Tests\TestCase;

class CourseTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_get_published_courses()
    {
        $publishedCourse = Course::create([
            'name' => 'How to use Stripe Checkout',
            'slug' => 'how-to-use-stripe-checkout',
            'description' => 'This is a course about Stripe Checkout.',
            'estimated_length' => 3600,
            'start_date' => now()->addWeeks(2),
            'publish_date' => now()->subDay(),
        ]);

        $unPublishedCourse = Course::create([
            'name' => 'How to use Stripe Connect',
            'slug' => 'how-to-use-stripe-connect',
            'description' => 'This is a course about Stripe Connect.',
            'estimated_length' => 3600,
            'start_date' => now()->addWeeks(2),
            'publish_date' => null,
        ]);

        $this->assertCount(1, $publishedCourses = Course::published()->get());
        $this->assertSame($publishedCourse->id, $publishedCourses->first()->id);
    }

    /** @test */
    public function can_get_current_courses()
    {
        $currentCourse = Course::create([
            'name' => 'How to use Stripe Checkout',
            'slug' => 'how-to-use-stripe-checkout',
            'description' => 'This is a course about Stripe Checkout.',
            'estimated_length' => 3600,
            'start_date' => now(),
            'publish_date' => now(),
        ]);

        $futureCourse = Course::create([
            'name' => 'How to use Stripe Connect',
            'slug' => 'how-to-use-stripe-connect',
            'description' => 'This is a course about Stripe Connect.',
            'estimated_length' => 3600,
            'start_date' => now()->addWeeks(2),
            'publish_date' => now(),
        ]);

        $this->assertCount(1, $currentCourses = Course::started()->get());
        $this->assertSame($currentCourse->id, $currentCourses->first()->id);
    }
}
