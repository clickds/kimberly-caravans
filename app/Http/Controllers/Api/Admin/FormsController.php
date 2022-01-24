<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\Admin\FormResource;
use App\Models\Form;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class FormsController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        $forms = Form::orderBy('name', 'asc')->get();

        return FormResource::collection($forms);
    }
}
