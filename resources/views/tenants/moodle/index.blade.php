@extends('tenants.layouts.app')

@section('content')
    <moodle-users :users="{{ $users }}" :local-users="{{ $localUsers }}"></moodle-users>
@endsection
