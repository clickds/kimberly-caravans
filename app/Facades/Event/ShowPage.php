<?php

namespace App\Facades\Event;

use Illuminate\Http\Request;
use App\Facades\BasePage;
use App\Models\Event;
use App\Models\Page;

class ShowPage extends BasePage
{
    /**
     * @var Event
     */
    private $event;

    public function __construct(Page $page, Request $request)
    {
        parent::__construct($page, $request);

        $this->event = $page->pageable;
    }

    public function getEvent(): Event
    {
        return $this->event;
    }
}
