<?php

namespace App\Services\Wysiwyg;

use DOMDocument;
use DOMElement;
use Config;
use Illuminate\Support\Collection;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class UploadUrlReplacer
{
    private string $htmlContent;
    private string $disk;

    public function __construct(string $htmlContent, string $disk = 'public')
    {
        $this->htmlContent = $htmlContent;
        $this->disk = $disk;
    }

    /**
     * Done this way because the wysiwyg content isn't technically valid html and DOMDocument seems to try to fix
     * e.g. '<figure></figure><ul></ul>' becomes '<figure><ul></ul></figure>'
     */
    public function call(): string
    {
        libxml_use_internal_errors(true);
        $dom = new DOMDocument();
        $dom->loadHTML($this->htmlContent, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        $imageTags = $dom->getElementsByTagName('img');

        $newHtmlContent = $this->htmlContent;

        foreach ($imageTags as $imageTag) {
            $beforeHtml = $dom->saveHTML($imageTag);
            $this->updateImageTag($imageTag);
            $afterHtml = $dom->saveHTML($imageTag);
            if ($beforeHtml && $afterHtml) {
                $newHtmlContent = str_replace($beforeHtml, $afterHtml, $newHtmlContent);
            }
        }
        libxml_use_internal_errors(false);

        return $newHtmlContent;
    }

    private function updateImageTag(DOMElement $imageTag): void
    {
        $imageSrc = $imageTag->getAttribute('src');
        $mediaId = $this->mediaId($imageSrc);
        $media = Media::findOrFail($mediaId);
        $responsiveUrlGenerator = new ResponsiveImageUrlsGenerator($media);
        $urls = $responsiveUrlGenerator->call();
        $urlCollection = collect($urls);
        $defaultUrl = $urlCollection->get('default');
        $urlsWithWidths = $urlCollection->filter(function ($value, $key) {
            return $key !== 'default';
        });
        $srcSet = $this->calculateSrcSet($urlsWithWidths);

        $imageTag->setAttribute('src', $defaultUrl);
        $imageTag->setAttribute('srcset', $srcSet);
    }

    private function calculateSrcSet(Collection $urls): string
    {
        return $urls->map(function ($url, $key) {
            return $url . ' ' . $key . 'w';
        })->implode(', ');
    }

    private function mediaId(string $imageSrc): string
    {
        $newImageSrc = $this->stripDiskUrlFromImageSource($imageSrc);
        $parts = explode('/', $newImageSrc);
        // If the string began with / we get an empty string
        $parts = array_values(array_filter($parts));
        return $parts[0];
    }

    private function stripDiskUrlFromImageSource(string $imageSrc): string
    {
        $url = $this->diskUrl();
        if (is_null($url)) {
            return $imageSrc;
        }
        $newSrc = str_replace($url, "", $imageSrc);

        return $newSrc;
    }

    private function diskUrl(): ?string
    {
        $config = Config::get('filesystems.disks.' . $this->disk . '.url');
        return $config;
    }
}
