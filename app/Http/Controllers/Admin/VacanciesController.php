<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\Vacancies\StoreRequest;
use App\Http\Requests\Admin\Vacancies\UpdateRequest;
use App\Models\Dealer;
use App\Models\Page;
use App\Models\Site;
use App\Models\Vacancy;
use App\Services\Pageable\VacancyPageSaver;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class VacanciesController extends BaseController
{
    public function index(Request $request): View
    {
        $vacancies = Vacancy::with('pages')->withCount('applications')
            ->ransack($request->all())->get();

        return view('admin.vacancies.index', [
            'vacancies' => $vacancies,
            'listingPages' => $this->getPagesWithTemplate(Page::TEMPLATE_VACANCIES_LISTING),
        ]);
    }

    public function create(Request $request): View
    {
        return view('admin.vacancies.create', [
            'vacancy' => new Vacancy(),
            'dealers' => $this->fetchDealers(),
            'redirectUrl' => $this->redirectUrl($request),
        ]);
    }

    public function store(StoreRequest $request): RedirectResponse
    {
        try {
            DB::beginTransaction();

            $vacancy = Vacancy::create($request->validated());

            $this->syncDealers($request, $vacancy);

            $this->maintainSitePages($vacancy);

            DB::commit();

            if ($redirect_url = $this->redirectUrl($request)) {
                return redirect($redirect_url)->with('success', 'Vacancy created');
            }

            return redirect()
                ->route('admin.vacancies.index')
                ->with('success', 'Vacancy created');
        } catch (Throwable $e) {
            Log::error($e);

            DB::rollBack();

            return back()->with('error', 'Failed to create vacancy');
        }
    }

    public function edit(Vacancy $vacancy, Request $request): View
    {
        return view('admin.vacancies.edit', [
            'vacancy' => $vacancy,
            'dealers' => $this->fetchDealers(),
            'redirectUrl' => $this->redirectUrl($request),
        ]);
    }

    public function update(UpdateRequest $request, Vacancy $vacancy): RedirectResponse
    {
        try {
            DB::beginTransaction();

            $vacancy->update($request->validated());

            $this->syncDealers($request, $vacancy);

            $this->maintainSitePages($vacancy);

            DB::commit();

            if ($redirect_url = $this->redirectUrl($request)) {
                return redirect($redirect_url)->with('success', 'Vacancy updated');
            }

            return redirect()
                ->route('admin.vacancies.index')
                ->with('success', 'Vacancy updated');
        } catch (Throwable $e) {
            Log::error($e);

            DB::rollBack();

            return back()->with('error', 'Failed to update vacancy');
        }
    }

    public function destroy(Vacancy $vacancy, Request $request): RedirectResponse
    {
        $vacancy->delete();

        if ($redirect_url = $this->redirectUrl($request)) {
            return redirect($redirect_url)->with('success', 'Vacancy deleted');
        }

        return redirect()
            ->route('admin.vacancies.index')
            ->with('success', 'Vacancy deleted');
    }

    private function syncDealers(FormRequest $request, Vacancy $vacancy): void
    {
        $vacancy->dealers()->sync(
            $request->get('dealer_ids', [])
        );
    }

    private function fetchDealers(): Collection
    {
        return Dealer::all();
    }

    private function maintainSitePages(Vacancy $vacancy): void
    {
        // Currently only create vacancies for the default site.

        $defaultSite = Site::where('is_default', true)->firstOrFail();

        $vacancy->pages()->delete();

        $saver = new VacancyPageSaver($vacancy, $defaultSite);

        $saver->call();
    }
}
