<?php

namespace App\Http\Controllers\Admin;

use App\Models\Vacancy;
use App\Models\VacancyApplication;
use Illuminate\Http\Request;
use Illuminate\View\View;

class VacancyApplicationsController extends BaseController
{
    public function index(Request $request, Vacancy $vacancy): View
    {
        $vacancyApplications = $vacancy->applications()->with('employmentHistory')
            ->ransack($request->all())->get();

        return view('admin.vacancy-applications.index', [
            'vacancyApplications' => $vacancyApplications,
        ]);
    }

    public function show(Vacancy $vacancy, VacancyApplication $vacancyApplication): View
    {
        return view('admin.vacancy-applications.show', [
            'vacancy' => $vacancy,
            'vacancyApplication' => $vacancyApplication,
        ]);
    }
}
