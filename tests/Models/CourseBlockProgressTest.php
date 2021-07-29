<?php

namespace SteadfastCollective\Summit\Tests\Models;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use SteadfastCollective\Summit\Models\Course;
use SteadfastCollective\Summit\Tests\TestCase;

class CourseBlockProgressTest extends TestCase
{
    use RefreshDatabase;

    protected $course;
    protected $courseBlock;

    public function setUp(): void
    {
        parent::setUp();

        $this->course = Course::create([
            'name' => 'Statamic Crash Course',
            'slug' => 'statamic-crash-course',
        ]);

        $this->courseBlock = $this->course->courseBlocks()->create([
            'title' => 'Collections & Entries',
            'slug' => 'collections-and-entries',
            'estimated_length' => 50,
        ]);
    }

    /** @test */
    public function can_get_time_left()
    {
        $videoOne = $this->courseBlock->videos()->create([
            'video_duration' => 25,
        ]);

        $videoTwo = $this->courseBlock->videos()->create([
            'video_duration' => 25,
        ]);

        $user = User::create([
            'name' => 'Test',
            'email' => 'test@steadfastcollective.com',
            'password' => Hash::make('password'),
        ]);

        $this->courseBlock->users()->attach($user, [
            'started_at' => now()->subMinutes(5),
            'progress' => 20,
        ]);

        $timeLeft = $this->courseBlock->users->first()->pivot->time_left;

        $this->assertSame($timeLeft, '30 seconds');
    }

    /** @test */
    public function progress_belongs_to_course_block()
    {
        $user = User::create([
            'name' => 'Test',
            'email' => 'test@steadfastcollective.com',
            'password' => Hash::make('password'),
        ]);

        $this->courseBlock->users()->attach($user, [
            'started_at' => now()->subMinutes(5),
            'progress' => 20,
        ]);

        $courseBlockProgress = $this->courseBlock->users()->latest()->first();

        $this->assertTrue($courseBlockProgress->is($user));
        $this->assertTrue($courseBlockProgress instanceof User);

        $this->assertSame((int) $courseBlockProgress->pivot->course_block_id, $this->course->id);
    }

    /** @test */
    public function progress_belongs_to_user()
    {
        $user = User::create([
            'name' => 'Test',
            'email' => 'test@steadfastcollective.com',
            'password' => Hash::make('password'),
        ]);

        $this->courseBlock->users()->attach($user, [
            'started_at' => now()->subMinutes(5),
            'progress' => 20,
        ]);

        $courseBlockProgress = $this->courseBlock->users()->latest()->first();

        $this->assertTrue($courseBlockProgress->is($user));
        $this->assertTrue($courseBlockProgress instanceof User);

        $this->assertSame((int) $courseBlockProgress->pivot->user_id, $user->id);
    }
}
