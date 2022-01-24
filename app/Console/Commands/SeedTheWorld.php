<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class SeedTheWorld extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'marquis:seed-the-world';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Seeds the full marquis site';

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
        if (!$this->confirm('This command drops all tables and re-migrates. Do you wish to continue?')) {
            return;
        }
        $this->call('migrate:fresh');
        $this->call('db:seed', [
            '--class' => 'ActualContentSeeder',
        ]);
    }
}
