@extends('tenants.layouts.app')

@section('content')

    <google-user-add action="{{ $action }}"></google-user-add>

    <google-users :users="{{ $users }}"></google-users>

@endsection


