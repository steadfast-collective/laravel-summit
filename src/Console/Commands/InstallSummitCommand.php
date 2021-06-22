<?php

namespace SteadfastCollective\Summit\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

class InstallSummitCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'summit:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install Summit';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        if ($this->confirm("Publish config?")) {
            Artisan::call("vendor:publish", [
                '--tag' => 'summit-config',
            ]);
        }

        if ($this->confirm("Publish migrations?")) {
            Artisan::call("vendor:publish", [
                '--tag' => 'summit-migrations',
            ]);
        }

        if (class_exists('Laravel\Nova\Nova') && $this->confirm("Publish Nova Resources?")) {
            File::copyDirectory(__DIR__.'/../../Nova', base_path('app/Nova'));
        }

        $this->info("Publishing complete.");
    }
}
