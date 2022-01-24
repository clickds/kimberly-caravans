<div class="w-full bg-gallery">
  <div class="container mx-auto py-5 px-standard">
    <div class="grid gap-5 grid-cols-1 md:grid-cols-3 lg:grid-cols-4">
      @foreach($dealers as $dealer)
        @include('site.shared.dealer-card', ['dealer' => $dealer])
      @endforeach
    </div>
  </div>
</div>