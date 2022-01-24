<?php

namespace App\Console\Commands;

use App\OldSite\Importers\NewsArticleImporter;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ImportNewsFromOldSite extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'old-site:import-news {importFromDate}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Imports news from the old site';

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
        $importFromDate = Carbon::parse($this->argument('importFromDate'));

        $this->info('Importing News Articles');

        $articleImporter = new NewsArticleImporter($this);

        if ($articleImporter->call($importFromDate)) {
            $this->info('News Articles imported');
        } else {
            $this->error('News Article import failed');
        }
    }
}
