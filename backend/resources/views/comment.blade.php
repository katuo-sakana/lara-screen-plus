@extends('layouts.app')

@section('content')
{{-- <img src="{{$url}}" alt=""> --}}
<capture-wrap imageurl="{{ $image_url }}" directory="{{$directory}}"
  endpoint="{{ route('comment', ['directory' => $directory]) }}">
</capture-wrap>
@endsection