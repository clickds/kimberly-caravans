<section class="items-collection">
	<div class="container mb-12 mx-auto md:grid md:grid-cols-2 xl:grid-cols-3 md:gap-8">

		@foreach ($items_collection as $item)
			<article class="w-full">
				@if ($item->hasMedia('image'))
					<div class="items-collection__image bg-cover bg-center ml-3" style="height: 250px; background-image: url({{  $item->getFirstMediaUrl('image') }})" >
				@else
					<div class="items-collection__image bg-cover bg-center ml-3" style="height: 250px; background-image: url('https://via.placeholder.com/250')" >
				@endif
				  	<div class="h-full w-full">
				  		<h3 class="pt-3 pl-3 text-2xl text-white font-normal">
				  			{{ $item->title }}
				  		</h3>
				  	</div>
				</div>
				<p class="pt-10 pb-4 px-3">{{ \Illuminate\Support\Str::limit($item->excerpt, 90, $end='...') }}</p>
				<a class="pl-3 text-sm font-semibold text-endeavour underline" href="{{ pageLink($item->sitePage($site->getWrappedObject())) }}">Discover More</a>
			</article>
		@endforeach

	</div>

	<div class="container mx-auto text-center">

		{{ $items_collection->links() }}

	</div>
</section>