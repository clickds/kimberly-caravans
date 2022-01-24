<?php

namespace App\Http\Controllers\Admin\Buttonable;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Buttonable\Buttons\StoreRequest;
use App\Http\Requests\Admin\Buttonable\Buttons\UpdateRequest;
use App\Models\Button;
use App\Models\Interfaces\HasButtons;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Str;

class ButtonsController extends Controller
{
    /**
     * @param mixed $buttonable
     */
    public function index($buttonable): View
    {
        $buttons = $buttonable->buttons()->orderBy('position', 'asc')->get();

        return view('admin.buttonable.buttons.index', [
            'buttonable' => $buttonable,
            'buttons' => $buttons,
            'pluralButtonableRouteName' => $this->pluralButtonableRouteName($buttonable),
        ]);
    }

    /**
     * @param mixed $buttonable
     */
    public function create($buttonable): View
    {
        $button = $buttonable->buttons()->make();

        return view('admin.buttonable.buttons.create', [
            'buttonable' => $buttonable,
            'button' => $button,
            'pluralButtonableRouteName' => $this->pluralButtonableRouteName($buttonable),
            'colours' => $this->fetchColours(),
        ]);
    }

    /**
     * @param mixed $buttonable
     */
    public function store(StoreRequest $request, $buttonable): RedirectResponse
    {
        $data = $request->validated();
        $buttonable->buttons()->create($data);

        $redirectUrl = $this->redirectUrl($buttonable);

        return redirect()->to($redirectUrl)->with('success', 'Button saved');
    }

    /**
     * @param mixed $buttonable
     */
    public function edit($buttonable, Button $button): View
    {
        return view('admin.buttonable.buttons.edit', [
            'buttonable' => $buttonable,
            'button' => $button,
            'pluralButtonableRouteName' => $this->pluralButtonableRouteName($buttonable),
            'colours' => $this->fetchColours(),
        ]);
    }

    /**
     * @param mixed $buttonable
     */
    public function update(UpdateRequest $request, $buttonable, Button $button): RedirectResponse
    {
        $data = $request->validated();
        $button->update($data);

        $redirectUrl = $this->redirectUrl($buttonable);

        return redirect()->to($redirectUrl)->with('success', 'Button saved');
    }

    /**
     * @param mixed $buttonable
     */
    public function destroy($buttonable, Button $button): RedirectResponse
    {
        $button->delete();

        $redirectUrl = $this->redirectUrl($buttonable);

        return redirect()->to($redirectUrl)->with('success', 'Button deleted');
    }

    private function redirectUrl(HasButtons $buttonable): string
    {
        $plural = $this->pluralButtonableRouteName($buttonable);
        return route('admin.' . $plural . '.buttons.index', $buttonable);
    }

    private function pluralButtonableRouteName(HasButtons $buttonable): string
    {
        $classBasename = class_basename($buttonable);
        $singular = Str::kebab($classBasename);
        return Str::plural($singular);
    }

    private function fetchColours(): array
    {
        return Button::COLOURS;
    }
}
