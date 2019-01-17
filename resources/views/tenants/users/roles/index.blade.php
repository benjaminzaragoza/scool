@extends('tenants.layouts.app')

@section('content')
    <roles :roles="{{ $roles }}"></roles>
@endsection


