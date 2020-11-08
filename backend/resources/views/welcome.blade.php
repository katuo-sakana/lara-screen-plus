@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Capture plus</div>

                <div class="card-body">
                    <form method="POST" action="{{ url('/') }}">
                        @csrf
                        <div class="form-group">
                            <label for="urlInput">指定のURLを入力ください</label>
                            <input type="url" name="url" class="form-control" id="urlInput" aria-describedby="emailHelp"
                                placeholder="https://example.com">
                        </div>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection