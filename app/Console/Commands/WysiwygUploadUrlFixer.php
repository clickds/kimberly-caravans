<?php

namespace App\Console\Commands;

use App\Models\Panel;
use App\Services\Wysiwyg\UploadUrlReplacer;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Throwable;

class WysiwygUploadUrlFixer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'wysiwyg:fix-uploads';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fixes the upload url for images wysiwyg content';

    private array $errors = [];

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
        Panel::chunk(200, function ($panels) {
            foreach ($panels as $panel) {
                $this->fixPanel($panel);
            }
        });

        foreach ($this->errors as $error) {
            $this->error($error);
        }
    }

    private function fixPanel(Panel $panel): void
    {
        if ($panel->content) {
            try {
                $replacer = new UploadUrlReplacer($panel->content);
                $panel->content = $replacer->call();
                $panel->save();
            } catch (Throwable $e) {
                Log::error($e);
                $this->errors[] = 'Panel ' . $panel->id . ' content';
            }
        }

        if ($panel->read_more_content) {
            try {
                $replacer = new UploadUrlReplacer($panel->read_more_content);
                $panel->read_more_content = $replacer->call();
                $panel->save();
            } catch (Throwable $e) {
                Log::error($e);
                $this->errors[] = 'Panel ' . $panel->id . ' read more content';
            }
        }
    }
}
