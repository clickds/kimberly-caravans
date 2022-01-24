<div class="form-builder-panel">
    @if($form)
      @include('site.panels.form.success-message')
      @include('site.panels.form.errors')

      <form action="{{ $submissionUrl }}" method="post" enctype="multipart/form-data">
        @csrf
        @foreach($fieldsets as $fieldset)
          @include('site.panels.form.fieldset', ['fieldset' => $fieldset])
        @endforeach
      </form>
    @endif
  </div>