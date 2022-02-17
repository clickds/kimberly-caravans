@extends('layouts/site')

@section('body')
    @include('components/page-banner')
    <h1>{{$news->title}}</h1>
    {!! $news->content !!}
@endsection
