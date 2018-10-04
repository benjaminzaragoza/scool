@extends('tenants.layouts.app')

@section('content')
    <incident-add></incident-add>

    <incidents-list :incidents="{{ $incidents }}"></incidents-list>

@endsection


