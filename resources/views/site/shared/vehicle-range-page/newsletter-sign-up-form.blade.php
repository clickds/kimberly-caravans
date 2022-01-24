<div class="my-20 text-center text-lg">
  <p class="mb-4">
    Stay up to date with all the latest Marquis news, promotions and events on both new and pre-owned Motorhomes and Caravans.
  </p>

  <p class="mb-4">
    If you would like to join our mailing list, please leave your name and contact details below and click submit.
  </p>

  <p>
    We will not bombard you with emails on a daily basis, we simply send you details of our relevant forthcoming events, promotions and special offers.
    We will not pass your details onto third parties without your consent and you may unsubscribe from the mailing list at any time
  </p>
</div>

@if($form)
  @include('site.panels.form.form', [
    'site' => $pageFacade->getSite(),
    'form' => $form,
    'submissionUrl' => $form->submissionUrl,
    'fieldsets' => $form->fieldsets,
  ])
@endif