@extends('tenants.layouts.app')

@section('content')
    <moodle-users :users="{{ $users }}"></moodle-users>
@endsection


