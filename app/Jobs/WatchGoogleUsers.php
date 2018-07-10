<?php

namespace App\Jobs;

use App\GoogleGSuite\GoogleDirectory;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

/**
 * Class WatchGoogleUsers
 * @package App\Jobs
 */
class WatchGoogleUsers implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $directory = new GoogleDirectory();
        $directory->watch();
    }
}
