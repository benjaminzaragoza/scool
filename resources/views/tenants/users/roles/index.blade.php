@extends('tenants.layouts.app')

@section('content')

    <role-add :roles="{{ $roles }}" :roles="{{ $roles }}"></role-add>

    <roles-list :roles="{{ $roles }}"></roles-list>

@endsection


