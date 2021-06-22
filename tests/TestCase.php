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
}
