@foreach($listingPages as $listingPage)
  @include('admin._partials.button', ['url' => $listingPage->siteLink(), 'text' => sprintf('%s (%s)', $buttonText, $listingPage->site->country)])
@endforeach