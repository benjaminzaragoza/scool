@extends('tenants.layouts.app')

@section('content')
    <positions
            :positions="{{ $positions }}"
    ></positions>
@endsection
