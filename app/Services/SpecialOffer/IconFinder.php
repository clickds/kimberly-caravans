<?php

namespace App\Services\SpecialOffer;

use App\Models\SpecialOffer;

class IconFinder
{
    public function call(): array
    {
        $directory = $this->directoryPath();
        $globPath = $directory . '/*';
        $files = glob($globPath);

        if ($files === false) {
            return [];
        }

        return array_map(function ($filePath) {
            return basename($filePath);
        }, $files);
    }

    private function directoryPath(): string
    {
        return SpecialOffer::iconDirectoryPath();
    }
}
