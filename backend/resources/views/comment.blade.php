@extends('layouts.app')

@section('content')
{{-- {{ $page_id }} --}}
{{-- <img src="{{$url}}" alt=""> --}}
<capture-wrap imageurl="{{ $image_url }}" pageid="{{ $page_id }}" directory="{{$directory}}"
  endpoint="{{ route('comment', ['directory' => $directory]) }}">
</capture-wrap>
@endsection