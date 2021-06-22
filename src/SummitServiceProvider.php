<?php

namespace SteadfastCollective\Summit;

use Illuminate\Support\ServiceProvider;

class SummitServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->registerPublishables();

        // Registering package commands.
        // $this->commands([]);
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/summit.php', 'summit');

        // Register the main class to use with the facade
        $this->app->singleton('summit', function () {
            return new Summit;
        });
    }

    protected function registerPublishables() : void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/summit.php' => config_path('summit.php'),
            ], 'summit-config');

            if (! class_exists('CreateCoursesTable')) {
                $this->publishes([
                    __DIR__.'/../database/migrations/create_courses_table.php.stub' => database_path('migrations/'.date('Y_m_d_His', time()).'_create_courses_table.php'),
                ], 'migrations');
            }

            if (! class_exists('CreateVideosTable')) {
                $this->publishes([
                    __DIR__.'/../database/migrations/create_videos_table.php.stub' => database_path('migrations/'.date('Y_m_d_His', time()).'_create_videos_table.php'),
                ], 'migrations');
            }

            if (! class_exists('CreateCourseBlocksTable')) {
                $this->publishes([
                    __DIR__.'/../database/migrations/create_course_blocks_table.php.stub' => database_path('migrations/'.date('Y_m_d_His', time()).'_create_course_blocks_table.php'),
                ], 'migrations');
            }

            if (! class_exists('CreateCourseBlockUserTable')) {
                $this->publishes([
                    __DIR__.'/../database/migrations/create_course_block_user_table.php.stub' => database_path('migrations/'.date('Y_m_d_His', time()).'_create_course_block_user_table.php'),
                ], 'migrations');
            }
        }
    }
}
