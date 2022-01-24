<?php

namespace App\Mail;

use App\Models\Form;
use App\Models\FormSubmission;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewFormSubmission extends Mailable
{
    use Queueable;
    use SerializesModels;

    private FormSubmission $formSubmission;
    private ?Form $form;

    public function __construct(FormSubmission $formSubmission)
    {
        $this->formSubmission = $formSubmission;
        $this->form = $formSubmission->form;
    }

    public function build(): NewFormSubmission
    {
        return $this->view('emails.form-submissions.new', [
            'form' => $this->form,
            'submission' => $this->formSubmission
        ]);
    }
}
