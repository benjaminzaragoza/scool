@extends('tenants.layouts.app')

@section('content')
    <moodle-users action="{{ $action }}" :users="{{ $users }}" :local-users="{{ $localUsers }}"></moodle-users>
@endsection
