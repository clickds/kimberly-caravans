<div class="px-2 py-10 w-full flex flex-col items-center justify-center">
  <div class="font-heading font-medium text-endeavour text-h3">
    {{ $article->formattedDate() }}
  </div>
  <h2 class="text-endeavour">{{ $article->title }}</h2>

  <div class="wysiwyg text-xl">
    {!! $article->excerpt !!}
  </div>
</div>