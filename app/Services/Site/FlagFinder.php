<?php

namespace App\Services\Site;

class FlagFinder
{
    public function call(): array
    {
        $flagDirectory = $this->flagDirectoryPath();
        $globPath = $flagDirectory . '/*';
        $files = glob($globPath);

        if ($files === false) {
            return [];
        }

        return array_map(function ($filePath) {
            return basename($filePath);
        }, $files);
    }

    private function flagDirectoryPath(): string
    {
        return public_path('images/flags');
    }
}
