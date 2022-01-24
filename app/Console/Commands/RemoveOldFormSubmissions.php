<?php

namespace App\Console\Commands;

use App\Models\FormSubmission;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Throwable;

class RemoveOldFormSubmissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'data-clean-up:remove-old-form-submissions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Removes form submissions created over 30 days ago';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {
            $this->info('Fetching form submissions');

            $formSubmissions = FormSubmission::whereDate('created_at', '<=', Carbon::now()->subDays(30))->get();

            $this->info('Deleting form submissions');

            $formSubmissions->map(function (FormSubmission $formSubmission) {
                $formSubmission->delete();
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
