@extends('tenants.layouts.app')

@section('content')
    <positions
            :positions="{{ $positions }}"
            :users="{{ $users }}"
    ></positions>
@endsection
