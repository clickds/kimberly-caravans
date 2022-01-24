<?php

namespace App\OldSite\Importers;

use App\OldSite\Models\NewsArticle as OldSiteArticle;
use App\Models\Article as NewSiteArticle;
use App\Models\ArticleCategory;
use App\Models\CaravanRange;
use App\Models\Dealer;
use App\Models\MotorhomeRange;
use App\Models\Page;
use App\Services\Pageable\ArticlePageSaver;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class NewsArticleImporter extends BaseImporter
{
    private Collection $categories;

    public const CATEGORY_NAMES = [
        'Motorhomes',
        'Caravans',
        'Shows and Events',
        'Featured Reviews',
    ];

    public function call(?Carbon $importFromDate = null): bool
    {
        DB::beginTransaction();
        try {
            $this->removeExistingArticles();
            $this->import($importFromDate);
            DB::commit();
            return true;
        } catch (Throwable $e) {
            DB::rollBack();
            Log::error($e);
            return false;
        }
    }

    private function import(?Carbon $importFromDate): void
    {
        $categories = collect();
        foreach (self::CATEGORY_NAMES as $categoryName) {
            $category = ArticleCategory::firstOrCreate([
                'name' => $categoryName,
            ]);
            $categories->push($category);
        }
        $this->categories = $categories;

        if (is_null($importFromDate)) {
            $importFromDate = Carbon::now()->subYears(2);
        }

        OldSiteArticle::with([
            'panels' => function ($query) {
                return $query->importable()->orderBy('sort_order', 'asc');
            }
        ])->where('created_at', '>=', $importFromDate)->chunk(200, function ($oldSiteArticles) {
            foreach ($oldSiteArticles as $oldSiteArticle) {
                $this->importArticle($oldSiteArticle);
            }
        });
    }

    private function removeExistingArticles(): void
    {
        // Done this way so that associated objects like media and pages get deleted
        foreach (NewSiteArticle::cursor() as $article) {
            $article->delete();
        };
    }

    private function importArticle(OldSiteArticle $oldSiteArticle): void
    {
        $this->outputInfo('Importing old article: ' . $oldSiteArticle->id);
        $newArticle = $this->createArticle($oldSiteArticle);
        $this->outputInfo('New Article: ' . $newArticle->id);
        $newArticle->sites()->attach($this->getDefaultSite());
        $this->attachImage($newArticle, $oldSiteArticle);
        $this->attachCategories($newArticle, $oldSiteArticle);
        $this->attachCaravanRanges($newArticle, $oldSiteArticle);
        $this->attachMotorhomeRanges($newArticle, $oldSiteArticle);
        $page = $this->createPage($newArticle);
        if (is_null($page)) {
            return;
        }
        $this->importPanels($page, $oldSiteArticle->panels);
    }

    private function createPage(NewSiteArticle $newSiteArticle): ?Page
    {
        $site = $this->getDefaultSite();
        if (is_null($site)) {
            return null;
        }
        $saver = new ArticlePageSaver($newSiteArticle, $site);
        $saver->call();
        return $newSiteArticle->pages()->first();
    }

    private function attachCategories(NewSiteArticle $newSiteArticle, OldSiteArticle $oldSiteArticle): void
    {
        $oldCategoryNames = $oldSiteArticle->mediaCategories()->pluck('name');
        foreach ($this->categories as $category) {
            switch ($category->name) {
                case 'Caravans':
                    $items = $oldCategoryNames->filter(function ($name) {
                        return strpos(strtolower($name), 'caravan') !== false;
                    });
                    if ($items->count() > 0) {
                        $newSiteArticle->articleCategories()->attach($category);
                    }
                    break;
                case 'Motorhomes':
                    $items = $oldCategoryNames->filter(function ($name) {
                        return strpos(strtolower($name), 'motorhome') !== false;
                    });
                    if ($items->count() > 0) {
                        $newSiteArticle->articleCategories()->attach($category);
                    }
                    break;
                default:
                    $items = $oldCategoryNames->filter(function ($name) use ($category) {
                        return strpos(strtolower($name), strtolower($category->name)) !== false;
                    });
                    if ($items->count() > 0) {
                        $newSiteArticle->articleCategories()->attach($category);
                    }
                    break;
            }
        }
    }

    private function attachCaravanRanges(NewSiteArticle $newSiteArticle, OldSiteArticle $oldSiteArticle): void
    {
        $oldCategoryNames = $oldSiteArticle->mediaCategories()->pluck('name');
        $caravanRanges = CaravanRange::whereIn('name', $oldCategoryNames)->pluck('id');
        $newSiteArticle->caravanRanges()->attach($caravanRanges);
    }

    private function attachMotorhomeRanges(NewSiteArticle $newSiteArticle, OldSiteArticle $oldSiteArticle): void
    {
        $oldCategoryNames = $oldSiteArticle->mediaCategories()->pluck('name');
        $motorhomeRanges = MotorhomeRange::whereIn('name', $oldCategoryNames)->pluck('id');
        $newSiteArticle->motorhomeRanges()->attach($motorhomeRanges);
    }

    private function attachImage(NewSiteArticle $newSiteArticle, OldSiteArticle $oldSiteArticle): void
    {
        $imageFileName = $oldSiteArticle->image_file_name;
        if (empty($imageFileName)) {
            return;
        }
        $imageUrl = $this->calculateAttachmentUrl(
            'NewsArticle',
            'image',
            $oldSiteArticle->id,
            $imageFileName
        );
        $newSiteArticle->addMediaFromUrl($imageUrl)->usingFileName($imageFileName)
            ->toMediaCollection('image');
    }

    private function createArticle(OldSiteArticle $oldSiteArticle): NewSiteArticle
    {
        $categories = $oldSiteArticle->mediaCategories()->pluck('name');
        $dealerIds = Dealer::whereIn('name', $categories)->pluck('id');
        $dealerId = $dealerIds->first();

        return NewSiteArticle::forceCreate([
            'dealer_id' => $dealerId,
            'title' => $oldSiteArticle->headline,
            'excerpt' => $oldSiteArticle->teaser,
            'date' => $oldSiteArticle->date_live,
            'published_at' => $oldSiteArticle->date_live,
            'created_at' => $oldSiteArticle->created_at,
            'updated_at' => $oldSiteArticle->updated_at,
            'type' => 'Both',
        ]);
    }
}
