<?php

namespace App\Console\Commands;

use App\Models\VacancyApplication;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Throwable;

class RemoveOldVacancyApplications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'data-clean-up:remove-old-vacancy-applications';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Removes vacancy applications created over 30 days ago';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {
            $this->info('Fetching vacancy applications');

            $vacancyApplications = VacancyApplication::whereDate('created_at', '<=', Carbon::now()->subDays(30))->get();

            $this->info('Deleting vacancy applications');

            $vacancyApplications->map(function (VacancyApplication $vacancyApplication) {
                $vacancyApplication->delete();
            });

            $this->info('Done');

            return 0;
        } catch (Throwable $e) {
            Log::error($e);

            $this->error('An error occurred, please check the logs for details');

            return 1;
        }
    }
}
