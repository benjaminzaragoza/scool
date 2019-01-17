@extends('tenants.layouts.app')

@section('content')
    <permissions :permissions="{{ $permissions }}"></permissions>
@endsection


