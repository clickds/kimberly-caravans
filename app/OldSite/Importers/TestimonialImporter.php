<?php

namespace App\OldSite\Importers;

use App\OldSite\Models\Testimonial as OldSiteTestimonial;
use App\Models\Testimonial as NewSiteTestimonial;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class TestimonialImporter extends BaseImporter
{
    public function call(): bool
    {
        DB::beginTransaction();
        try {
            $this->removeExistingTestimonials();
            $this->import();
            DB::commit();
            return true;
        } catch (Throwable $e) {
            DB::rollBack();
            Log::error($e);
            return false;
        }
    }

    private function import(): void
    {
        OldSiteTestimonial::chunk(200, function ($oldSiteTestimonials) {
            foreach ($oldSiteTestimonials as $oldSiteTestimonial) {
                $this->importTestimonial($oldSiteTestimonial);
            }
        });
    }

    private function removeExistingTestimonials(): void
    {
        // Done this way so that associated objects like media and pages get deleted
        foreach (NewSiteTestimonial::cursor() as $video) {
            $video->delete();
        };
    }

    private function importTestimonial(OldSiteTestimonial $oldSiteTestimonial): void
    {
        $this->outputInfo('Importing old testimonial: ' . $oldSiteTestimonial->id);
        $newTestimonial = $this->createTestimonial($oldSiteTestimonial);
        $this->outputInfo('New testimonial: ' . $newTestimonial->id);
        $newTestimonial->sites()->attach($this->getDefaultSite());
    }

    private function createTestimonial(OldSiteTestimonial $oldSiteTestimonial): NewSiteTestimonial
    {
        return NewSiteTestimonial::forceCreate([
            'published_at' => $oldSiteTestimonial->date_live,
            'created_at' => $oldSiteTestimonial->created_at,
            'updated_at' => $oldSiteTestimonial->updated_at,
            'position' => $oldSiteTestimonial->sort_order,
            'name' => $oldSiteTestimonial->name,
            'content' => $oldSiteTestimonial->description,
        ]);
    }
}
