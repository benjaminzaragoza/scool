<?php

namespace App\Console\Commands;

use App\Console\Commands\Traits\MigrateTenants;
use App\Tenant;
use Illuminate\Console\Command;

class MigrateTenantAll extends Command
{
    use MigrateTenants;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate:tenant_all';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run the database migrations in all tenants';

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
        return 'migrate';
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $tenants = Tenant::all();

        foreach ($tenants as $tenant) {
            $this->info('Running tenant: ' . $tenant->subdomain . ' ...');

            $this->connectAndConfigureTenant($tenant);
            $this->run_command();
        }
    }
}
