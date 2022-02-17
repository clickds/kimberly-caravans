@extends('layouts/site')

@section('body')
    @include('components/page-banner')
    @foreach($latest_news as $article)
        {{$article->title}}
        {!! $article->buildExcerpt(50) !!}
        <a href="/news/{{$article->id}}">Read more</a>
        {{$article->created_at->format('d/m/Y H:i')}}
        {{$article->author->name}}
    @endforeach
@endsection
