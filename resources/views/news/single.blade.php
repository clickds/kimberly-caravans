@extends('layouts/site')

@section('body')
    <h1>{{$news->title}}</h1>
    {!! $news->content !!}
@endsection
