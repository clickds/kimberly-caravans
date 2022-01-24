<div class="mb-10">
    <a href="{{ $searchResult['relativeUrl'] }}">
        <h3 class="text-endeavour">{!! $searchResult['name'] !!}</h3>
        <span class="text-h5 text-endeavour italic">{{ $searchResult['type'] }} ({{ $searchResult['condition'] }})</span>
    </a>
    <div class="mt-3">
        {{ $searchResult['content'] }}
    </div>
</div>