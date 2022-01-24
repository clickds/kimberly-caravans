<div class="container mx-auto py-4 lg:px-12">
    <h2 class="text-endeavour font-semibold mb-4">Part Exchange</h2>
    <p class="mb-5">We are always on the lookout for quality pre-owned motorhomes and caravans, but we will also take cars, boats and motorcycles, in fact almost anything in part exchange.</p>
    <p class="mb-5 text-endeavour">Please note that we do not provide online valuations. We will contact you by telephone to discuss your vehicle and its value.</p>
    <p class="mb-10">Please complete the details below as accurately as possible as this will help us to give you the most accurate price prior to inspecting your vehicle. So that we can get in touch with you, please fill in your contact details including your phone number and email address if appropriate.</p>

    @if($form)
      @include('site.panels.form.form', [
        'site' => $pageFacade->getSite(),
        'form' => $form,
        'submissionUrl' => '/',
        'fieldsets' => $form->fieldsets,
      ])
    @endif
</div>