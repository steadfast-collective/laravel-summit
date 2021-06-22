<?php

namespace SteadfastCollective\Summit\Tests;

use Illuminate\Support\Facades\Artisan;

class InstallSummitCommandTest extends TestCase
{
    /** @test */
    public function can_publish_config()
    {
        $this->artisan('summit:install')
            ->expectsConfirmation("Publish config?", 'yes')
            ->expectsConfirmation("Publish migrations?", 'no');
            // ->expectsConfirmation("Publish Nova Resources?", 'no');
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
