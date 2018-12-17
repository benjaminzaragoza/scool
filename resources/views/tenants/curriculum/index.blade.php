@extends('tenants.layouts.app')

@section('content')
    <curriculum :studies="{{ $studies }}" :departments="{{ $departments }}" :families="{{ $families }}"></curriculum>
@endsection
