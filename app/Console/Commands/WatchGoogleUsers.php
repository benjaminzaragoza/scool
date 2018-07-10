<?php

namespace App\Console\Commands;

use App\GoogleGSuite\GoogleDirectory;
use Config;
use Illuminate\Console\Command;

/**
 * Class WatchGoogleUsers.
 *
 * @package App\Console\Commands
 */
class WatchGoogleUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'google-users:watch {tenant} {domain?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Watch google users';

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
        $directory = new GoogleDirectory();
        $url = 'https://' . $this->argument('tenant') . '.';
        if ($this->argument('domain')) {
            $url = $url . $this->argument('domain');
        } else {
            $url = $url . Config::get('app.domain');
        }
        $directory->watch($url);
    }
}
