@extends('layouts.app-comment')

@section('content')
{{-- @dump($comments); --}}
{{-- {{ $page_id }} --}}
{{-- <img src="{{$url}}" alt=""> --}}
<capture-wrap imageurl="{{ $image_url }}" pageid="{{ $page_id }}" directory="{{$directory}}"
  v-bind:commentsdata="{{$comments}}" endpoint="{{ route('comment', ['directory' => $directory]) }}">
</capture-wrap>
@endsection