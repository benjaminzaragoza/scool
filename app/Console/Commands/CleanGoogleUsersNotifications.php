<?php

namespace App\Console\Commands;

use App\GoogleGSuite\GoogleDirectory;
use App\Models\GoogleNotification;
use App\Models\GoogleWatch;
use Config;
use Illuminate\Console\Command;

/**
 * Class CleanGoogleUsersNotifications.
 *
 * @package App\Console\Commands
 */
class CleanGoogleUsersNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'google-users:clean-notifications {tenant}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean google users notifications';

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
        GoogleNotification::truncate();
        $this->info('Table google_notifications truncated ok!');
    }
}
