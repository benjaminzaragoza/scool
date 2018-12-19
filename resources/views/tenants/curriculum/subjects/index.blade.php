@extends('tenants.layouts.app')

@section('content')
    <subjects :subjects="{{ $subjects }}"></subjects>
@endsection
