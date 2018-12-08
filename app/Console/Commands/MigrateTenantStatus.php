<?php

namespace App\Console\Commands;

use App\Console\Commands\Traits\MigrateTenants;
use Illuminate\Console\Command;

class MigrateTenantStatus extends Command
{
    use MigrateTenants;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate:tenant:status {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Show the status of each migration in tenant database';

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
     * Command to execute.
     *
     * @return string
     */
    private function command() {
        return 'migrate:status';
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
