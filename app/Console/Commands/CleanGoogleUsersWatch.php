<?php

namespace App\Console\Commands;

use App\GoogleGSuite\GoogleDirectory;
use App\Models\GoogleWatch;
use Config;
use Illuminate\Console\Command;

/**
 * Class CleanGoogleuserswatch.
 *
 * @package App\Console\Commands
 */
class CleanGoogleuserswatch extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'google-users:clean-watch {tenant}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean google users watch';

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
     * @return mixed
     */
    public function handle()
    {
        apply_tenant($this->argument('tenant'));
        GoogleWatch::truncate();
        $this->info('Table google_watches truncated ok!');
    }
}
