<?php

namespace App\Http\Controllers\Admin\Fieldset;

use App\Http\Controllers\Controller;
use App\Models\Fieldset;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class ClonesController extends Controller
{
    public function store(Fieldset $fieldset): RedirectResponse
    {
        DB::beginTransaction();
        try {
            $clone = $fieldset->replicate();
            $clone->name = $fieldset->name . ' Clone';
            $clone->save();
            $this->cloneFields($clone, $fieldset->fields);

            DB::commit();
            return redirect()->route('admin.fieldsets.edit', $clone)
                ->with('success', 'Fieldset Cloned');
        } catch (Throwable $e) {
            DB::rollBack();
            Log::error($e);

            return redirect()->back()->with('warning', 'Could not clone fieldset');
        }
    }

    private function cloneFields(Fieldset $clone, Collection $fields): void
    {
        foreach ($fields as $field) {
            $fieldClone = $field->replicate();
            $fieldClone->fieldset_id = $clone->id;
            $fieldClone->save();
        }
    }
}
