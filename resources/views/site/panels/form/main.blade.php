@include('site.panels._heading', [
  'panel' => $panel,
])

<div class="wysiwyg">
  {!! $panel->getContent() !!}
</div>

@include('site.panels.form.form', [
  'site' => $pageFacade->getSite(),
  'form' => $panel->getForm(),
  'submissionUrl' => $panel->getFormSubmissionUrl(),
  'fieldsets' => $panel->getFieldsets(),
])