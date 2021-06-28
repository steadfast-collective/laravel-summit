<?php

namespace SteadfastCollective\Summit\Tests\Models;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use SteadfastCollective\Summit\Models\Course;
use SteadfastCollective\Summit\Tests\TestCase;

class CourseTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function courses_have_a_published_scope()
    {
        $publishedCourse = Course::create([
            'name' => 'How to use Stripe Checkout',
            'slug' => 'how-to-use-stripe-checkout',
            'description' => 'This is a course about Stripe Checkout.',
            'start_date' => now()->addWeeks(2),
            'publish_date' => now()->subDay(),
        ]);

        $unPublishedCourse = Course::create([
            'name' => 'How to use Stripe Connect',
            'slug' => 'how-to-use-stripe-connect',
            'description' => 'This is a course about Stripe Connect.',
            'start_date' => now()->addWeeks(2),
            'publish_date' => null,
        ]);

        $this->assertCount(1, $publishedCourses = Course::published()->get());
        $this->assertSame($publishedCourse->id, $publishedCourses->first()->id);
    }

    /** @test */
    public function courses_have_a_started_scope()
    {
        $startedCourse = Course::create([
            'name' => 'How to use Stripe Checkout',
            'slug' => 'how-to-use-stripe-checkout',
            'description' => 'This is a course about Stripe Checkout.',
            'start_date' => now(),
            'publish_date' => now(),
        ]);

        $futureCourse = Course::create([
            'name' => 'How to use Stripe Connect',
            'slug' => 'how-to-use-stripe-connect',
            'description' => 'This is a course about Stripe Connect.',
            'start_date' => now()->addWeeks(2),
            'publish_date' => now(),
        ]);

        $this->assertCount(1, $startedCourses = Course::started()->get());
        $this->assertSame($startedCourse->id, $startedCourses->first()->id);
    }

    /** @test */
    public function courses_can_have_course_blocks()
    {
        $course = Course::create([
            'name' => 'How to use Stripe Checkout',
            'slug' => 'how-to-use-stripe-checkout',
            'description' => 'This is a course about Stripe Checkout.',
            'start_date' => now(),
            'publish_date' => now(),
        ]);

        $courseBlock = $course->courseBlocks()->create([
            'title' => 'Installing Stripe CLI',
            'description' => 'How to get started with Stripe development using the Stripe CLI.',
            'download_file_path' => null,
            'order' => 1,
            'available_from' => now(),
        ]);

        $this->assertDatabaseHas('course_blocks', [
            'course_id' => $course->id,
            'title' => $courseBlock->title,
        ]);
    }

    /** @test */
    public function can_get_readable_estimated_length()
    {
        $course = Course::create([
            'name' => 'How to use Stripe Checkout',
            'slug' => 'how-to-use-stripe-checkout',
            'description' => 'This is a course about Stripe Checkout.',
            'start_date' => now(),
            'publish_date' => now(),
        ]);

        $course->courseBlocks()->create([
            'estimated_length' => 3600,
        ]);

        $this->assertSame($course->readable_estimated_length, '1 hour');
    }

    /** @test */
    public function can_get_progress_percentage()
    {
        $course = Course::create([
            'name' => 'How to use Stripe Billing',
            'slug' => 'how-to-use-stripe-billing',
            'description' => 'This is a course about Stripe Billing.',
            'start_date' => now(),
            'publish_date' => now(),
        ]);

        $courseBlockOne = $course->courseBlocks()->create([
            'title' => 'Creating products',
        ]);

        $courseBlockTwo = $course->courseBlocks()->create([
            'title' => 'Setting up for customers',
        ]);

        $user = User::create([
            'name' => 'Test',
            'email' => 'test@steadfastcollective.com',
            'password' => Hash::make('password'),
        ]);

        $courseBlockOne->users()->attach($user, [
            'started_at' => now(),
            'progress' => 25,
            'finished_at' => now(),
        ]);

        Auth::setUser($user);

        $progress = $course->progress_percentage;

        $this->assertIsString($progress);
        $this->assertSame($progress, '50%');
    }

    /** @test */
    public function can_get_progress_percentage_as_guest()
    {
        $course = Course::create([
            'name' => 'How to use Stripe Billing',
            'slug' => 'how-to-use-stripe-billing',
            'description' => 'This is a course about Stripe Billing.',
            'start_date' => now(),
            'publish_date' => now(),
        ]);

        $courseBlockOne = $course->courseBlocks()->create([
            'title' => 'Creating products',
        ]);

        $courseBlockTwo = $course->courseBlocks()->create([
            'title' => 'Setting up for customers',
        ]);

        $progress = $course->progress_percentage;

        $this->assertIsString($progress);
        $this->assertSame($progress, '0%');
    }

    /** @test */
    public function can_get_chapters_count_attribute()
    {
        $course = Course::create([
            'name' => 'How to use Stripe Billing',
            'slug' => 'how-to-use-stripe-billing',
            'description' => 'This is a course about Stripe Billing.',
            'start_date' => now(),
            'publish_date' => now(),
        ]);

        $courseBlockOne = $course->courseBlocks()->create([
            'title' => 'Creating products',
            'type' => 'CHAPTER',
        ]);

        $courseBlockTwo = $course->courseBlocks()->create([
            'title' => 'Setting up for customers',
            'type' => 'INTRO',
        ]);

        $chaptersCount = $course->chapters_count;

        $this->assertIsInt($chaptersCount);
        $this->assertSame($chaptersCount, 1);
    }
}
