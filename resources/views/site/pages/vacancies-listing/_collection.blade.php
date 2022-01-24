<div class="container mx-auto pt-10">
  @if($vacancies->isNotEmpty())
    @foreach($vacancies as $vacancy)
      @include('site.pages.vacancies-listing._vacancy', ['vacancy' => $vacancy])
    @endforeach
  @else
    <h2>No current vacancies</h2>
  @endif
</div>
