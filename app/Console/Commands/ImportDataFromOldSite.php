<?php

namespace App\Console\Commands;

use App\OldSite\Importers\NewsArticleImporter;
use App\OldSite\Importers\BrochureImporter;
use App\OldSite\Importers\ReviewImporter;
use App\OldSite\Importers\TestimonialImporter;
use App\OldSite\Importers\VideoImporter;
use Illuminate\Console\Command;

class ImportDataFromOldSite extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'old-site:import';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Imports data from the old site';

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
        $this->importVideos();
        $this->importArticles();
        $this->importReviews();
        $this->importTestimonials();
        $this->importBrochures();
    }

    private function importArticles(): void
    {
        $this->info('Importing News Articles');
        $articleImporter = new NewsArticleImporter($this);
        if ($articleImporter->call()) {
            $this->info('News Articles imported');
        } else {
            $this->error('News Article import failed');
        }
    }

    private function importBrochures(): void
    {
        $this->info('Importing Brochures');
        $brochureImporter = new BrochureImporter($this);
        if ($brochureImporter->call()) {
            $this->info('Brochures imported');
        } else {
            $this->error('Brochure import failed');
        }
    }

    private function importReviews(): void
    {
        $this->info('Importing Reviews');
        $reviewImporter = new ReviewImporter($this);
        if ($reviewImporter->call()) {
            $this->info('Reviews imported');
        } else {
            $this->error('Review import failed');
        }
    }

    private function importTestimonials(): void
    {
        $this->info('Importing Testimonials');
        $testimonialImporter = new TestimonialImporter($this);
        if ($testimonialImporter->call()) {
            $this->info('Testimonials imported');
        } else {
            $this->error('Testimonial import failed');
        }
    }

    private function importVideos(): void
    {
        $this->info('Importing Videos');
        $videoImporter = new VideoImporter($this);
        if ($videoImporter->call()) {
            $this->info('Videos imported');
        } else {
            $this->error('Video import failed');
        }
    }
}
