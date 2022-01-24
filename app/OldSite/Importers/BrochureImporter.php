<?php

namespace App\OldSite\Importers;

use App\OldSite\Models\Brochure as OldSiteBrochure;
use App\OldSite\Models\BrochureGroup as OldSiteBrochureGroup;
use App\Models\Brochure as NewSiteBrochure;
use App\Models\BrochureGroup as NewSiteBrochureGroup;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class BrochureImporter extends BaseImporter
{
    public function call(): bool
    {
        DB::beginTransaction();
        try {
            $this->removeExistingBrochures();
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
        OldSiteBrochureGroup::chunk(200, function ($oldSiteBrochureGroups) {
            foreach ($oldSiteBrochureGroups as $oldBrochureGroup) {
                $this->importBrochureGroup($oldBrochureGroup);
            }
        });
    }

    private function removeExistingBrochures(): void
    {
        // Done this way so that associated objects like media and pages get deleted
        foreach (NewSiteBrochure::cursor() as $brochure) {
            $brochure->delete();
        }
        foreach (NewSiteBrochureGroup::cursor() as $brochureGroup) {
            $brochureGroup->delete();
        };
    }

    private function importBrochureGroup(OldSiteBrochureGroup $oldSiteBrochureGroup): void
    {
        $this->outputInfo('Importing old brochure group: ' . $oldSiteBrochureGroup->id);
        $newBrochureGroup = $this->createBrochureGroup($oldSiteBrochureGroup);
        $this->outputInfo('New brochure group: ' . $newBrochureGroup->id);
        foreach ($oldSiteBrochureGroup->brochures as $oldSiteBrochure) {
            $this->importBrochure($newBrochureGroup, $oldSiteBrochure);
        }
    }

    private function importBrochure(NewSiteBrochureGroup $newSiteBrochureGroup, OldSiteBrochure $oldSiteBrochure): void
    {
        $this->outputInfo('Importing old brochure: ' . $oldSiteBrochure->id);
        $newBrochure = $this->createBrochure($newSiteBrochureGroup, $oldSiteBrochure);
        $this->outputInfo('New brochure: ' . $newBrochure->id);
        $this->attachImage($newBrochure, $oldSiteBrochure);
        $this->attachBrochureFile($newBrochure, $oldSiteBrochure);
    }

    private function createBrochure(
        NewSiteBrochureGroup $newSiteBrochureGroup,
        OldSiteBrochure $oldSiteBrochure
    ): NewSiteBrochure {
        // Done this way as it would appear force create means that the foreign key isn't set
        $site = $this->getDefaultSite();
        $siteId = $site ? $site->id : null;
        return NewSiteBrochure::forceCreate([
            'group_id' => $newSiteBrochureGroup->id,
            'title' => $oldSiteBrochure->name,
            'url' => $oldSiteBrochure->url,
            'site_id' => $siteId,
            'published_at' => Carbon::now(),
            'created_at' => $oldSiteBrochure->created_at,
            'updated_at' => $oldSiteBrochure->updated_at,
        ]);
    }

    private function attachImage(NewSiteBrochure $newSiteBrochure, OldSiteBrochure $oldSiteBrochure): void
    {
        $imageFileName = $oldSiteBrochure->thumb_file_name;
        if (empty($imageFileName)) {
            return;
        }
        $imageUrl = $this->calculateAttachmentUrl(
            'Brochure',
            'thumb',
            $oldSiteBrochure->id,
            $imageFileName
        );
        $newSiteBrochure->addMediaFromUrl($imageUrl)
            ->usingFileName($imageFileName)->toMediaCollection('image');
    }

    private function attachBrochureFile(NewSiteBrochure $newSiteBrochure, OldSiteBrochure $oldSiteBrochure): void
    {
        $pdfFileName = $oldSiteBrochure->pdf_file_name;
        if (empty($pdfFileName)) {
            return;
        }
        $pdfUrl = $this->calculateAttachmentUrl(
            'Brochure',
            'pdf',
            $oldSiteBrochure->id,
            $pdfFileName
        );
        $newSiteBrochure->addMediaFromUrl($pdfUrl)
            ->usingFileName($pdfFileName)->toMediaCollection('brochure_file');
    }


    private function createBrochureGroup(OldSiteBrochureGroup $oldSiteBrochureGroup): NewSiteBrochureGroup
    {
        return NewSiteBrochureGroup::forceCreate([
            'name' => $oldSiteBrochureGroup->name,
            'created_at' => $oldSiteBrochureGroup->created_at,
            'position' => $oldSiteBrochureGroup->sort_order,
        ]);
    }
}
