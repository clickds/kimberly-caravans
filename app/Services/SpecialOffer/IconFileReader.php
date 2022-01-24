<?php

namespace App\Services\SpecialOffer;

use App\Models\SpecialOffer;

class IconFileReader
{
    private string $filename;

    public function __construct(string $filename)
    {
        $this->filename = $filename;
    }

    public function call(): string
    {
        $filePath = $this->directoryPath() . '/' . $this->getFilename();
        if (!file_exists($filePath)) {
            return "";
        }
        $contents = file_get_contents($filePath);
        if ($contents == false) {
            return "";
        }
        return $contents;
    }

    private function getFilename(): string
    {
        return $this->filename;
    }

    private function directoryPath(): string
    {
        return SpecialOffer::iconDirectoryPath();
    }
}
