@extends('tenants.layouts.app')

@section('content')

    <incidents-list :incidents="{{ $incidents }}"></incidents-list>

@endsection


