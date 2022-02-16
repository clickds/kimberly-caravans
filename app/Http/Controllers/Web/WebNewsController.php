<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Repositories\NewsRepository;
use Illuminate\Contracts\View\View;

/**
 * Class WebNewsController
 * @package App\Http\Controllers\Web
 */
class WebNewsController extends Controller
{

    /**
     * @var NewsRepository
     */
    private NewsRepository $newsRepo;

    /**
     * WebNewsController constructor.
     * @param NewsRepository $newsRepository
     */
    public function __construct(NewsRepository $newsRepository)
    {
        $this->newsRepo = $newsRepository;
    }

    /**
     * @return View
     */
    public function index() : View
    {

        // pull all latest news
        $latestNews = $this->newsRepo->getAllPublished();

        return view('news.index',[
            'latest_news'=>$latestNews
        ]);
    }
}
