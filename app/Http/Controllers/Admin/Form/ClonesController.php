<?php

namespace App\Http\Controllers\Admin\Form;

use App\Http\Controllers\Controller;
use App\Models\Form;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class ClonesController extends Controller
{
    public function store(Form $form): RedirectResponse
    {
        DB::beginTransaction();
        try {
            $clone = $form->replicate();
            $clone->name = $form->name . ' Clone';
            $clone->save();
            $fieldsetIds = $form->fieldsets()->toBase()->pluck('id')->toArray();
            $clone->fieldsets()->attach($fieldsetIds);

            DB::commit();
            return redirect()->route('admin.forms.edit', $clone)
                ->with('success', 'Form Cloned');
        } catch (Throwable $e) {
            DB::rollBack();
            Log::error($e);

            return redirect()->back()->with('warning', 'Could not clone form');
        }
    }
}
