<nav class="bg-alabaster p-3 w-full">
	<ol class="list-reset flex container mx-auto text-sm">
		<li class="mr-2 opacity-50">You are here: </li>
		<li><a href="/" class="text-endeavour hover:underline">Home</a></li>
		<li><span class="mx-2">/</span></li>
		@if ($breadcrumbs)
			@foreach ($breadcrumbs as $breadcrumb => $link)
				<li class="opacity-50">
					@if ($link)
						<a href="{{ $link }}">{{ $breadcrumb }}</a>
					@else
						{{ $breadcrumb }}
					@endif
				</li>
				@if ($link)
					<li><span class="mx-2">/</span></li>
				@endif
			@endforeach
		@endif
	</ol>
</nav>