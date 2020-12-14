@extends('layouts.app')

@section('content')
{{-- <img src="{{$url}}" alt=""> --}}
<capture-wrap imageurl="{{$url}}" directory="{{$id}}" endpoint="{{ route('comment', ['id' => $id]) }}">
</capture-wrap>
@endsection