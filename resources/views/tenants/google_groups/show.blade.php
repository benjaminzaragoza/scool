@extends('tenants.layouts.app')

@section('content')

    <google-group-add></google-group-add>

    <google-groups :groups="{{ $groups }}"></google-groups>

@endsection


