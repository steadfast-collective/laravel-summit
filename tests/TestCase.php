<?php

namespace SteadfastCollective\Summit\Tests;

use Illuminate\Foundation\Auth\User;
use Orchestra\Testbench\TestCase as OrchestraTestCase;
use SteadfastCollective\Summit\SummitServiceProvider;

class TestCase extends OrchestraTestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $this->loadLaravelMigrations(['--database' => 'sqlite']);
        $this->setUpDatabase();
    }

    protected function getPackageProviders($app)
    {
        return [
            SummitServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('auth.providers.users.model', User::class);
        $app['config']->set('database.default', 'sqlite');
        $app['config']->set('database.connections.sqlite', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);
        $app['config']->set('app.key', 'base64:6Cu/ozj4gPtIjmXjr8EdVnGFNsdRqZfHfVjQkmTlg4Y=');
    }

    protected function setUpDatabase()
    {
        include_once __DIR__ . '/../database/migrations/create_courses_table.php.stub';

        (new \CreateCoursesTable())->up();
    }
}
