<?php

namespace App\Services\Search\Page\DataProviders;

use App\Models\Video;
use UnexpectedValueException;

final class VideoDataProvider extends BaseDataProvider
{
    public const TYPE = 'Video';

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
        $video = $this->page->pageable;

        if (is_null($video) || !is_a($video, Video::class)) {
            throw new UnexpectedValueException('Expected pageable to be an instance of Video');
        }

        return $video->excerpt;
    }
}
