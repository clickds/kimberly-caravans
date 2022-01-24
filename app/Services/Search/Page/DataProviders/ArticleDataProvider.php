<?php

namespace App\Services\Search\Page\DataProviders;

use App\Models\Article;
use UnexpectedValueException;

final class ArticleDataProvider extends BaseDataProvider
{
    public const TYPE = 'Article';

    protected function getContentData(): array
    {
        return [self::KEY_CONTENT => $this->generateContentString()];
    }

    protected function getTypeData(): array
    {
        return [self::KEY_TYPE => self::TYPE];
    }

    private function generateContentString(): string
    {
        $article = $this->page->pageable;

        if (is_null($article) || !is_a($article, Article::class)) {
            throw new UnexpectedValueException('Expected pageable to be an instance of Article');
        }

        return $article->excerpt;
    }
}
