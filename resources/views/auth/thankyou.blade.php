@extends('layouts.app')

@section('content')
    <div class="container pt-5">
        <div class="d-flex flex-column align-items-center justify-content-center">
            <h1 class="display-6 pb-4">Thank you for registering!</h1>
            <a href="{{ route('contacts') }}" class="btn btn-primary">Continue</a>
        </div>
    </div>
@endsection
