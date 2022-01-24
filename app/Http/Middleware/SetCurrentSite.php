<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Site;
use Illuminate\Http\Request;

class SetCurrentSite
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $site = $this->findSiteBySubdomain($request);
        if (is_null($site)) {
            $site = $this->findDefaultSite();
        }

        if (is_null($site)) {
            abort(404);
        }

        app()->instance('currentSite', $site);

        return $next($request);
    }

    private function findSiteBySubdomain(Request $request): ?Site
    {
        // Extract the subdomain from URL.
        list($subdomain) = explode('.', $request->getHost(), 2);
        // Retrieve requested site's info from database.
        return Site::where('subdomain', $subdomain)->first();
    }

    private function findDefaultSite(): ?Site
    {
        return Site::where('is_default', true)->first();
    }
}
