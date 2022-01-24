# Important

See https://github.com/getsentry/sentry-laravel/issues/371 - currently a workaround to allow update to laravel 8

# Domain Knowledge

MIRO - Mass In Running Order - basically base mass of the vehicle
MTPLM - Maximum Technically Permissible Laden Mass - basically base mass + maximum allowed payload
Payload - Mass of stuff allowed to put in the vehicle

# Notes

This application serves different content based on subdomain. Laravel has options for [subdomains](https://laravel.com/docs/6.x/routing#route-group-sub-domain-routing) but even making the subdomain an optional parameter would fail locally unless the subdomain on a site includes the trailing dot.

As a result the `App\Providers\AppServiceProvider` binds a macro for site to the request, either fetching the site by the subdomain or getting the first site where is_default is true. If this fails it aborts with a 404.

This allows us to set a default site for local development. There is a basic tet in `tests\Feature\Sites\SetsCorrectSiteTest.php`

# Docker local development setup

## Container setup

Build and run the containers using `docker-compose up`

You will then need to attach to the `marquis-app` container by running `docker-compose exec marquis-app bash`.

## Laravel setup

1. Install dependencies: `composer install`.

2. Set up your environment file: `cp .env.example .env` and add any necessary credentials to the `.env`.

3. Generate an application key: `php artisan key:generate`.

4. Create database tables: `php artisan migrate:fresh`.

5. Add some data: `php artisan db:seed`.

# Notes

## The Feed

The "Exclusive" field in the feed is actually "Managers Special" on the site.

## Pages

Everything on the front of the site is a page, that runs through the Site/Page controller. This renders `site/pages/page` view, which in turn renders `resources/views/site/pages/{template-name}/main.blade.php`

## Pageables

A pageable uses the `App\Models\Traits\Pageable` trait. This trait defines a has and belongs to many (polymorphic) association between pageables and sites. It also defines a polymorphic has many with pages.

The pageable trait is used to create stock item pages on stock items for any sites that are classed as having stock - the has_stock boolean on a site is set to true.

### Pageable Events

The pageable trait fires a `App\Events\PageableUpdated` event when a pageable is saved.

### Responsive Images

```
        $this->addMediaConversion('thumbnail')
            ->width(920)
            ->height(430)
            ->fit(Manipulations::FIT_FILL, 920, 430)
            ->crop(Manipulations::CROP_CENTER, 920, 430)
            ->performOnCollections('image')
            ->withResponsiveImages()
            ->nonQueued();
```

For spatie media library responsive images we've decided to apply styling by wrapping the image in a container that applies the styling for the image - these are in the components/image-containers sass file.

### Tailwind

When centred content - not full width padding should use a px-standard class defined in \_custom-utilities.scss

# Importing Old Site Content

White Agency would like the following imported:

-   News (past 2 years only)
-   Events (only upcoming)
-   Reviews
-   Customer Reviews (Testimonials)
-   Videos
-   Brochures

The panel types on the old site => new site

-   RichText => TYPE_STANDARD
-   Form => TYPE_FORM
-   Offers => TYPE_SPECIAL_OFFERS
-   News
-   Videos => TYPE_VIDEO
-   Reviews
-   Events
-   Calc
-   Advent
-   Brochure

So there are essentially four panel types that could be imported theoretically.
Testimonials, Reviews and Brochures don't have pages so won't have panels.
There are no upcoming events due to covid.
No Video page has a panel on the old site

I suggest we ignore Form and Offers Panel types as they will be too complex to import and are
a secondary item. Any form attached would surely only be for entering a competition or equivalent. There is also a "Hidden" area on panels - we will ignore these.

Video panels on the old site you can select a category to display multiple by the looks of it.
There isn't an equivalent on the new site, so we'll only import video panels displaying a single video.

I suggest we import Videos first so we can link up the video panels on the other items to be imported.

Once the imports have happened the things to remove will be

-   This part of the readme
-   The old_site_mysql connection in config/database.php
-   The app/OldSite folder
-   The app/Commands/ImportDataFromOldSite.php
-   The settings in .env.example

## Import attachments

On the old site everything is basically a MediaCategory
A media category can have many ranges

### For News

Filters

-   Category
-   Range
-   Dealership

New Category examples:
Motorhomes
Caravans
Shows and Events
Featured Reviews

Suggest for Motorhomes/Caravans we basically check the media category names if any contain
motorhome or caravan then add them.

For ranges, get the ranges names via the media categories. Attach any ranges that match exactly.

For dealers, there are media categories which match the dealer name exactly on the old site.

### For Reviews

Filters

-   Category
-   Range
-   Dealership

Categories just:

-   Motorhomes
-   Caravans

### For Videos

FILTERS TO BE:

-   Category
-   Range
-   Dealership

Category examples:

-   Motorhomes
-   Caravans
-   Aftercare videos

No such thing as aftercare videos on the old site
