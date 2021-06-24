<?php

namespace SteadfastCollective\Summit\Tests;

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
        require_once __DIR__.'/__fixtures__/app/Models/User.php';
        require_once __DIR__.'/__fixtures__/database/migrations/create_users_table.php';

        $app['config']->set('auth.providers.users.model', \App\Models\User::class);

        (new \CreateUsersTable)->up();
    }
}
