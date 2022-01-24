<div class="container mx-auto px-standard flex flex-wrap">
  <div class="w-full md:w-1/2">
    Currently Displaying: {{ $paginator->firstItem() }} - {{ $paginator->lastItem() }} of {{ $paginator->total() }} Items
  </div>
</div>