@extends('tenants.layouts.app')

@section('content')
    <floating-add>
        <incident-add></incident-add>
    </floating-add>

    <incidents-list :incidents="{{ $incidents }}"></incidents-list>

@endsection


