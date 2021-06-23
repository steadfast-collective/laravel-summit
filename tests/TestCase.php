<?php

namespace SteadfastCollective\Summit\Tests;

use Illuminate\Foundation\Auth\User;
use Orchestra\Testbench\TestCase as OrchestraTestCase;
use SteadfastCollective\Summit\SummitServiceProvider;

class TestCase extends OrchestraTestCase
{
    protected function getPackageProviders($app)
    {
        return [
            SummitServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('auth.providers.users.model', User::class);
    }
}
