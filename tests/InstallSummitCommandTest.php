<?php

namespace SteadfastCollective\Summit\Tests;

use Illuminate\Support\Facades\Artisan;
use Orchestra\Testbench\TestCase;

class InstallSummitCommandTest extends TestCase
{
    /** @test */
    public function can_publish_config()
    {
        // dd();

        $this->artisan('summit:install')
            ->expectsConfirmation("Publish config?", 'yes')
            ->expectsConfirmation("Publish migrations?", 'no')
            ->expectsConfirmation("Publish Nova Resources?", 'no')
            ->expectsOutput("Publishing complete.");

        // dd(base_path());

        // dd(
        //     // Artisan::all()
        //     // $this->app->
        // );
    }

    /** @test */
    public function can_publish_migrations()
    {
        //
    }

    /** @test */
    public function can_publish_nova_resources()
    {
        //
    }

    /** @test */
    public function can_publish_everything()
    {
        //
    }
}
