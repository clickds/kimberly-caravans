<?php

namespace App\OldSite\Importers;

use App\Models\Area;
use App\Models\Page;
use App\Models\Panel;
use App\Models\Site;
use App\Models\Video;
use App\OldSite\Models\Panel as OldPanel;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

abstract class BaseImporter
{
    private Command $command;

    public function __construct(Command $command)
    {
        $this->command = $command;
    }

    abstract public function call(): bool;

    protected function convertIntegerToString(int $integer): string
    {
        return strval($integer);
    }

    /**
     * https://www.rubydoc.info/github/thoughtbot/paperclip/Paperclip%2FInterpolations:id_partition
     */
    protected function calculateIdPartition(int $modelId): string
    {
        $idString = $this->convertIntegerToString($modelId);
        $zeroPaddedString = str_pad($idString, 9, "0", STR_PAD_LEFT);
        $parts = str_split($zeroPaddedString, 3);
        return implode('/', $parts);
    }

    /**
     * https://www.rubydoc.info/gems/paperclip/Paperclip%2FClassMethods:has_attached_file
     *
     * The default value is “/system/:class/:attachment/:id_partition/:style/:filename
     *
     * Ruby class name would just be the base class name e.g. NewsArticle not App/Models/NewsArticle
     * It looks like the attachment is pluralized
     * For style we'll use original to get the original uploaded image
     */
    protected function calculateAttachmentUrl(
        string $rubyClassName,
        string $attachmentName,
        int $modelId,
        string $filename
    ): string {
        $pluralizedClassName = Str::plural(Str::snake($rubyClassName));
        $idPartition = $this->calculateIdPartition($modelId);
        $pluralizedAttachementName = Str::plural($attachmentName);
        $filename = urlencode($filename);
        $parts = [
            'https://orbit.brightbox.com/v1/acc-jqzwj/Marquis-Leisure',
            $pluralizedClassName,
            $pluralizedAttachementName,
            $idPartition,
            'original',
            $filename,
        ];

        return implode('/', $parts);
    }

    protected function getDefaultSite(): ?Site
    {
        return Site::where('is_default', true)->first();
    }


    protected function importPanels(Page $page, Collection $panels): void
    {
        $groupedByAreaName = $panels->groupBy('area');
        foreach ($groupedByAreaName as $areaName => $panels) {
            $area = $this->createArea($page, $areaName);
            foreach ($panels as $panel) {
                switch ($panel->panel_type) {
                    case 'Videos':
                        $this->importVideoPanel($area, $panel);
                        break;
                    case 'RichText':
                        $this->importStandardPanel($area, $panel);
                        break;
                    default:
                        break;
                }
            }
        }
    }

    protected function importVideoPanel(Area $area, OldPanel $oldSitePanel): void
    {
        // The name is unique not because of any constraints but by luck
        $oldSiteVideo = $oldSitePanel->video;
        if (is_null($oldSiteVideo)) {
            return;
        }
        $video = Video::where('title', $oldSiteVideo->name)->first();
        if (is_null($video)) {
            return;
        }
        $area->panels()->create([
            'name' => $oldSitePanel->name,
            'position' => $oldSitePanel->sort_order,
            'type' => Panel::TYPE_VIDEO,
            'vertical_positioning' => Panel::POSITION_STRETCH,
            'content' => $this->stripInlineStyles($oldSitePanel->copy),
            'live' => true,
            'published_at' => Carbon::now(),
            'featureable_type' => Video::class,
            'featureable_id' => $video->id,
        ]);
    }

    protected function importStandardPanel(Area $area, OldPanel $oldSitePanel): void
    {
        $area->panels()->create([
            'area_id' => $area->id,
            'name' => $oldSitePanel->name,
            'position' => $oldSitePanel->sort_order,
            'type' => Panel::TYPE_STANDARD,
            'vertical_positioning' => Panel::POSITION_STRETCH,
            'content' => $this->stripInlineStyles($oldSitePanel->copy),
            'live' => true,
            'published_at' => Carbon::now(),
        ]);
    }

    protected function stripInlineStyles(string $content): string
    {
        $newContent = preg_replace('/ style=("|\')(.*?)("|\')/', '', $content);
        if ($newContent) {
            return $newContent;
        }
        return $content;
    }

    /**
     * The only area names on the old site are Primary, Secondary and Hidden.
     * We're ignoring hidden
     */
    protected function createArea(Page $page, string $name): Area
    {
        return $page->areas()->create([
            'page_id' => $page->id,
            'name' => $name,
            'holder' => $name,
            'columns' => 1,
            'live' => true,
            'position' => 0,
            'published_at' => Carbon::now(),
            'background_colour' => 'white',
            'heading_text_alignment' => Area::TEXT_ALIGNMENT_LEFT,
            'width' => Area::STANDARD_WIDTH,
        ]);
    }

    protected function outputInfo(string $message): void
    {
        $this->command->info($message);
    }

    protected function outputError(string $message): void
    {
        $this->command->error($message);
    }
}
