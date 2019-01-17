@extends('tenants.layouts.app')

@section('content')

    <permission-add :permissions="{{ $permissions }}"></permission-add>

    <permissions-list :permissions="{{ $permissions }}"></permissions-list>


@endsection


