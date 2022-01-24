<?php

namespace App\Console\Commands;

use App\Services\Importers\StockFeed\Fetcher;
use App\Services\Importers\StockFeed\Importer;
use GuzzleHttp\Client;
use Illuminate\Console\Command;

class ImportStockItemsFromFeed extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'feed:import-stock {--test : Whether to import from the test feed}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Imports stock items from the saxon feed';

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
        $this->info('Importing from feed');
        $baseUri = 'https://marquisfeed.saxon.co.uk';
        if ($this->option('test')) {
            $baseUri = 'https://marquisfeed-test.saxon.co.uk';
        }
        $client = new Client([
            'base_uri' => $baseUri,
            'verify' => false,
        ]);
        $fetcher = new Fetcher($client);
        $importer = new Importer($fetcher);
        $importer->call();
        $this->info('Completed importing from feed');
    }
}
