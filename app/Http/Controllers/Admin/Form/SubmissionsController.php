<?php

namespace App\Http\Controllers\Admin\Form;

use App\Http\Controllers\Controller;
use App\Models\Form;
use App\Models\FormSubmission;
use Illuminate\View\View;

class SubmissionsController extends Controller
{
    public function index(Form $form): View
    {
        $submissions = $form->submissions()->orderBy('created_at', 'desc')->get();

        return view('admin.form.submissions.index', [
            'form' => $form,
            'submissions' => $submissions,
        ]);
    }

    public function show(Form $form, FormSubmission $submission): View
    {
        return view('admin.form.submissions.show', [
            'form' => $form,
            'submission' => $submission,
        ]);
    }
}
