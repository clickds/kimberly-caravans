<?php

namespace App\Facades\Video;

use Illuminate\Http\Request;
use App\Facades\BasePage;
use App\Models\Video;
use App\Models\Page;

class ShowPage extends BasePage
{
    /**
     * @var Video
     */
    private $video;

    public function __construct(Page $page, Request $request)
    {
        parent::__construct($page, $request);
        $this->video = $page->pageable;
    }

    public function getVideo(): Video
    {
        return $this->video;
    }
}
