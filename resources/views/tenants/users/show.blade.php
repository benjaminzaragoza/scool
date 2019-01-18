@extends('tenants.layouts.app')

@section('content')

    <user-add :roles="{{ $roles }}" :users="{{ $users }}"></user-add>

    <users-list :users="{{ $users }}" :user-types=" {{ $userTypes }}" :roles="{{ $roles }}"></users-list>

    <users-dashboard></users-dashboard>

@endsection


