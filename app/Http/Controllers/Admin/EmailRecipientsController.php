<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\EmailRecipients\StoreRequest;
use App\Http\Requests\Admin\EmailRecipients\UpdateRequest;
use App\Models\EmailRecipient;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EmailRecipientsController extends BaseController
{
    public function index(Request $request): View
    {
        $emailRecipients = EmailRecipient::ransack($request->all())
            ->orderBy('name', 'asc')->get();

        return view('admin.email-recipients.index', [
            'emailRecipients' => $emailRecipients,
        ]);
    }

    public function create(Request $request): View
    {
        $emailRecipient = new EmailRecipient();

        return view('admin.email-recipients.create', [
            'emailRecipient' => $emailRecipient,
            'redirectUrl' => $this->redirectUrl($request),
        ]);
    }

    public function store(StoreRequest $request): RedirectResponse
    {
        $data = $request->validated();
        EmailRecipient::create($data);

        if ($redirect_url = $this->redirectUrl($request)) {
            return redirect($redirect_url)->with('success', 'Email recipient created');
        }

        return redirect()
            ->route('admin.email-recipients.index')
            ->with('success', 'Email recipient created');
    }

    public function edit(EmailRecipient $emailRecipient, Request $request): View
    {
        return view('admin.email-recipients.edit', [
            'emailRecipient' => $emailRecipient,
            'redirectUrl' => $this->redirectUrl($request),
        ]);
    }

    public function update(UpdateRequest $request, EmailRecipient $emailRecipient): RedirectResponse
    {
        $data = $request->validated();
        $emailRecipient->update($data);

        if ($redirect_url = $this->redirectUrl($request)) {
            return redirect($redirect_url)->with('success', 'Email recipient updated');
        }

        return redirect()
            ->route('admin.email-recipients.index')
            ->with('success', 'Email recipient updated');
    }

    public function destroy(EmailRecipient $emailRecipient, Request $request): RedirectResponse
    {
        $emailRecipient->delete();

        if ($redirect_url = $this->redirectUrl($request)) {
            return redirect($redirect_url)->with('success', 'Email recipient deleted');
        }

        return redirect()
            ->route('admin.email-recipients.index')
            ->with('success', 'Email recipient deleted');
    }
}
