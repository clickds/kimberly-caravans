<?php

namespace App\Facades\Testimonial;

use App\Facades\BasePage;
use App\Models\Page;
use App\Models\Testimonial;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Http\Request;

class ListingPage extends BasePage
{
    private Paginator $testimonials;

    public function __construct(Page $page, Request $request)
    {
        parent::__construct($page, $request);

        $this->testimonials = $this->fetchTestimonials();
    }

    public function getTestimonials(): Paginator
    {
        return $this->testimonials;
    }

    private function fetchTestimonials(): Paginator
    {
        $site = $this->getSite();

        return Testimonial::join('site_testimonial', 'testimonials.id', '=', 'site_testimonial.testimonial_id')
            ->where('site_testimonial.site_id', $site->id)
            ->orderBy('published_at', 'desc')
            ->orderBy('position', 'desc')
            ->paginate($this->perPage());
    }
}
