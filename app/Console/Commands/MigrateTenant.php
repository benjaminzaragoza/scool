<?php

namespace App\Console\Commands;

use App\Console\Commands\Traits\MigrateTenants;
use Illuminate\Console\Command;

/**
 * Class MigrateTenant.
 *
 * @package App\Console\Commands
 */
class MigrateTenant extends Command
{
    use MigrateTenants;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate:tenant {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run the tenant database migrations';

    /**
     * Command to execute.
     *
     * @return string
     */
    private function command() {
        return 'migrate';
    }

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
        $this->configureTenant();
        $this->run_command();
    }
}
