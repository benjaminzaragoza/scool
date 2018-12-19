@extends('tenants.layouts.app')

@section('content')
    <curriculum :studies="{{ $studies }}" :departments="{{ $departments }}" :families="{{ $families }}" :tags="{{ $tags }}"></curriculum>
@endsection
