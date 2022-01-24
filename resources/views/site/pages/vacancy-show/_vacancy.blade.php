<div class="container mx-auto pt-10">
  <h2 class="text-endeavour">{{ $vacancy->title }}</h2>
  <h4 class="text-tundora mb-5">Posted: {{ $vacancy->getPostedDate() }}</h4>
  <div class="text-lg text-dove-gray mb-10 wysiwyg">
    {!! $vacancy->description !!}
  </div>
  <div class="flex flex-col md:flex-row text-lg text-dove-gray mb-10">
    <div class="w-1/3 flex flex-col">
      @if($vacancy->salary)
        <div class="flex flex-row mb-5">
          <div class="border-2 h-8 w-8 rounded-full border-shiraz text-shiraz text-center mr-5">
            {!! $vacancy->getCurrencySymbol() !!}
          </div>
          <div>
            Salary: {{ $vacancy->salary }}
          </div>
        </div>
      @endif
      <div class="flex flex-row">
        <div class="text-shiraz h-8 w-8 mr-5">
          @include('site.shared.svg-icons.clock')
        </div>
        <div>
          Closing Date: {{ $vacancy->getClosingDate() }}
        </div>
      </div>
    </div>
    <div class="w-2/3">
      <div class="flex flex-row">
        <div class="text-shiraz h-8 w-8 mr-5 mb-5">
          @include('site.shared.svg-icons.book-viewing')
        </div>
        <div>
          {{ implode(', ', $vacancy->getDealerNames()) }}
        </div>
      </div>
    </div>
  </div>

  <div>
    <h3 class="mb-5">Apply Now</h3>
    @include('site.pages.vacancy-show._errors')
    @include('site.pages.vacancy-show._success')
    @include('site.pages.vacancy-show._application-form', ['action' => $vacancy->getApplicationSubmissionUrl()])
  </div>
</div>
