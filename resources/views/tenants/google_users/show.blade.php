@extends('tenants.layouts.app')

@section('content')

    <google-user-add></google-user-add>

    <google-users :users="{{ $users }}"></google-users>

@endsection


