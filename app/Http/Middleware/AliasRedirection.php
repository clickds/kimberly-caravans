<?php

namespace App\Http\Middleware;

use App\Models\Alias;
use App\Models\Page;
use App\Presenters\Page\BasePagePresenter;
use Closure;
use Illuminate\Support\Str;

class AliasRedirection
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $alias = $this->fetchAlias($request->path());

        if ($alias && $alias->page) {
            $redirectUrl = $this->redirectUrl($alias->page);
            return redirect($redirectUrl);
        }

        return $next($request);
    }

    private function redirectUrl(Page $page): string
    {
        $class = $page->getPresenterClass();
        $pagePresenter = new $class();
        $pagePresenter->setWrappedObject($page);
        return $pagePresenter->link();
    }

    private function fetchAlias(string $path): ?Alias
    {
        $path = Str::start($path, '/');
        $site = resolve('currentSite');

        return Alias::with('page', 'page.parent:id,slug')
            ->where('capture_path', $path)
            ->where('site_id', $site->id)
            ->first();
    }
}
