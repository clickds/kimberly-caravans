<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\Forms\StoreRequest;
use App\Http\Requests\Admin\Forms\UpdateRequest;
use App\Models\EmailRecipient;
use App\Models\Fieldset;
use Illuminate\Http\Request;
use App\Models\Form;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class FormsController extends BaseController
{
    public function index(Request $request): View
    {
        $forms = Form::with('fieldsets:id,name')->with('areas.page')->withCount('submissions')
            ->ransack($request->all())->orderBy('name', 'asc')->paginate(15);

        return view('admin.forms.index', [
            'forms' => $forms,
        ]);
    }

    public function create(Request $request): View
    {
        return view('admin.forms.create', [
            'emailRecipients' => $this->fetchEmailRecipients(),
            'fieldsets' => $this->fetchFieldsets(),
            'form' => new Form(),
            'types' => Form::VALID_TYPES,
            'redirectUrl' => $this->redirectUrl($request),
        ]);
    }

    public function store(StoreRequest $request): RedirectResponse
    {
        $form = new Form();

        if ($this->saveForm($form, $request)) {
            if ($redirect_url = $this->redirectUrl($request)) {
                return redirect($redirect_url)->with('success', 'Form created');
            }

            return redirect()
                ->route('admin.forms.index')
                ->with('success', 'Form created');
        }

        return redirect()
            ->back()
            ->with('error', 'Failed to create form');
    }

    public function edit(Form $form, Request $request): View
    {
        return view('admin.forms.edit', [
            'emailRecipients' => $this->fetchEmailRecipients(),
            'fieldsets' => $this->fetchFieldsets(),
            'form' => $form,
            'types' => Form::VALID_TYPES,
            'redirectUrl' => $this->redirectUrl($request),
        ]);
    }

    public function update(UpdateRequest $request, Form $form): RedirectResponse
    {
        if ($this->saveForm($form, $request)) {
            if ($redirect_url = $this->redirectUrl($request)) {
                return redirect($redirect_url)->with('success', 'Form updated');
            }

            return redirect()->route('admin.forms.index')->with('success', 'Form updated');
        }

        return redirect()
            ->back()
            ->with('error', 'Failed to update form');
    }

    public function destroy(Form $form, Request $request): RedirectResponse
    {
        $form->delete();

        if ($redirect_url = $this->redirectUrl($request)) {
            return redirect($redirect_url)->with('success', 'Form deleted');
        }

        return redirect()
            ->route('admin.forms.index')
            ->with('success', 'Form deleted');
    }

    private function fetchEmailRecipients(): Collection
    {
        return EmailRecipient::orderBy('name', 'asc')->select('name', 'id')->get();
    }

    private function fetchFieldsets(): Collection
    {
        return Fieldset::orderBy('name', 'asc')->select('name', 'id')->get();
    }

    private function saveForm(Form $form, FormRequest $request): bool
    {
        DB::beginTransaction();
        try {
            $data = $request->validated();
            $form->fill($data);
            $form->save();

            $carbonCopyRecipientIds = $request->get('carbon_copy_ids', []);
            $form->carbonCopyRecipients()->sync($carbonCopyRecipientIds);

            $fieldsetIds = $request->get('fieldset_ids', []);
            $mappedFieldsetIds = $this->mapFieldsetIdsIndexToPosition($fieldsetIds);
            $form->fieldsets()->sync($mappedFieldsetIds);

            DB::commit();
            return true;
        } catch (Throwable $e) {
            DB::rollBack();
            Log::error($e);
            return false;
        }
    }

    private function mapFieldsetIdsIndexToPosition(array $fieldsetIds): array
    {
        $fieldsetIds = collect($fieldsetIds);
        return $fieldsetIds->mapWithKeys(function ($fieldsetId, $index) {
            return [$fieldsetId => ['position' => $index]];
        })->toArray();
    }
}
